<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobdesk_id', 'field_name', 'field_label', 'field_type',
        'options', 'is_required', 'sort_order', 'default_value',
        'placeholder', 'help_text'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function jobdesk()
    {
        return $this->belongsTo(Jobdesk::class);
    }

    public function getOptionsArrayAttribute()
    {
        if (is_array($this->options)) {
            return $this->options;
        }
        return json_decode($this->options, true) ?? [];
    }
}