<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Candidat;
use App\Models\Parrainage;

class CandidatController extends Controller
{
    // Récupérer la liste des candidats
    public function index()
    {
        return response()->json(Candidat::all());
    }

    // Gérer le parrainage
    public function parrainer(Request $request)
    {
        $request->validate([
            'candidat_id' => 'required|exists:candidats,id',
        ]);

        // Simuler l'envoi du code OTP (tu peux intégrer Twilio, Mailtrap ou un autre service)
        $otp = rand(100000, 999999);
        session(['otp' => $otp]); // Stocker temporairement dans la session

        // Enregistrer le parrainage dans la base de données
        Parrainage::create([
            'candidat_id' => $request->candidat_id,
            $electeurId = Auth::id(),// Récupérer l'ID de l'électeur connecté
            'otp' => $otp,
        ]);
        

        return response()->json(['success' => true, 'message' => 'Code OTP envoyé !']);
    }

    // Vérifier le code OTP
    public function verifierOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if ($request->otp == session('otp')) {
            session()->forget('otp'); // Supprimer l'OTP après validation
            return response()->json(['success' => true, 'message' => 'Parrainage confirmé !']);
        }

        return response()->json(['success' => false, 'message' => 'Code OTP incorrect.']);
    }
}
