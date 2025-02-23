<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElecteurController;

// Affichage du formulaire d'inscription
Route::get('/electeurs/inscription', function () {
    return view('electeurs.inscription');
})->name('electeurs.inscription.form');

// Traitement du formulaire d'inscription
Route::post('/electeurs/inscription', [ElecteurController::class, 'inscription'])->name('electeurs.inscription');

// Affichage de la page de contact
Route::get('/electeurs/contact', function () {
    return view('electeurs.contact');
})->name('electeurs.contact.form');

// Traitement des informations de contact
Route::post('/electeurs/contact', [ElecteurController::class, 'contact'])->name('electeurs.contact');

// Affichage de la page de vérification
Route::get('/electeurs/verification', function () {
    return view('electeurs.verification');
})->name('electeurs.verification.form');

// Traitement de la vérification du code
Route::post('/electeurs/verification', [ElecteurController::class, 'verification'])->name('electeurs.verification');

// Route pour renvoyer le code
Route::post('/electeurs/renvoyer_code', [ElecteurController::class, 'renvoyerCode'])->name('electeurs.renvoyer_code');

Route::get('/electeurs/parrainage', function () {
    return view('electeurs.parrainage');
})->name('electeurs.parrainage.form');

use App\Http\Controllers\CandidateController;

Route::get('/electeurs/candidat/{id}', [CandidateController::class, 'show'])->name('electeurs.candidat.details');


Route::get('/electeurs/confirmer_parrainage', function () {
    return view('electeurs.confirmer_parrainage');
})->name('electeurs.confirmer_parrainage.form');

Route::post('/electeurs/confirmer_parrainage', [\App\Http\Controllers\ParrainagesController::class, 'confirmerParrainage'])->name('electeurs.confirmer_parrainage');
