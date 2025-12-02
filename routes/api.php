<?php

use App\Http\Controllers\EntryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('entries/{contentTypeSlug}', EntryController::class)->except(['create', 'edit']);
