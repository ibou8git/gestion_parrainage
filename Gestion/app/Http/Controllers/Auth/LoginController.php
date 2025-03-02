<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Assurez-vous que la vue existe dans resources/views/auth/login.blade.php
    }

    /**
     * Gère l'authentification de l'utilisateur.
     */
    public function login(Request $request)
    {
        // Validation des données
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentative de connexion
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->route('dashboard')->with('success', 'Connexion réussie !');
        }

        // En cas d'échec
        return back()->withErrors(['email' => 'Les informations de connexion sont incorrectes.'])->withInput();
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }
}
