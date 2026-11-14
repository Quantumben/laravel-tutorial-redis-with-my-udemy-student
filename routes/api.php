<?php

use App\Http\Controllers\Api\TodoController;
use App\Models\Todo;
use Illuminate\Support\Facades\Route;

Route::apiResource('todos', TodoController::class);

// Development-only benchmark endpoint
if (app()->environment('local')) {
    Route::get('todos-uncached', function () {
        return response()->json([
            'success' => true,
            'data' => Todo::latest()->get(),
        ]);
    });
}