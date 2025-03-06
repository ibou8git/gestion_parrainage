<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ElecteurController extends Controller
{
    public function showRegistrationForm()
    {
        return view('electeurs.inscription');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_carte_electeur' => 'required|digits:10',
            'numero_carte_identite' => 'required|digits:13',
            'nom' => 'required|string|max:255',
            'numero_bureau_vote' => 'required|digits_between:1,10',
            'telephone' => 'required|unique:electeurs|digits:9',
            'email' => 'required|email|unique:electeurs',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // On stocke les données temporairement
        session(['electeur_data' => $request->all()]);

        // Rediriger vers la vérification par code
        return redirect()->route('electeur.verification');
    }
}
