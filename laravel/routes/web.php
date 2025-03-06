<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DGEController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\ElecteurController;
use App\Http\Controllers\AuthController;

Route::prefix('dge')->group(function () {
    Route::get('/', [DGEController::class, 'dashboard'])->name('dge.dashboard');
    
    // 📌 Routes pour la gestion des électeurs
    Route::get('/electeurs', [DGEController::class, 'listeElecteurs'])->name('dge.electeurs');

    // 📌 Routes pour la gestion des candidats
    Route::get('/candidats', [DGEController::class, 'listeCandidats'])->name('dge.candidats');
    
    // 📌 Route pour l'importation des électeurs
    Route::post('/import-electeurs', [DGEController::class, 'ControlerFichierElecteurs'])->name('import.electeurs');
    
    // 📌 Routes RESTful pour la gestion des candidats
    Route::resource('/candidats', CandidatController::class);
    
    // 📌 Routes RESTful pour la gestion des électeurs (si tu en as un)
    Route::resource('/electeurs', ElecteurController::class);
});

Route::post('/dge/valider-importation', [DGEController::class, 'validerImportation'])->name('valider.importation');

// 📌 Test layout
Route::get('/test-layout', function () {
    return view('layouts.app');
});

// 📌 Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes pour l'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Routes pour l'inscription
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// 📌 Route pour afficher le formulaire d'importation des électeurs (protégée par auth)
Route::middleware('auth')->group(function () {
    Route::get('/dge/import', function () {
        return view('dge.import');
    })->name('dge.importForm');
});