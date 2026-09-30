<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAktivitas extends Model
{
    use HasFactory;

    protected $table = 'laporan_aktivitas';
    protected $fillable = [
        'jobdesk_id', 'lokasi_id', 'jenis_aktivitas', 'keterangan',
        'checklist_camera', 'checklist_dvr', 'checklist_monitor',
        'checklist_kabel_camera', 'checklist_kabel_listrik', 'checklist_konektor',
        'kendala_kerusakan', 'jumlah_rusak', 'status_pekerjaan',
        'solusi', 'tanggal_laporan', 'pelapor', 'teknisi'
    ];

    protected $casts = [
        'checklist_camera' => 'boolean',
        'checklist_dvr' => 'boolean',
        'checklist_monitor' => 'boolean',
        'checklist_kabel_camera' => 'boolean',
        'checklist_kabel_listrik' => 'boolean',
        'checklist_konektor' => 'boolean',
        'tanggal_laporan' => 'date',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function laporanEksekusi()
    {
        return $this->hasMany(LaporanEksekusi::class);
    }

    // RELASI KE GAMBAR (polymorphic)
    public function gambar()
    {
        return $this->morphMany(Gambar::class, 'gambarable');
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) return $query;
        
        return $query->where('keterangan', 'LIKE', "%{$search}%")
            ->orWhere('kendala_kerusakan', 'LIKE', "%{$search}%")
            ->orWhere('solusi', 'LIKE', "%{$search}%")
            ->orWhere('pelapor', 'LIKE', "%{$search}%")
            ->orWhere('teknisi', 'LIKE', "%{$search}%")
            ->orWhereHas('lokasi', function($q) use ($search) {
                $q->where('nama_lokasi', 'LIKE', "%{$search}%")
                  ->orWhere('kode_lokasi', 'LIKE', "%{$search}%");
            });
    }
}