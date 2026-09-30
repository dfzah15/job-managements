<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lokasi::withCount(['inventaris', 'checklistCctv', 'laporanAktivitas']);

        // Pencarian
        if ($request->search) {
            $query->where('nama_lokasi', 'LIKE', "%{$request->search}%")
                  ->orWhere('kode_lokasi', 'LIKE', "%{$request->search}%")
                  ->orWhere('alamat', 'LIKE', "%{$request->search}%");
        }

        $lokasi = $query->latest()->paginate(15);
        return view('lokasi.index', compact('lokasi'));
    }

    public function create()
    {
        return view('lokasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'kode_lokasi' => 'required|string|unique:lokasi,kode_lokasi|max:50',
            'alamat' => 'nullable|string'
        ]);

        Lokasi::create($request->all());
        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function show(Lokasi $lokasi)
    {
        $lokasi->load(['inventaris', 'checklistCctv', 'laporanAktivitas']);
        return view('lokasi.show', compact('lokasi'));
    }

    public function edit(Lokasi $lokasi)
    {
        return view('lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'kode_lokasi' => 'required|string|unique:lokasi,kode_lokasi,' . $lokasi->id . '|max:50',
            'alamat' => 'nullable|string'
        ]);

        $lokasi->update($request->all());
        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil diupdate');
    }

    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();
        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil dihapus');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $lokasi = Lokasi::where('nama_lokasi', 'LIKE', "%{$query}%")
                       ->orWhere('kode_lokasi', 'LIKE', "%{$query}%")
                       ->limit(10)
                       ->get();
        return response()->json($lokasi);
    }
}