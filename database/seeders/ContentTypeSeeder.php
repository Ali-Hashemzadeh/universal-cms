<?php

namespace Database\Seeders;

use App\Models\ContentType;
use App\Models\Field;
use Illuminate\Database\Seeder;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pageContentType = ContentType::create([
            'name' => 'Page',
            'slug' => 'page',
            'icon' => 'file-text',
        ]);

        Field::create([
            'content_type_id' => $pageContentType->id,
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'validation' => ['required'],
            'order' => 1,
        ]);

        Field::create([
            'content_type_id' => $pageContentType->id,
            'label' => 'Slug',
            'name' => 'slug',
            'type' => 'slug',
            'validation' => ['required', 'unique:entries,slug'],
            'order' => 2,
        ]);

        Field::create([
            'content_type_id' => $pageContentType->id,
            'label' => 'Content',
            'name' => 'content',
            'type' => 'richtext',
            'order' => 3,
        ]);
    }
}
