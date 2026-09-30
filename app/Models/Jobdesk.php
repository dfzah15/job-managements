<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobdesk extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'color', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_jobdesks')
                    ->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete', 'can_export', 'can_approve']);
    }

    // Relasi ke tabel yang sudah ada
    public function checklists()
    {
        return $this->hasMany(ChecklistCctv::class);
    }

    public function laporanAktivitas()
    {
        return $this->hasMany(LaporanAktivitas::class);
    }

    public function laporanEksekusi()
    {
        return $this->hasMany(LaporanEksekusi::class);
    }

    public function maintenanceSchedules()
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function getColorBadgeAttribute()
    {
        $colors = [
            'primary' => 'primary',
            'success' => 'success',
            'danger' => 'danger',
            'warning' => 'warning',
            'info' => 'info',
            'secondary' => 'secondary',
        ];
        return $colors[$this->color] ?? 'secondary';
    }
}