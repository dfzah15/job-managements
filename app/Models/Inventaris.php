<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'jobdesk_id', // <-- TAMBAHKAN INI
        'lokasi_id',
        'kode_inventaris',
        'nama_inventaris',
        'jenis',
        'merk',
        'model',
        'jumlah',
        'spesifikasi',
        'tanggal_pemasangan',
        'status'
    ];

    protected $casts = [
        'tanggal_pemasangan' => 'date',
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

    public function checklists()
    {
        return $this->hasMany(ChecklistResult::class);
    }

    public function maintenanceSchedules()
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    // HAPUS ATAU COMMENT BOOT METHOD DI BAWAH INI
    /*
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($inventaris) {
            if (!$inventaris->kode_inventaris) {
                $inventaris->kode_inventaris = 'INV-' . str_pad(($inventaris->id ?? 0) + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }
    */

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'aktif' => 'success',
            'rusak' => 'danger',
            'perbaikan' => 'warning',
            'nonaktif' => 'secondary',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getJenisBadgeAttribute()
    {
        $badges = [
            'cctv' => 'info',
            'dvr' => 'primary',
            'monitor' => 'success',
            'kabel' => 'warning',
            'konektor' => 'secondary',
            'server' => 'danger',
            'genset' => 'warning',
            'pompa' => 'info',
            'panel' => 'danger',
            'other' => 'secondary',
        ];
        return $badges[$this->jenis] ?? 'secondary';
    }
}