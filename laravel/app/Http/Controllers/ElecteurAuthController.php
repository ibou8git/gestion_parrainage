<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElecteurAuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion.
     */
    public function showLoginForm()
    {
        return view('electeurs.login');
    }

    /**
     * Traiter la connexion.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('electeur')->attempt($credentials)) {
            return redirect()->intended('/electeurs/dashboard');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    /**
     * Déconnexion.
     */
    public function logout()
    {
        Auth::guard('electeur')->logout();
        return redirect('/electeurs/login');
    }
}