<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\DGEController;

Route::get('/dge', [DGEController::class, 'dashboard'])->name('dge.dashboard');
Route::get('/dge/electeurs', [DGEController::class, 'listeElecteurs'])->name('dge.electeurs');
Route::get('/dge/candidats', [DGEController::class, 'listeCandidats'])->name('dge.candidats');



Route::get('/', function () {
    return view('welcome');
});
