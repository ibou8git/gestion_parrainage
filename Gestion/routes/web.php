<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElecteurController;

// Affichage du formulaire d'inscription
Route::get('/electeurs/inscription', function () {
    return view('electeurs.inscription');
})->name('electeurs.inscription.form');

// Traitement du formulaire d'inscription
Route::post('/electeurs/inscription', [ElecteurController::class, 'inscription'])->name('electeurs.inscription');
