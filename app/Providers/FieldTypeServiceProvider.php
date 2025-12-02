<?php

namespace App\Providers;

use App\Services\FieldTypeService;
use Illuminate\Support\ServiceProvider;

class FieldTypeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(FieldTypeService::class, function ($app) {
            return new FieldTypeService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $fieldTypeService = $this->app->make(FieldTypeService::class);

        $fieldTypeService->register('text', ['validation_rules' => ['string', 'max:255']]);
        $fieldTypeService->register('textarea', ['validation_rules' => ['string']]);
        $fieldTypeService->register('number', ['validation_rules' => ['numeric']]);
        $fieldTypeService->register('boolean', [
            'validation_rules' => ['boolean'],
            'serialize' => fn ($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'normalize' => fn ($value) => (bool) $value,
        ]);
        $fieldTypeService->register('date', ['validation_rules' => ['date']]);
        $fieldTypeService->register('datetime', ['validation_rules' => ['datetime']]);
        $fieldTypeService->register('image', ['validation_rules' => ['integer', 'exists:media_files,id']]);
        $fieldTypeService->register('file', ['validation_rules' => ['integer', 'exists:media_files,id']]);
        $fieldTypeService->register('json', [
            'validation_rules' => ['json'],
            'serialize' => fn ($value) => is_array($value) ? json_encode($value) : $value,
            'normalize' => fn ($value) => is_string($value) ? json_decode($value, true) : $value,
        ]);
        $fieldTypeService->register('richtext', ['validation_rules' => ['string']]);
        $fieldTypeService->register('select', ['validation_rules' => ['string']]);
        $fieldTypeService->register('relation', ['validation_rules' => ['integer', 'exists:entries,id']]);
        $fieldTypeService->register('repeater', [
            'validation_rules' => ['array'],
            'serialize' => fn ($value) => is_array($value) ? json_encode($value) : $value,
            'normalize' => fn ($value) => is_string($value) ? json_decode($value, true) : $value,
        ]);
        $fieldTypeService->register('gallery', [
            'validation_rules' => ['array'],
            'serialize' => fn ($value) => is_array($value) ? json_encode($value) : $value,
            'normalize' => fn ($value) => is_string($value) ? json_decode($value, true) : $value,
        ]);
    }
}
