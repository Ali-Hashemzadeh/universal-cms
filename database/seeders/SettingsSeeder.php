<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingsGroup;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generalGroup = SettingsGroup::create([
            'name' => 'General',
            'slug' => 'general',
            'order' => 1,
        ]);

        Setting::create([
            'settings_group_id' => $generalGroup->id,
            'key' => 'site_name',
            'display_name' => 'Site Name',
            'value' => 'Laravel CMS',
            'type' => 'text',
            'order' => 1,
        ]);

        Setting::create([
            'settings_group_id' => $generalGroup->id,
            'key' => 'site_tagline',
            'display_name' => 'Site Tagline',
            'value' => 'A powerful and flexible CMS.',
            'type' => 'text',
            'order' => 2,
        ]);
    }
}
