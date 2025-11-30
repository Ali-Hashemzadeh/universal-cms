<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'settings_group_id',
        'key',
        'display_name',
        'value',
        'type',
        'options',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(SettingsGroup::class, 'settings_group_id');
    }
}
