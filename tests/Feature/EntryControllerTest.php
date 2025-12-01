<?php

namespace Tests\Feature;

use App\Models\ContentType;
use App\Models\Entry;
use App\Models\Field;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private ContentType $contentType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');

        // Grant permissions to the user
        $permissions = ['view_entries', 'create_entries', 'edit_entries', 'delete_entries'];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
            $this->user->givePermissionTo($permission);
        }

        $this->contentType = ContentType::factory()->create(['slug' => 'pages']);
        Field::factory()->create([
            'content_type_id' => $this->contentType->id,
            'label' => 'Page Content',
            'name' => 'page_content',
            'type' => 'textarea',
            'validation' => ['required', 'string'],
        ]);
    }

    public function test_can_create_entry(): void
    {
        $data = [
            'title' => 'New Page',
            'slug' => 'new-page',
            'status' => 'published',
            'page_content' => 'This is the page content.',
        ];

        $this->postJson("/api/entries/{$this->contentType->slug}", $data)
            ->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Page']);

        $this->assertDatabaseHas('entries', ['slug' => 'new-page']);
        $this->assertDatabaseHas('entry_fields', ['value' => 'This is the page content.']);
    }

    public function test_create_entry_fails_validation(): void
    {
        $data = [
            'title' => '', // Invalid
            'slug' => 'invalid-page',
            'page_content' => '', // Invalid
        ];

        $this->postJson("/api/entries/{$this->contentType->slug}", $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'page_content']);
    }

    public function test_can_get_entries_list(): void
    {
        Entry::factory()->count(3)->create(['content_type_id' => $this->contentType->id]);

        $this->getJson("/api/entries/{$this->contentType->slug}")
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_get_single_entry(): void
    {
        $entry = Entry::factory()->create(['content_type_id' => $this->contentType->id]);

        $this->getJson("/api/entries/{$this->contentType->slug}/{$entry->id}")
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $entry->id]);
    }

    public function test_can_update_entry(): void
    {
        $entry = Entry::factory()->create(['content_type_id' => $this->contentType->id]);
        $entry->entryFields()->create(['field_id' => $this->contentType->fields->first()->id, 'value' => 'Old content.']);

        $data = [
            'title' => 'Updated Title',
            'slug' => $entry->slug,
            'page_content' => 'New updated content.',
        ];

        $this->putJson("/api/entries/{$this->contentType->slug}/{$entry->id}", $data)
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('entries', ['title' => 'Updated Title']);
        $this->assertDatabaseHas('entry_fields', ['value' => 'New updated content.']);
    }

    public function test_can_delete_entry(): void
    {
        $entry = Entry::factory()->create(['content_type_id' => $this->contentType->id]);

        $this->deleteJson("/api/entries/{$this->contentType->slug}/{$entry->id}")
            ->assertStatus(204);

        $this->assertSoftDeleted('entries', ['id' => $entry->id]);
    }
}
