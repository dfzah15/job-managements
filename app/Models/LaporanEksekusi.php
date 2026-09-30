<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanEksekusi extends Model
{
    use HasFactory;

    protected $table = 'laporan_eksekusi';

    protected $fillable = [
        'jobdesk_id', // <-- PASTIKAN INI ADA
        'lokasi_id',
        'laporan_aktivitas_id',
        'user_id',
        'tanggal_eksekusi',
        'waktu_mulai',
        'waktu_selesai',
        'deskripsi_pekerjaan',
        'hasil',
        'status_eksekusi',
        'catatan'
    ];

    protected $casts = [
        'tanggal_eksekusi' => 'date',
    ];

    // ============================================
    // RELASI KE JOBDESK (TAMBAHKAN INI)
    // ============================================
    public function jobdesk()
    {
        return $this->belongsTo(Jobdesk::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function laporanAktivitas()
    {
        return $this->belongsTo(LaporanAktivitas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gambar()
    {
        return $this->morphMany(Gambar::class, 'gambarable');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'selesai' => 'success',
            'pending' => 'warning',
            'proses' => 'info',
            'gagal' => 'danger',
        ];
        return $badges[$this->status_eksekusi] ?? 'secondary';
    }
}