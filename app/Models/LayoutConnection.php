<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayoutConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'layout_id',
        'device_from_id',
        'device_to_id',
        'tipe_kabel',
        'panjang_meter',
        'warna',
        'label'
    ];

    protected $casts = [
        'panjang_meter' => 'decimal:2',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Get the layout that owns the connection.
     */
    public function layout()
    {
        return $this->belongsTo(Layout::class);
    }

    /**
     * Get the source device.
     */
    public function sourceDevice()
    {
        return $this->belongsTo(LayoutDevice::class, 'device_from_id');
    }

    /**
     * Get the target device.
     */
    public function targetDevice()
    {
        return $this->belongsTo(LayoutDevice::class, 'device_to_id');
    }

    /**
     * Alias for sourceDevice (for backward compatibility)
     */
    public function fromDevice()
    {
        return $this->belongsTo(LayoutDevice::class, 'device_from_id');
    }

    /**
     * Alias for targetDevice (for backward compatibility)
     */
    public function toDevice()
    {
        return $this->belongsTo(LayoutDevice::class, 'device_to_id');
    }
}