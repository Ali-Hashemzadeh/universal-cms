<?php

namespace Database\Factories;

use App\Models\ContentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContentTypeFactory extends Factory
{
    protected $model = ContentType::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => $this->faker->word,
        ];
    }
}
