<?php

namespace App\Http\Controllers;

use App\Models\Parrainage;
use Illuminate\Http\Request;

class ParrainageController extends Controller
{
    public function enregistrerParrainage(Request $request) {
        $request->validate([
            'electeur_id' => 'required|exists:utilisateurs,id',
            'candidat_id' => 'required|exists:utilisateurs,id',
        ]);

        $parrainage = Parrainage::create([
            'electeur_id' => $request->electeur_id,
            'candidat_id' => $request->candidat_id,
            'date_parrainage' => now(),
            'statut' => 'EN_ATTENTE'
        ]);

        return response()->json(['message' => 'Parrainage enregistré'], 201);
    }

    public function validerParrainage($id) {
        $parrainage = Parrainage::findOrFail($id);
        $parrainage->update(['statut' => 'VALIDE']);
        return response()->json(['message' => 'Parrainage validé']);
    }

    public function suivreParrainages() {
        return response()->json(Parrainage::with('electeur', 'candidat')->get());
    }
}
