<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'aksi', 'modul', 'deskripsi',
        'data_old', 'data_new', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'data_old' => 'array',
        'data_new' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    public function getAksiBadgeAttribute()
    {
        $badges = [
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger',
            'login' => 'info',
            'logout' => 'secondary',
        ];
        return $badges[$this->aksi] ?? 'primary';
    }
}