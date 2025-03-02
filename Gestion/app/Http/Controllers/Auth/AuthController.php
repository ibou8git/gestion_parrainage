<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $electeur = Auth::user();
            return response()->json([
                'message' => 'Connexion réussie',
                'electeur' => $electeur
            ], 200);
        }

        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }
}
