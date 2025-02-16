<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParrainageController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/parrainages', [ParrainageController::class, 'enregistrerParrainage']);
    Route::put('/parrainages/{id}/valider', [ParrainageController::class, 'validerParrainage']);
    Route::get('/parrainages', [ParrainageController::class, 'suivreParrainages']);
});
