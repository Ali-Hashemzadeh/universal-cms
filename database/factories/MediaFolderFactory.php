<?php

namespace Database\Factories;

use App\Models\MediaFolder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MediaFolderFactory extends Factory
{
    protected $model = MediaFolder::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word;

        return [
            'parent_id' => null,
            'name' => $name,
            'path' => Str::slug($name),
        ];
    }
}
