<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobdesk_id', 'lokasi_id', 'inventaris_id', 'judul', 'deskripsi', 'jenis',
        'status', 'tanggal_mulai', 'tanggal_selesai', 'waktu_mulai',
        'waktu_selesai', 'teknisi', 'catatan', 'is_recurring',
        'recurring_pattern'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'scheduled' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getJenisBadgeAttribute()
    {
        $badges = [
            'rutin' => 'primary',
            'khusus' => 'warning',
            'tahunan' => 'success',
        ];
        return $badges[$this->jenis] ?? 'secondary';
    }
}