<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use App\Http\Controllers\DGEController;

Route::get('/electeurs', [DGEController::class, 'listeElecteurs']);
Route::get('/candidats', [DGEController::class, 'listeCandidats']);
Route::post('/parrainage', [DGEController::class, 'ajouterParrainage']);
Route::get('/parrainage/{id}', [DGEController::class, 'verifierParrainage']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
