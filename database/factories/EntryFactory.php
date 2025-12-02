<?php

namespace Database\Factories;

use App\Models\ContentType;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EntryFactory extends Factory
{
    protected $model = Entry::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence;

        return [
            'content_type_id' => ContentType::factory(),
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'status' => 'published',
            'published_at' => now(),
        ];
    }
}
