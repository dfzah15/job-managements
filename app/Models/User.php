<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'no_telepon', 'foto', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============================================
    // ROLE CHECK
    // ============================================
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManajer()
    {
        return $this->role === 'manajer';
    }

    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    // ============================================
    // JOBDESK RELATIONS (TAMBAHKAN INI)
    // ============================================
    public function jobdesks()
    {
        return $this->belongsToMany(Jobdesk::class, 'user_jobdesks')
                    ->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete', 'can_export', 'can_approve']);
    }

    public function hasJobdesk($slug)
    {
        return $this->jobdesks()->where('slug', $slug)->exists();
    }

    public function hasAnyJobdesk()
    {
        return $this->jobdesks()->exists();
    }

    public function canAccessJobdesk($slug, $permission = 'view')
    {
        $jobdesk = $this->jobdesks()->where('slug', $slug)->first();
        if (!$jobdesk) return false;
        
        $column = 'can_' . $permission;
        return $jobdesk->pivot->$column ?? false;
    }

    public function getActiveJobdesks()
    {
        return $this->jobdesks()->where('is_active', true)->get();
    }

    public function getSidebarJobdesks()
    {
        // Jika admin, tampilkan semua jobdesk
        if ($this->isAdmin()) {
            return Jobdesk::where('is_active', true)->get();
        }
        // Jika bukan admin, tampilkan jobdesk yang dimiliki user
        return $this->getActiveJobdesks();
    }

    // ... relasi lainnya ...
    public function laporanEksekusi()
    {
        return $this->hasMany(LaporanEksekusi::class);
    }

    public function checklists()
    {
        return $this->hasMany(ChecklistCctv::class);
    }

    public function laporanAktivitas()
    {
        return $this->hasMany(LaporanAktivitas::class);
    }

    public function gambar()
    {
        return $this->morphMany(Gambar::class, 'gambarable');
    }
}