<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistResult extends Model
{
    use HasFactory;

    protected $table = 'checklist_results';

    protected $fillable = [
        'jobdesk_id', 'lokasi_id', 'inventaris_id', 'user_id',
        'tanggal_check', 'waktu_check', 'petugas_check',
        'catatan', 'status', 'values'
    ];

    protected $casts = [
        'tanggal_check' => 'date',
        'values' => 'array',
    ];

    public function jobdesk()
    {
        return $this->belongsTo(Jobdesk::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getField($key, $default = null)
    {
        return $this->values[$key] ?? $default;
    }
}