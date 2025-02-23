<?php

use App\Http\Controllers\CandidatController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Route pour le tableau de bord (sans authentification)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Routes pour les candidats
Route::get('/candidats', [CandidatController::class, 'index'])->name('candidats.index'); // Liste des candidats
Route::get('/candidats/create', [CandidatController::class, 'create'])->name('candidats.create'); // Formulaire d'enregistrement
Route::post('/candidats/check', [CandidatController::class, 'check'])->name('candidats.check');
Route::post('/candidats', [CandidatController::class, 'store'])->name('candidats.store'); // Enregistrement du candidat
Route::get('/candidats/{id}', [CandidatController::class, 'show'])->name('candidats.show'); // Détails d'un candidat
