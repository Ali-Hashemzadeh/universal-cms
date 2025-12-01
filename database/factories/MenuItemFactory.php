<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'parent_id' => null,
            'title' => $this->faker->word,
            'url' => $this->faker->url,
            'target' => '_self',
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
