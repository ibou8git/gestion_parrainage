<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidat;

class CandidatController extends Controller
{
    //  Afficher le formulaire d'enregistrement
    public function create()
{
    return view('candidats.create'); // Affiche le formulaire sans vérifier la session
}
    public function check(Request $request)
{
    // Valider le numéro de la carte électorale
    $request->validate([
        'numero_carte' => 'required|string|exists:candidats,numero_carte', // Vérifie si le numéro existe dans la base de données
    ]);

    // Chercher le candidat par le numéro de carte
    $candidate = Candidat::where('numero_carte', $request->numero_carte)->first();

    // Si le candidat est trouvé, stocker ses données dans la session
    if ($candidate) {
        session(['candidate_data' => $candidate]);
        return redirect()->route('candidats.create'); // Redirige vers le formulaire de création
    } else {
        return back()->withErrors(['numero_carte' => 'Le candidat n\'est pas présent dans le fichier électoral.']);
    }
}
public function deleteCandidat($id)
{
    $candidat = Candidat::findOrFail($id);
    $candidat->delete();

    // Réinitialise les données de la session après suppression
    session()->forget('candidate_data');

    return redirect()->route('candidats.check'); // Redirige vers le formulaire de saisie du numéro de carte
}

    //  Enregistrer un candidat
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'numero_carte' => 'required|string|unique:candidats,numero_carte',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'date_naissance' => 'required|date',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'parti_politique' => 'nullable|string|max:100',
            'slogan' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'couleur_1' => 'nullable|string|max:7',
            'couleur_2' => 'nullable|string|max:7',
            'couleur_3' => 'nullable|string|max:7',
            'url_infos' => 'nullable|url|max:255',
        ]);

        // Stockage de la photo (si fournie)
        $photoPath = $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null;

        // Enregistrement du candidat
        $candidat = Candidat::create([
            'numero_carte' => $request->numero_carte,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'parti_politique' => $request->parti_politique,
            'slogan' => $request->slogan,
            'photo' => $photoPath,
            'couleur_1' => $request->couleur_1,
            'couleur_2' => $request->couleur_2,
            'couleur_3' => $request->couleur_3,
            'url_infos' => $request->url_infos,
            'code_securite' => rand(100000, 999999) // Générer un code de sécurité
        ]);


        return redirect()->route('candidats.index')->with('success', 'Candidat enregistré avec succès !');
    }

    // Afficher la liste des candidats
    public function index()
    {
        $candidats = Candidat::all();
        return view('candidats.index', compact('candidats'));
    }

    //  Afficher le détail d’un candidat
    public function show($id)
    {
        $candidat = Candidat::findOrFail($id);
        return view('candidats.show', compact('candidat'));
    }
}
