<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

abstract class Module extends ServiceProvider
{
    abstract public function getName(): string;
    abstract public function getDescription(): string;
    abstract public function getVersion(): string;

    public function boot(): void
    {
        // Boot module services
    }

    public function register(): void
    {
        // Register module services
    }
}
