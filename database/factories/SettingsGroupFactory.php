<?php

namespace Database\Factories;

use App\Models\SettingsGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SettingsGroupFactory extends Factory
{
    protected $model = SettingsGroup::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
