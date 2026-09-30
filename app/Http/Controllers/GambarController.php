<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gambar;
use App\Services\GambarService;

class GambarController extends Controller
{
    protected $gambarService;

    public function __construct(GambarService $gambarService)
    {
        $this->gambarService = $gambarService;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:20480',
            'model_id' => 'required',
            'model_type' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $modelClass = 'App\\Models\\' . $request->model_type;
        
        if (!class_exists($modelClass)) {
            return response()->json([
                'success' => false,
                'message' => 'Model tidak ditemukan'
            ], 404);
        }
        
        $model = $modelClass::findOrFail($request->model_id);

        $gambar = $this->gambarService->upload(
            $request->file('file'),
            $model,
            $request->keterangan
        );

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil diupload',
            'data' => $gambar,
            'url' => asset('storage/' . $gambar->path),
        ]);
    }

    public function destroy($id)
    {
        $gambar = Gambar::findOrFail($id);
        $this->gambarService->delete($gambar);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus'
        ]);
    }
}