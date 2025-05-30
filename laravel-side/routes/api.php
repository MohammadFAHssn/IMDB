<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(MovieController::class)->group(function () {
    Route::get('movie/getAll', 'getAll');
    Route::post('movie/syncWithIMDB', 'syncWithIMDB');
});
