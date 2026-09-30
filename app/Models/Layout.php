<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Layout extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jobdesk_id',
        'nama_layout',
        'slug',
        'deskripsi',
        'image_path',
        'width',
        'height',
        'metadata',
        'created_by',
        'updated_by',
        'is_active',
        'version',
        'last_edited_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'width' => 'integer',
        'height' => 'integer',
        'is_active' => 'boolean',
        'version' => 'integer',
        'last_edited_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'device_count',
        'connection_count',
        'thumbnail_url',
        'formatted_created_at'
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Get the jobdesk that owns the layout.
     */
    public function jobdesk()
    {
        return $this->belongsTo(Jobdesk::class);
    }

    /**
     * Get the devices for the layout.
     */
    public function devices()
    {
        return $this->hasMany(LayoutDevice::class)->orderBy('pos_y')->orderBy('pos_x');
    }

    /**
     * Get the connections for the layout.
     */
    public function connections()
    {
        return $this->hasMany(LayoutConnection::class)->orderBy('created_at');
    }

    /**
     * Get the user who created the layout.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the layout.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope a query to only include active layouts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include layouts from a specific jobdesk.
     */
    public function scopeForJobdesk($query, $jobdeskId)
    {
        return $query->where('jobdesk_id', $jobdeskId);
    }

    /**
     * Scope a query to search layouts by name or description.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_layout', 'LIKE', "%{$search}%")
            ->orWhere('deskripsi', 'LIKE', "%{$search}%");
    }

    /**
     * Scope a query to get recent layouts.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    // ============================================================
    // ACCESSORS & MUTATORS
    // ============================================================

    /**
     * Get the device count for the layout.
     */
    public function getDeviceCountAttribute()
    {
        return $this->devices()->count();
    }

    /**
     * Get the connection count for the layout.
     */
    public function getConnectionCountAttribute()
    {
        return $this->connections()->count();
    }

    /**
     * Get the thumbnail URL.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->image_path && \Storage::disk('public')->exists($this->image_path)) {
            return \Storage::disk('public')->url($this->image_path);
        }
        return asset('images/default-layout.png');
    }

    /**
     * Get formatted created at date.
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d M Y H:i') : null;
    }

    /**
     * Get formatted last edited at date.
     */
    public function getFormattedLastEditedAtAttribute()
    {
        return $this->last_edited_at ? $this->last_edited_at->format('d M Y H:i') : null;
    }

    /**
     * Get the layout dimensions as array.
     */
    public function getDimensionsAttribute()
    {
        return [
            'width' => $this->width ?? 1200,
            'height' => $this->height ?? 800
        ];
    }

    /**
     * Get the total area of the layout.
     */
    public function getAreaAttribute()
    {
        return ($this->width ?? 1200) * ($this->height ?? 800);
    }

    /**
     * Set the nama_layout attribute and generate slug automatically.
     */
    public function setNamaLayoutAttribute($value)
    {
        $this->attributes['nama_layout'] = $value;
        $this->attributes['slug'] = Str::slug($value) . '-' . time();
    }

    /**
     * Set the metadata attribute with merging.
     */
    public function setMetadataAttribute($value)
    {
        $existing = $this->metadata ?? [];
        if (is_array($value)) {
            $this->attributes['metadata'] = json_encode(array_merge($existing, $value));
        } else {
            $this->attributes['metadata'] = $value;
        }
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Check if layout has background image.
     */
    public function hasBackground()
    {
        return !empty($this->image_path) && \Storage::disk('public')->exists($this->image_path);
    }

    /**
     * Get the full URL of the background image.
     */
    public function getBackgroundUrl()
    {
        if ($this->hasBackground()) {
            return \Storage::disk('public')->url($this->image_path);
        }
        return null;
    }

    /**
     * Delete the background image.
     */
    public function deleteBackground()
    {
        if ($this->hasBackground()) {
            \Storage::disk('public')->delete($this->image_path);
            $this->update(['image_path' => null]);
            return true;
        }
        return false;
    }

    /**
     * Get all devices grouped by type.
     */
    public function getDevicesGroupedByType()
    {
        return $this->devices()
            ->select('tipe_device', \DB::raw('count(*) as total'))
            ->groupBy('tipe_device')
            ->get()
            ->pluck('total', 'tipe_device')
            ->toArray();
    }

    /**
     * Check if layout has any devices.
     */
    public function hasDevices()
    {
        return $this->devices()->exists();
    }

    /**
     * Check if layout has any connections.
     */
    public function hasConnections()
    {
        return $this->connections()->exists();
    }

    /**
     * Get connected devices network.
     */
    public function getNetworkGraph()
    {
        $graph = [];
        foreach ($this->connections as $connection) {
            if (!isset($graph[$connection->device_from_id])) {
                $graph[$connection->device_from_id] = [];
            }
            $graph[$connection->device_from_id][] = $connection->device_to_id;
        }
        return $graph;
    }

    /**
     * Get devices that are not connected to any other device.
     */
    public function getIsolatedDevices()
    {
        $connectedIds = $this->connections()
            ->select('device_from_id')
            ->union($this->connections()->select('device_to_id'))
            ->pluck('device_from_id')
            ->unique()
            ->toArray();

        return $this->devices()
            ->whereNotIn('id', $connectedIds)
            ->get();
    }

    /**
     * Duplicate the layout with all its devices and connections.
     */
    public function duplicate($newName = null)
    {
        $newLayout = $this->replicate();
        $newLayout->nama_layout = $newName ?? $this->nama_layout . ' (Copy)';
        $newLayout->slug = Str::slug($newLayout->nama_layout) . '-' . time();
        $newLayout->created_at = now();
        $newLayout->updated_at = now();
        $newLayout->save();

        // Duplicate devices
        foreach ($this->devices as $device) {
            $newDevice = $device->replicate();
            $newDevice->layout_id = $newLayout->id;
            $newDevice->save();
        }

        // Duplicate connections
        foreach ($this->connections as $connection) {
            $newConnection = $connection->replicate();
            $newConnection->layout_id = $newLayout->id;
            $newConnection->save();
        }

        return $newLayout;
    }

    /**
     * Get statistics for the layout.
     */
    public function getStatistics()
    {
        return [
            'total_devices' => $this->device_count,
            'total_connections' => $this->connection_count,
            'device_types' => $this->getDevicesGroupedByType(),
            'isolated_devices' => $this->getIsolatedDevices()->count(),
            'has_background' => $this->hasBackground(),
            'dimensions' => $this->dimensions,
            'area' => $this->area,
            'created_at' => $this->formatted_created_at,
            'last_edited' => $this->formatted_last_edited_at,
        ];
    }

    /**
     * Update last edited timestamp.
     */
    public function touchLastEdited()
    {
        $this->update(['last_edited_at' => now()]);
    }

    /**
     * Increment version number.
     */
    public function incrementVersion()
    {
        $this->increment('version');
        $this->touchLastEdited();
    }

    /**
     * Get a specific metadata value.
     */
    public function getMetadata($key, $default = null)
    {
        return data_get($this->metadata, $key, $default);
    }

    /**
     * Set a specific metadata value.
     */
    public function setMetadata($key, $value)
    {
        $metadata = $this->metadata ?? [];
        data_set($metadata, $key, $value);
        $this->update(['metadata' => $metadata]);
    }

    // ============================================================
    // BOOT METHOD
    // ============================================================

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Auto-generate slug when creating
        static::creating(function ($layout) {
            if (empty($layout->slug)) {
                $layout->slug = Str::slug($layout->nama_layout) . '-' . time();
            }
            if (empty($layout->version)) {
                $layout->version = 1;
            }
            if (empty($layout->is_active)) {
                $layout->is_active = true;
            }
            if (empty($layout->last_edited_at)) {
                $layout->last_edited_at = now();
            }
        });

        // Update timestamps when updating
        static::updating(function ($layout) {
            $layout->last_edited_at = now();
        });

        // Delete related data when deleting
        static::deleting(function ($layout) {
            // Delete background image
            if ($layout->hasBackground()) {
                \Storage::disk('public')->delete($layout->image_path);
            }
        });
    }
}