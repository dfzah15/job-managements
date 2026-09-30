<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanAktivitas;
use App\Models\Lokasi;
use App\Models\Jobdesk;
use App\Exports\LaporanAktivitasExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\GambarService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class LaporanAktivitasController extends Controller
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
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $query = LaporanAktivitas::where('jobdesk_id', $jobdesk->id)->with('lokasi');

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Filter by lokasi
        if ($request->lokasi_id) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status_pekerjaan', $request->status);
        }

        // Filter by tanggal
        if ($request->date_from) {
            $query->whereDate('tanggal_laporan', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('tanggal_laporan', '<=', $request->date_to);
        }

        $laporan = $query->latest()->paginate(15);
        $lokasi = Lokasi::all();
        $statusOptions = ['selesai', 'pending', 'proses'];

        return view('laporan.index', compact('laporan', 'lokasi', 'statusOptions', 'jobdesk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($jobdeskSlug)
    {
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        $lokasi = Lokasi::all();
        
        return view('laporan.create', compact('lokasi', 'jobdesk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($jobdeskSlug, Request $request)
    {
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,id',
            'jenis_aktivitas' => 'required|in:perbaikan,pemeliharaan,inspeksi',
            'keterangan' => 'required|string|max:255',
            'checklist_camera' => 'nullable|boolean',
            'checklist_dvr' => 'nullable|boolean',
            'checklist_monitor' => 'nullable|boolean',
            'checklist_kabel_camera' => 'nullable|boolean',
            'checklist_kabel_listrik' => 'nullable|boolean',
            'checklist_konektor' => 'nullable|boolean',
            'kendala_kerusakan' => 'nullable|string',
            'jumlah_rusak' => 'nullable|integer|min:0',
            'status_pekerjaan' => 'required|in:selesai,pending,proses',
            'solusi' => 'nullable|string',
            'tanggal_laporan' => 'required|date',
            'pelapor' => 'required|string|max:255',
            'teknisi' => 'nullable|string|max:255'
        ]);

        $data = $request->all();
        $data['jobdesk_id'] = $jobdesk->id;
        $data['user_id'] = Auth::id();
        $data['checklist_camera'] = $request->has('checklist_camera') ? 1 : 0;
        $data['checklist_dvr'] = $request->has('checklist_dvr') ? 1 : 0;
        $data['checklist_monitor'] = $request->has('checklist_monitor') ? 1 : 0;
        $data['checklist_kabel_camera'] = $request->has('checklist_kabel_camera') ? 1 : 0;
        $data['checklist_kabel_listrik'] = $request->has('checklist_kabel_listrik') ? 1 : 0;
        $data['checklist_konektor'] = $request->has('checklist_konektor') ? 1 : 0;

        $laporan = LaporanAktivitas::create($data);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $this->gambarService->upload($file, $laporan, 'Dokumentasi laporan');
            }
        }

        return redirect()->route('laporan.index', $jobdeskSlug)
            ->with('success', 'Laporan aktivitas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($jobdeskSlug, $id)
    {
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $laporan = LaporanAktivitas::where('jobdesk_id', $jobdesk->id)
                                    ->with(['lokasi', 'gambar', 'laporanEksekusi.user'])
                                    ->findOrFail($id);
        
        return view('laporan.show', compact('laporan', 'jobdesk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jobdeskSlug, $id)
    {
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $laporan = LaporanAktivitas::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        $lokasi = Lokasi::all();
        
        return view('laporan.edit', compact('laporan', 'lokasi', 'jobdesk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($jobdeskSlug, Request $request, $id)
    {
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $laporan = LaporanAktivitas::where('jobdesk_id', $jobdesk->id)->findOrFail($id);

        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,id',
            'jenis_aktivitas' => 'required|in:perbaikan,pemeliharaan,inspeksi',
            'keterangan' => 'required|string|max:255',
            'checklist_camera' => 'nullable|boolean',
            'checklist_dvr' => 'nullable|boolean',
            'checklist_monitor' => 'nullable|boolean',
            'checklist_kabel_camera' => 'nullable|boolean',
            'checklist_kabel_listrik' => 'nullable|boolean',
            'checklist_konektor' => 'nullable|boolean',
            'kendala_kerusakan' => 'nullable|string',
            'jumlah_rusak' => 'nullable|integer|min:0',
            'status_pekerjaan' => 'required|in:selesai,pending,proses',
            'solusi' => 'nullable|string',
            'tanggal_laporan' => 'required|date',
            'pelapor' => 'required|string|max:255',
            'teknisi' => 'nullable|string|max:255'
        ]);

        $data = $request->all();
        $data['checklist_camera'] = $request->has('checklist_camera') ? 1 : 0;
        $data['checklist_dvr'] = $request->has('checklist_dvr') ? 1 : 0;
        $data['checklist_monitor'] = $request->has('checklist_monitor') ? 1 : 0;
        $data['checklist_kabel_camera'] = $request->has('checklist_kabel_camera') ? 1 : 0;
        $data['checklist_kabel_listrik'] = $request->has('checklist_kabel_listrik') ? 1 : 0;
        $data['checklist_konektor'] = $request->has('checklist_konektor') ? 1 : 0;

        $laporan->update($data);

        // Upload gambar baru
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $this->gambarService->upload($file, $laporan, 'Dokumentasi laporan');
            }
        }

        return redirect()->route('laporan.index', $jobdeskSlug)
            ->with('success', 'Laporan aktivitas berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jobdeskSlug, $id)
    {
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $laporan = LaporanAktivitas::where('jobdesk_id', $jobdesk->id)->findOrFail($id);
        
        // Hapus semua gambar
        foreach ($laporan->gambar as $gambar) {
            $this->gambarService->delete($gambar);
        }
        
        $laporan->delete();
        
        return redirect()->route('laporan.index', $jobdeskSlug)
            ->with('success', 'Laporan aktivitas berhasil dihapus');
    }

    /**
     * Export to PDF
     */
    public function exportPdf($jobdeskSlug)
    {
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $laporan = LaporanAktivitas::where('jobdesk_id', $jobdesk->id)
                                    ->with(['lokasi', 'gambar'])
                                    ->latest()
                                    ->get();
        
        $pdf = PDF::loadView('laporan.pdf', compact('laporan', 'jobdesk'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-aktivitas-' . $jobdesk->slug . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export to Excel
     */
    public function exportExcel($jobdeskSlug, Request $request)
    {
        $jobdesk = Jobdesk::where('slug', $jobdeskSlug)->firstOrFail();
        
        $filters = $request->all();
        $filters['jobdesk_id'] = $jobdesk->id;
        
        $export = new LaporanAktivitasExport($filters);
        
        return Excel::download($export, 'laporan-aktivitas-' . $jobdesk->slug . '-' . date('Y-m-d') . '.xlsx');
    }
}