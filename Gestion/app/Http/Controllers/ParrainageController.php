<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parrainage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ParrainageController extends Controller
{
    public function parrainer(Request $request)
    {
        // Vérifier si l'électeur est authentifié
        $electeur = auth()->guard('web')->user();

        if (!$electeur) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        // Vérifier si l'utilisateur est bien un électeur
        if ($electeur->type_utilisateur !== 'ELECTEUR') {
            return response()->json(['error' => 'Seuls les électeurs peuvent parrainer'], 403);
        }

        // Valider la requête
        $validator = Validator::make($request->all(), [
            'candidat_id' => 'required|exists:candidats,id', // Vérifie que le candidat existe bien en base
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Vérifier si l'électeur a déjà parrainé
        if (Parrainage::where('electeur_id', $electeur->id)->exists()) {
            return response()->json(['error' => 'Vous avez déjà parrainé un candidat'], 400);
        }

        // Enregistrer le parrainage
        try {
            $parrainage = Parrainage::create([
                'date_parrainage' => Carbon::now(),
                'electeur_id' => $electeur->id,
                'candidat_id' => $request->candidat_id,
                'statut' => 'EN_ATTENTE',
            ]);

            return response()->json(['success' => true, 'message' => 'Parrainage enregistré avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du parrainage'], 500);
        }
    }

    public function confirmerParrainage(Request $request)
{
    // Vérifier si l'utilisateur est authentifié
    $electeur = auth()->guard('web')->user(); 

    if (!$electeur) {
        return response()->json(['error' => 'Utilisateur non authentifié'], 401);
    }

    // Valider la requête
    $validator = Validator::make($request->all(), [
        'code_validation' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // Vérifier si le code de validation est correct
    $parrainage = Parrainage::where('electeur_id', $electeur->id)
        ->where('code_validation', $request->code_validation)
        ->where('statut', 'EN_ATTENTE')
        ->first();

    if (!$parrainage) {
        return response()->json(['error' => 'Code de validation incorrect ou parrainage déjà confirmé'], 400);
    }

    // Mettre à jour le statut du parrainage
    $parrainage->statut = 'CONFIRME';
    $parrainage->save();

    return response()->json(['success' => true, 'message' => 'Parrainage confirmé avec succès']);

}public function store(Request $request)
{
    $request->validate([
        'candidat_id' => 'required|integer|exists:candidats,id'
    ]);

    // Logique pour envoyer le code OTP à l'utilisateur...
    
    return response()->json([
        'success' => true,
        'message' => 'Un code à usage unique a été envoyé sur votre email et téléphone.'
    ]);
}




}
