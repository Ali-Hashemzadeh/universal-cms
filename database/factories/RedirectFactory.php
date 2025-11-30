<?php

namespace Database\Factories;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Factories\Factory;

class RedirectFactory extends Factory
{
    protected $model = Redirect::class;

    public function definition(): array
    {
        return [
            'source_url' => '/' . $this->faker->slug,
            'destination_url' => '/' . $this->faker->slug,
            'status_code' => 301,
        ];
    }
}
