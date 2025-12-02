<?php

use Illuminate\Support\Facades\Route;

Route::get('/api/sample-module', function () {
    return response()->json(['message' => 'Hello from the Sample Module!']);
});
