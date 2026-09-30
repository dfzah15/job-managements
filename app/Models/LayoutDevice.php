<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayoutDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'layout_id', 
        'inventaris_id', 
        'nama_device', 
        'tipe_device',
        'pos_x', 
        'pos_y', 
        'rotation', 
        'icon', 
        'color', 
        'keterangan', 
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function layout()
    {
        return $this->belongsTo(Layout::class);
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function connectionsFrom()
    {
        return $this->hasMany(LayoutConnection::class, 'device_from_id');
    }

    public function connectionsTo()
    {
        return $this->hasMany(LayoutConnection::class, 'device_to_id');
    }

    public function getIconHtmlAttribute()
    {
        $icons = [
            'cctv' => 'bi bi-camera',
            'panel' => 'bi bi-grid',
            'genset' => 'bi bi-fuel-pump',
            'pompa' => 'bi bi-water-pump',
            'server' => 'bi bi-server',
            'dvr' => 'bi bi-hdd-stack',
            'monitor' => 'bi bi-display',
            'router' => 'bi bi-wifi',
            'switch' => 'bi bi-diagram-3',
            'panel_surya' => 'bi bi-sun',
            'baterai' => 'bi bi-battery',
            'default' => 'bi bi-geo-alt',
        ];
        return $icons[$this->tipe_device] ?? $icons['default'];
    }
}