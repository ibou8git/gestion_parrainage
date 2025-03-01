<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElecteurController;

// Route API pour l'inscription d'un électeur
Route::post('/electeurs/inscription', [ElecteurController::class, 'inscription']);

// Route API pour la vérification d'un électeur
Route::post('/electeurs/verification', [ElecteurController::class, 'verification']);

// Route API pour obtenir la liste des candidats
Route::get('/candidats', [App\Http\Controllers\CandidateController::class, 'index']);

// Route API pour afficher les détails d'un candidat
Route::get('/candidats/{id}', [App\Http\Controllers\CandidateController::class, 'show']);
