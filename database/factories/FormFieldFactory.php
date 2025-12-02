<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FormFieldFactory extends Factory
{
    protected $model = FormField::class;

    public function definition(): array
    {
        $label = $this->faker->unique()->sentence(2);

        return [
            'form_id' => Form::factory(),
            'label' => $label,
            'name' => Str::snake(Str::lower($label)),
            'type' => $this->faker->randomElement(['text', 'textarea', 'email', 'select']),
            'validation' => null,
            'options' => null,
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
