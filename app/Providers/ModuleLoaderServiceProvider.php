<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use App\Modules\Module;

class ModuleLoaderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->instance('modules', collect());
        $this->loadModules();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modules = $this->app->make('modules');
        $modules->each(function (Module $module) {
            $this->app->register($module);
        });
    }

    private function loadModules()
    {
        $modulePath = app_path('modules');

        if (!File::isDirectory($modulePath)) {
            return;
        }

        $modules = collect(File::directories($modulePath))
            ->map(function ($moduleDir) {
                $moduleName = class_basename($moduleDir);
                $providerClass = "App\\Modules\\{$moduleName}\\{$moduleName}ServiceProvider";

                if (class_exists($providerClass)) {
                    $providerInstance = $this->app->resolveProvider($providerClass);
                    if ($providerInstance instanceof Module) {
                        return $providerInstance;
                    }
                }
                return null;
            })->filter();

        $this->app->instance('modules', $modules);
    }
}
