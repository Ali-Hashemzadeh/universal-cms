<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoMetadataFactory extends Factory
{
    protected $model = SeoMetadata::class;

    public function definition(): array
    {
        return [
            'metadatable_id' => Entry::factory(),
            'metadatable_type' => Entry::class,
            'meta_title' => $this->faker->sentence,
            'meta_description' => $this->faker->paragraph,
        ];
    }
}
