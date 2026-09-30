<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventaris;
use App\Models\Lokasi;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\Auth;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cek apakah user admin - jika admin lihat semua
        if (Auth::user()->role == 'admin') {
            $query = Inventaris::with(['lokasi', 'jobdesk']);
        } else {
            // Non-admin hanya lihat inventaris dari jobdesk yang di-assign
            $jobdeskIds = Auth::user()->jobdesks()->pluck('jobdesks.id');
            $query = Inventaris::whereIn('jobdesk_id', $jobdeskIds)->with(['lokasi', 'jobdesk']);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_inventaris', 'LIKE', "%{$request->search}%")
                  ->orWhere('merk', 'LIKE', "%{$request->search}%")
                  ->orWhere('model', 'LIKE', "%{$request->search}%")
                  ->orWhere('kode_inventaris', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('lokasi', function($l) use ($request) {
                      $l->where('nama_lokasi', 'LIKE', "%{$request->search}%");
                  });
            });
        }

        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->jobdesk_id) {
            $query->where('jobdesk_id', $request->jobdesk_id);
        }

        $inventaris = $query->latest()->paginate(15);
        $lokasi = Lokasi::all();
        $jobdesks = Jobdesk::where('is_active', true)->get();

        return view('inventaris.index', compact('inventaris', 'lokasi', 'jobdesks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lokasi = Lokasi::all();
        $jobdesks = Jobdesk::where('is_active', true)->get();
        return view('inventaris.create', compact('lokasi', 'jobdesks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jobdesk_id' => 'required|exists:jobdesks,id',
            'lokasi_id' => 'required|exists:lokasi,id',
            'nama_inventaris' => 'required|string|max:255',
            'jenis' => 'required|in:cctv,dvr,monitor,kabel,konektor,server,genset,pompa,panel,other',
            'merk' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'spesifikasi' => 'nullable|string',
            'tanggal_pemasangan' => 'nullable|date',
            'status' => 'required|in:aktif,rusak,perbaikan,nonaktif'
        ]);

        $data = $request->all();
        // Generate kode inventaris
        $lastInventaris = Inventaris::orderBy('id', 'desc')->first();
        $nextId = $lastInventaris ? $lastInventaris->id + 1 : 1;
        $data['kode_inventaris'] = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        Inventaris::create($data);

        return redirect()->route('inventaris.index')
            ->with('success', 'Inventaris berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $inventaris = Inventaris::with(['lokasi', 'jobdesk'])->findOrFail($id);
        return view('inventaris.show', compact('inventaris'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $lokasi = Lokasi::all();
        $jobdesks = Jobdesk::where('is_active', true)->get();
        return view('inventaris.edit', compact('inventaris', 'lokasi', 'jobdesks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);

        $request->validate([
            'jobdesk_id' => 'required|exists:jobdesks,id',
            'lokasi_id' => 'required|exists:lokasi,id',
            'nama_inventaris' => 'required|string|max:255',
            'jenis' => 'required|in:cctv,dvr,monitor,kabel,konektor,server,genset,pompa,panel,other',
            'merk' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'spesifikasi' => 'nullable|string',
            'tanggal_pemasangan' => 'nullable|date',
            'status' => 'required|in:aktif,rusak,perbaikan,nonaktif'
        ]);

        $inventaris->update($request->all());

        return redirect()->route('inventaris.index')
            ->with('success', 'Inventaris berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $inventaris->delete();
        
        return redirect()->route('inventaris.index')
            ->with('success', 'Inventaris berhasil dihapus');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $jobdeskId = $request->get('jobdesk_id');
        
        $inventaris = Inventaris::where('nama_inventaris', 'LIKE', "%{$query}%");
        
        if ($jobdeskId) {
            $inventaris->where('jobdesk_id', $jobdeskId);
        }
        
        $inventaris = $inventaris->limit(10)->get();
        
        return response()->json($inventaris);
    }
}