<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Gambar;

class GambarService
{
    /**
     * Upload gambar tanpa compress
     */
    public function upload($file, $model, $keterangan = null)
    {
        // Generate nama file unik
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/' . date('Y/m/d');
        
        // Simpan file
        $originalPath = $file->storeAs($path, $filename, 'public');
        
        // Data gambar
        $gambarData = [
            'nama_file' => $filename,
            'path' => $originalPath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'keterangan' => $keterangan,
            'is_compressed' => false,
        ];

        // Simpan ke database
        $gambar = $model->gambar()->create($gambarData);
        
        return $gambar;
    }

    /**
     * Hapus gambar
     */
    public function delete($gambar)
    {
        // Hapus file
        if ($gambar->path) {
            Storage::disk('public')->delete($gambar->path);
        }
        if ($gambar->thumbnail_path) {
            Storage::disk('public')->delete($gambar->thumbnail_path);
        }
        
        // Hapus record
        return $gambar->delete();
    }
}