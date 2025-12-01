<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relation extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_id',
        'related_entry_id',
        'type',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function relatedEntry(): BelongsTo
    {
        return $this->belongsTo(Entry::class, 'related_entry_id');
    }
}
