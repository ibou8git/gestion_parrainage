<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DGEController extends Controller
{
    public function dashboard()
    {
        return view('dge.dashboard');
    }

    public function listeElecteurs()
    {
        return view('dge.electeurs');
    }

    public function listeCandidats()
    {
        return view('dge.candidats');
    }
}
