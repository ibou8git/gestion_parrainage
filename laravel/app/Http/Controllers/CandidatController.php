<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidat;

class CandidatController extends Controller
{
    /**
     * Afficher la liste des candidats.
     */
    public function index()
    {
        $candidats = Candidat::all();
        return view('dge.candidats.index', compact('candidats'));
    }

    /**
     * Afficher le formulaire d'ajout d'un candidat.
     */
    public function create()
    {
        return view('dge.candidats.create');
    }

    /**
     * Enregistrer un nouveau candidat.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'parti_politique' => 'required|string|max:255',
            'email' => 'required|email|unique:candidats',
            'telephone' => 'required|string|unique:candidats',
            'slogan' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $candidat = new Candidat($request->except('photo'));

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidats');
            $candidat->photo = $photoPath;
        }

        $candidat->save();

        return redirect()->route('candidats.index')->with('success', 'Candidat ajouté avec succès !');
    }

    /**
     * Afficher le formulaire d'édition d'un candidat.
     */
    public function edit(Candidat $candidat)
    {
        return view('dge.candidats.edit', compact('candidat'));
    }

    /**
     * Mettre à jour les informations d'un candidat.
     */
    public function update(Request $request, Candidat $candidat)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'parti_politique' => 'required|string|max:255',
            'email' => 'required|email|unique:candidats,email,' . $candidat->id,
            'telephone' => 'required|string|unique:candidats,telephone,' . $candidat->id,
            'slogan' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $candidat->update($request->except('photo'));

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidats');
            $candidat->photo = $photoPath;
            $candidat->save();
        }

        return redirect()->route('candidats.index')->with('success', 'Candidat mis à jour avec succès !');
    }

    /**
     * Supprimer un candidat.
     */
    public function destroy(Candidat $candidat)
    {
        $candidat->delete();
        return redirect()->route('candidats.index')->with('success', 'Candidat supprimé avec succès.');
    }
}
