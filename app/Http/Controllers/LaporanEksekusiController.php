<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanEksekusi;
use App\Models\LaporanAktivitas;
use App\Models\Lokasi;
use App\Models\User;
use App\Models\Jobdesk;
use App\Services\GambarService;
use Illuminate\Support\Facades\Auth;

class LaporanEksekusiController extends Controller
{
    protected $gambarService;

    public function __construct(GambarService $gambarService)
    {
        $this->gambarService = $gambarService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($jobdeskSlug, Request $request)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $query = LaporanEksekusi::where('jobdesk_id', $jobdesk->id)
                                ->with(['lokasi', 'laporanAktivitas', 'user']);

        // Pencarian
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('deskripsi_pekerjaan', 'LIKE', "%{$request->search}%")
                  ->orWhere('hasil', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('lokasi', function($l) use ($request) {
                      $l->where('nama_lokasi', 'LIKE', "%{$request->search}%");
                  });
            });
        }

        // Filter status
        if ($request->status) {
            $query->where('status_eksekusi', $request->status);
        }

        // Filter tanggal
        if ($request->date_from) {
            $query->whereDate('tanggal_eksekusi', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('tanggal_eksekusi', '<=', $request->date_to);
        }

        $eksekusi = $query->latest()->paginate(15);
        $statusOptions = ['pending', 'proses', 'selesai', 'gagal'];

        return view('laporan_eksekusi.index', compact('eksekusi', 'statusOptions', 'jobdesk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($jobdeskSlug)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $laporan = LaporanAktivitas::where('jobdesk_id', $jobdesk->id)
                                   ->where('status_pekerjaan', '!=', 'selesai')
                                   ->get();
        $lokasi = Lokasi::all();
        $teknisi = User::where('role', 'teknisi')->where('is_active', true)->get();

        return view('laporan_eksekusi.create', compact('laporan', 'lokasi', 'teknisi', 'jobdesk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($jobdeskSlug, Request $request)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,id',
            'laporan_aktivitas_id' => 'required|exists:laporan_aktivitas,id',
            'user_id' => 'required|exists:users,id',
            'tanggal_eksekusi' => 'required|date',
            'waktu_mulai' => 'required',
            'deskripsi_pekerjaan' => 'required|string',
            'status_eksekusi' => 'required|in:pending,proses,selesai,gagal',
        ]);

        $data = $request->all();
        $data['jobdesk_id'] = $jobdesk->id;

        $eksekusi = LaporanEksekusi::create($data);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $this->gambarService->upload($file, $eksekusi, 'Dokumentasi eksekusi');
            }
        }

        return redirect()->route('laporan-eksekusi.index', $jobdeskSlug)
            ->with('success', 'Laporan eksekusi berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($jobdeskSlug, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        // Cari data
        $eksekusi = LaporanEksekusi::where('jobdesk_id', $jobdesk->id)
                                    ->with(['lokasi', 'laporanAktivitas', 'user', 'gambar'])
                                    ->find($id);
        
        // Jika data tidak ditemukan
        if (!$eksekusi) {
            return redirect()->route('laporan-eksekusi.index', $jobdeskSlug)
                ->with('error', 'Data laporan eksekusi tidak ditemukan.');
        }
        
        return view('laporan_eksekusi.show', compact('eksekusi', 'jobdesk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jobdeskSlug, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $eksekusi = LaporanEksekusi::where('jobdesk_id', $jobdesk->id)
                                    ->with(['lokasi', 'laporanAktivitas', 'user', 'gambar'])
                                    ->findOrFail($id);
        
        $lokasi = Lokasi::all();
        $laporan = LaporanAktivitas::where('jobdesk_id', $jobdesk->id)->get();
        $teknisi = User::where('role', 'teknisi')->where('is_active', true)->get();

        return view('laporan_eksekusi.edit', compact('eksekusi', 'lokasi', 'laporan', 'teknisi', 'jobdesk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($jobdeskSlug, Request $request, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $eksekusi = LaporanEksekusi::where('jobdesk_id', $jobdesk->id)->findOrFail($id);

        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,id',
            'laporan_aktivitas_id' => 'required|exists:laporan_aktivitas,id',
            'user_id' => 'required|exists:users,id',
            'tanggal_eksekusi' => 'required|date',
            'waktu_mulai' => 'required',
            'deskripsi_pekerjaan' => 'required|string',
            'status_eksekusi' => 'required|in:pending,proses,selesai,gagal',
        ]);

        $eksekusi->update($request->all());

        // Upload gambar baru
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $this->gambarService->upload($file, $eksekusi, 'Dokumentasi eksekusi');
            }
        }

        return redirect()->route('laporan-eksekusi.index', $jobdeskSlug)
            ->with('success', 'Laporan eksekusi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jobdeskSlug, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $eksekusi = LaporanEksekusi::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        
        // Hapus semua gambar
        foreach ($eksekusi->gambar as $gambar) {
            $this->gambarService->delete($gambar);
        }
        
        $eksekusi->delete();
        
        return redirect()->route('laporan-eksekusi.index', $jobdeskSlug)
            ->with('success', 'Laporan eksekusi berhasil dihapus');
    }

    /**
     * Start eksekusi
     */
    public function start($jobdeskSlug, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $eksekusi = LaporanEksekusi::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        $eksekusi->update([
            'status_eksekusi' => 'proses',
            'waktu_mulai' => now()
        ]);

        return redirect()->route('laporan-eksekusi.index', $jobdeskSlug)
            ->with('success', 'Eksekusi dimulai');
    }

    /**
     * Complete eksekusi
     */
    public function complete($jobdeskSlug, Request $request, $id)
    {
        // Ambil jobdesk
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $eksekusi = LaporanEksekusi::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        
        $request->validate([
            'hasil' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $eksekusi->update([
            'status_eksekusi' => 'selesai',
            'waktu_selesai' => now(),
            'hasil' => $request->hasil,
            'catatan' => $request->catatan
        ]);

        // Update status laporan aktivitas
        $laporan = $eksekusi->laporanAktivitas;
        if ($laporan) {
            $laporan->update([
                'status_pekerjaan' => 'selesai',
                'solusi' => $request->hasil
            ]);
        }

        return redirect()->route('laporan-eksekusi.index', $jobdeskSlug)
            ->with('success', 'Eksekusi selesai');
    }
}