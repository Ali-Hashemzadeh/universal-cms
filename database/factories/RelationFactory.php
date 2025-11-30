<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\Relation;
use Illuminate\Database\Eloquent\Factories\Factory;

class RelationFactory extends Factory
{
    protected $model = Relation::class;

    public function definition(): array
    {
        return [
            'entry_id' => Entry::factory(),
            'related_entry_id' => Entry::factory(),
            'type' => 'related',
        ];
    }
}
