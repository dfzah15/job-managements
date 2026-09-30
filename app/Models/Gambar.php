<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gambar extends Model
{
    use HasFactory;

    protected $table = 'gambar';
    protected $fillable = [
        'gambarable_id', 'gambarable_type', 'nama_file',
        'path', 'thumbnail_path', 'mime_type', 'size',
        'size_compress', 'is_compressed', 'keterangan'
    ];

    public function gambarable()
    {
        return $this->morphTo();
    }

    // Helper untuk mendapatkan URL
    public function getUrlAttribute()
    {
        return $this->path ? asset('storage/' . $this->path) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : $this->url;
    }

    // Format size
    public function getSizeFormattedAttribute()
    {
        $size = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
}