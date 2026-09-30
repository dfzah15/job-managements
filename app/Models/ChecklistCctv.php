<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistCctv extends Model
{
    use HasFactory;

    protected $table = 'checklist_cctv';

    protected $fillable = [
        'jobdesk_id', 'lokasi_id', 'inventaris_id', // tambahkan jobdesk_id
        'tanggal_check', 'waktu_check', 'petugas_check',
        'jumlah_camera', 'status_hdd', 'kapasitas_hdd',
        'jumlah_channel_dvr', 'status_display', 'jumlah_kamera_mati',
        'catatan'
    ];

    // Tambahkan relasi ke jobdesk
    public function jobdesk()
    {
        return $this->belongsTo(Jobdesk::class);
    }

    // ... relasi lainnya ...
}