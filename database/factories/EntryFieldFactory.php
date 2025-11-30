<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\EntryField;
use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFieldFactory extends Factory
{
    protected $model = EntryField::class;

    public function definition(): array
    {
        return [
            'entry_id' => Entry::factory(),
            'field_id' => Field::factory(),
            'value' => $this->faker->sentence,
        ];
    }
}
