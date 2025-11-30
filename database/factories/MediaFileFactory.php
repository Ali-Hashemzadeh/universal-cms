<?php

namespace Database\Factories;

use App\Models\MediaFile;
use App\Models\MediaFolder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MediaFileFactory extends Factory
{
    protected $model = MediaFile::class;

    public function definition(): array
    {
        $filename = Str::random(10) . '.jpg';

        return [
            'media_folder_id' => MediaFolder::factory(),
            'name' => $this->faker->word,
            'filename' => $filename,
            'path' => 'uploads/' . $filename,
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(1000, 5000),
            'metadata' => ['width' => 1920, 'height' => 1080],
        ];
    }
}
