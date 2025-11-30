<?php

namespace App\Modules\SampleModule;

use App\Modules\Module;
use Illuminate\Support\Facades\Route;

class SampleModuleServiceProvider extends Module
{
    public function getName(): string
    {
        return 'Sample Module';
    }

    public function getDescription(): string
    {
        return 'A sample module to demonstrate the module loader.';
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    public function register(): void
    {
        //
    }
}
