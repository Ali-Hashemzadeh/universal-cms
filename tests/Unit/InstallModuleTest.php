<?php

namespace Tests\Unit;

use App\Models\ContentType;
use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class InstallModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_module_installer_command(): void
    {
        $moduleName = 'TestModule';
        $modulePath = app_path('modules/' . $moduleName);
        $jsonPath = $modulePath . '/module.json';

        // Mock the module directory and module.json file
        File::shouldReceive('isDirectory')->with($modulePath)->andReturn(true);
        File::shouldReceive('exists')->with($jsonPath)->andReturn(true);
        File::shouldReceive('get')->with($jsonPath)->andReturn(json_encode([
            'permissions' => ['manage_test_module'],
            'content_types' => [
                [
                    'name' => 'Test Type',
                    'slug' => 'test-type',
                    'icon' => 'test-icon',
                    'fields' => [
                        [
                            'label' => 'Test Field',
                            'name' => 'test_field',
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ]));

        $this->artisan('module:install', ['module' => $moduleName])
            ->expectsOutput("Installing module: {$moduleName}")
            ->expectsOutput('  - Created or verified permission: manage_test_module')
            ->expectsOutput('  - Created or updated content type: Test Type')
            ->expectsOutput('    - Synced field: Test Field')
            ->expectsOutput("Module '{$moduleName}' installed successfully.")
            ->assertExitCode(0);

        $this->assertDatabaseHas('permissions', ['name' => 'manage_test_module']);
        $this->assertDatabaseHas('content_types', ['slug' => 'test-type']);
        $this->assertDatabaseHas('fields', ['name' => 'test_field']);
    }
}
