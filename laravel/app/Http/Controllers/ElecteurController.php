<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Electeur;

class ElecteurController extends Controller
{
    /**
     * Afficher la liste des électeurs.
     */
    public function index()
    {
        $electeurs = Electeur::all();
        return view('dge.electeurs.index', compact('electeurs'));
    }

    /**
     * Afficher le formulaire d'ajout d'un électeur.
     */
    public function create()
    {
        return view('dge.electeurs.create');
    }

    /**
     * Enregistrer un nouvel électeur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'carte_identite' => 'required|string|size:12|unique:electeurs',
            'num_electeur' => 'required|string|size:10|unique:electeurs',
            'email' => 'required|email|unique:electeurs',
            'telephone' => 'required|string|unique:electeurs',
            'bureau_vote' => 'required|string|max:255',
        ]);

        Electeur::create($request->all());

        return redirect()->route('dge.electeurs.index')->with('success', 'Électeur ajouté avec succès !');
    }

    /**
     * Afficher le formulaire d'édition d'un électeur.
     */
    public function edit(Electeur $electeur)
    {
        return view('dge.electeurs.edit', compact('electeur'));
    }

    /**
     * Mettre à jour un électeur.
     */
    public function update(Request $request, Electeur $electeur)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'carte_identite' => 'required|string|size:12|unique:electeurs,carte_identite,' . $electeur->id,
            'num_electeur' => 'required|string|size:10|unique:electeurs,num_electeur,' . $electeur->id,
            'email' => 'required|email|unique:electeurs,email,' . $electeur->id,
            'telephone' => 'required|string|unique:electeurs,telephone,' . $electeur->id,
            'bureau_vote' => 'required|string|max:255',
        ]);

        $electeur->update($request->all());

        return redirect()->route('dge.electeurs.index')->with('success', 'Électeur mis à jour avec succès !');
    }

    /**
     * Supprimer un électeur.
     */
    public function destroy(Electeur $electeur)
    {
        $electeur->delete();
        return redirect()->route('dge.electeurs.index')->with('success', 'Électeur supprimé avec succès.');
    }
}
