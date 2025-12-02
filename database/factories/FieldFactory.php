<?php

namespace Database\Factories;

use App\Models\ContentType;
use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FieldFactory extends Factory
{
    protected $model = Field::class;

    public function definition(): array
    {
        $label = $this->faker->unique()->sentence(2);

        return [
            'content_type_id' => ContentType::factory(),
            'label' => $label,
            'name' => Str::snake(Str::lower($label)),
            'type' => $this->faker->randomElement(['text', 'textarea', 'image', 'number']),
            'validation' => null,
            'options' => null,
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
