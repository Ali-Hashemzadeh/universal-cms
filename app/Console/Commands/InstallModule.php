<?php

namespace App\Console\Commands;

use App\Models\ContentType;
use App\Models\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;

class InstallModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a module by creating content types, fields, and permissions from its module.json file.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $moduleName = $this->argument('module');
        $modulePath = app_path('modules/' . $moduleName);
        $jsonPath = $modulePath . '/module.json';

        if (!File::isDirectory($modulePath)) {
            $this->error("Module '{$moduleName}' not found.");
            return;
        }

        if (!File::exists($jsonPath)) {
            $this->error("module.json not found for module '{$moduleName}'.");
            return;
        }

        $config = json_decode(File::get($jsonPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Error parsing module.json: " . json_last_error_msg());
            return;
        }

        $this->info("Installing module: {$moduleName}");

        $this->processPermissions($config['permissions'] ?? []);
        $this->processContentTypes($config['content_types'] ?? []);

        $this->info("Module '{$moduleName}' installed successfully.");
    }

    private function processPermissions(array $permissions): void
    {
        if (empty($permissions)) {
            $this->warn('No permissions to install.');
            return;
        }

        $this->line('Installing permissions...');
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->info("  - Created or verified permission: {$permission}");
        }
    }

    private function processContentTypes(array $contentTypes): void
    {
        if (empty($contentTypes)) {
            $this->warn('No content types to install.');
            return;
        }

        $this->line('Installing content types and fields...');
        foreach ($contentTypes as $ctData) {
            $contentType = ContentType::updateOrCreate(
                ['slug' => $ctData['slug']],
                [
                    'name' => $ctData['name'],
                    'icon' => $ctData['icon'] ?? null,
                ]
            );
            $this->info("  - Created or updated content type: {$contentType->name}");

            $this->processFields($contentType, $ctData['fields'] ?? []);
        }
    }

    private function processFields(ContentType $contentType, array $fields): void
    {
        if (empty($fields)) {
            return;
        }

        foreach ($fields as $fieldData) {
            Field::updateOrCreate(
                [
                    'content_type_id' => $contentType->id,
                    'name' => $fieldData['name'],
                ],
                [
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'validation' => $fieldData['validation'] ?? null,
                    'options' => $fieldData['options'] ?? null,
                    'order' => $fieldData['order'] ?? 0,
                ]
            );
            $this->info("    - Synced field: {$fieldData['label']}");
        }
    }
}
