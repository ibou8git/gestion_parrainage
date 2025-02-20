<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function show($id)
    {
        // Simuler des données (remplacez par un modèle si vous avez une base de données)
        $candidats = [
            1 => [
                'id'       => 1,
                'nom'      => 'Candidat A',
                'slogan'   => 'Pour un avenir meilleur',
                'photo'    => '/images/candidatA.jpg',
                'couleur'  => 'Bleu',
                'programme'=> 'Programme du parti A: ...'
            ],
            2 => [
                'id'       => 2,
                'nom'      => 'Candidat B',
                'slogan'   => 'Ensemble pour le changement',
                'photo'    => '/images/candidatB.jpg',
                'couleur'  => 'Rouge',
                'programme'=> 'Programme du parti B: ...'
            ]
        ];

        // Vérifier si le candidat existe
        if (!isset($candidats[$id])) {
            abort(404, 'Candidat non trouvé');
        }

        return view('electeurs.candidat_details', ['candidat' => $candidats[$id]]);
    }
}
