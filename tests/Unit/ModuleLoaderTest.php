<?php

namespace Tests\Unit;

use App\Modules\SampleModule\SampleModuleServiceProvider;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ModuleLoaderTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_module_loader_discovers_modules(): void
    {
        $modules = $this->app->make('modules');

        $this->assertInstanceOf(Collection::class, $modules);
        $this->assertCount(1, $modules);
        $this->assertInstanceOf(SampleModuleServiceProvider::class, $modules->first());
    }
}
