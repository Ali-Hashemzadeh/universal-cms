<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_type_id',
        'label',
        'name',
        'type',
        'validation',
        'options',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'validation' => 'array',
    ];

    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    public function entryFields(): HasMany
    {
        return $this->hasMany(EntryField::class);
    }
}
