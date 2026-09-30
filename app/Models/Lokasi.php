<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';
    protected $fillable = ['nama_lokasi', 'kode_lokasi', 'alamat'];

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class);
    }

    public function checklistCctv()
    {
        return $this->hasMany(ChecklistCctv::class);
    }

    public function laporanAktivitas()
    {
        return $this->hasMany(LaporanAktivitas::class);
    }
}