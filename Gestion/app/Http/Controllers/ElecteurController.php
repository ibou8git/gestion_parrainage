<?php

namespace App\Http\Controllers;

use App\Models\Electeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ElecteurController extends Controller
{
    /**
     * Enregistrement d'un électeur et envoi du code de vérification
     */
    public function inscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_carte_electeur' => 'required|digits:10|unique:electeurs,numero_carte_electeur',
            'numero_carte_identite' => 'required|digits:13|unique:electeurs,numero_carte_identite',
            'nom' => 'required|string|max:255',
            'numero_bureau_vote' => 'required|digits_between:1,10',
            'telephone' => 'required|digits:9|unique:electeurs,telephone',
            'email' => 'required|email|unique:electeurs,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Générer un code de vérification à 6 chiffres
        $codeVerification = rand(100000, 999999);

        // Enregistrer l'électeur en base avec statut non vérifié
        $electeur = Electeur::create([
            'numero_carte_electeur' => $request->numero_carte_electeur,
            'numero_carte_identite' => $request->numero_carte_identite,
            'nom' => $request->nom,
            'numero_bureau_vote' => $request->numero_bureau_vote,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'code_verification' => $codeVerification, // Stocker le code en base
            'est_verifie' => false
        ]);

        // Simuler l'envoi du code par email
        Mail::raw("Votre code de vérification est : $codeVerification", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Code de vérification');
        });

        return response()->json([
            'message' => 'Un code de vérification a été envoyé à votre email.',
            'electeur_id' => $electeur->id
        ], 200);
    }

    /**
     * Vérification du code et activation du compte
     */
    public function verification(Request $request)
    {
        $request->validate([
            'electeur_id' => 'required|exists:electeurs,id',
            'code' => 'required|string'
        ]);

        // Vérifier l'électeur et le code
        $electeur = Electeur::where('id', $request->electeur_id)
            ->where('code_verification', $request->code)
            ->first();

        if (!$electeur) {
            return response()->json(['error' => 'Code invalide ou électeur introuvable.'], 400);
        }

        // Mettre à jour l'état du compte
        $electeur->update(['est_verifie' => true]);

        return response()->json([
            'message' => 'Compte activé avec succès !',
            'electeur' => $electeur
        ], 200);
    }
}
