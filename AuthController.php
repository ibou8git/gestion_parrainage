<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:utilisateurs',
            'mot_de_passe' => 'required|min:6',
            'type_utilisateur' => 'required|in:ADMINISTRATEUR,ELECTEUR,CANDIDAT'
        ]);

        $user = Utilisateur::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'type_utilisateur' => $request->type_utilisateur
        ]);

        return response()->json(['message' => 'Utilisateur enregistré avec succès'], 201);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'mot_de_passe');

        if (Auth::attempt($credentials)) {
            return response()->json(['token' => Auth::user()->createToken('API Token')->plainTextToken]);
        }

        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }
}

