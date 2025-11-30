<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Models\SettingsGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        $displayName = $this->faker->unique()->word;

        return [
            'settings_group_id' => SettingsGroup::factory(),
            'key' => Str::snake($displayName),
            'display_name' => $displayName,
            'value' => $this->faker->word,
            'type' => 'text',
            'options' => null,
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
