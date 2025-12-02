# Laravel Modular CMS

This is a Laravel 11 application with a modular architecture.

## Module Discovery

Module discovery is handled automatically by the `ModuleLoaderServiceProvider`. It scans the `app/modules` directory for any subdirectories that contain a service provider class that extends `App\Modules\Module`. The naming convention for the service provider is `{ModuleName}ServiceProvider.php`.

For a module to be discovered, it must be located in `app/modules/{ModuleName}` and contain a `app/modules/{ModuleName}/{ModuleName}ServiceProvider.php` file.

## Creating a New Module

To create a new module, follow these steps:

1.  Create a new directory for your module in `app/modules`. For example, `app/modules/MyModule`.
2.  Create a service provider for your module that extends `App\Modules\Module`. The service provider must be named `{ModuleName}ServiceProvider.php`. For example, `app/modules/MyModule/MyModuleServiceProvider.php`.
3.  Implement the abstract methods in your service provider: `getName()`, `getDescription()`, and `getVersion()`.
4.  Add any routes, views, or other resources to your module's directory.
5.  Your module will be automatically discovered and registered by the application.
