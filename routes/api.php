<?php

use App\Http\Controllers\EntryController;
use Illuminate\Support\Facades\Route;

Route::prefix('entries/{contentTypeSlug}')->group(function () {
    Route::get('/', [EntryController::class, 'index']);
    Route::post('/', [EntryController::class, 'store']);
    Route::get('/{entry}', [EntryController::class, 'show']);
    Route::put('/{entry}', [EntryController::class, 'update']);
    Route::delete('/{entry}', [EntryController::class, 'destroy']);
});
