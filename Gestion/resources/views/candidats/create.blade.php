@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Ajouter un candidat</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!session('candidate_data'))
        <!-- Formulaire pour saisir le numéro de carte -->
        <form action="{{ route('candidats.check') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="numero_carte" class="form-label">Numéro de carte électorale</label>
                <input type="text" class="form-control" id="numero_carte" name="numero_carte" required>
            </div>

            <button type="submit" class="btn btn-primary">Vérifier</button>
        </form>
    @else
        <!-- Si le candidat existe, afficher ses informations -->
        <h3>Informations du candidat</h3>
        <p><strong>Nom:</strong> {{ session('candidate_data')->nom }}</p>
        <p><strong>Prénom:</strong> {{ session('candidate_data')->prenom }}</p>
        <p><strong>Date de naissance:</strong> {{ session('candidate_data')->date_naissance }}</p>

        <!-- Formulaire pour compléter les informations -->
        <form action="{{ route('candidats.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone">
            </div>

            <div class="mb-3">
                <label for="parti_politique" class="form-label">Parti Politique</label>
                <input type="text" class="form-control" id="parti_politique" name="parti_politique">
            </div>

            <div class="mb-3">
                <label for="slogan" class="form-label">Slogan</label>
                <input type="text" class="form-control" id="slogan" name="slogan">
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo">
            </div>

            <div class="mb-3">
                <label for="couleur_1" class="form-label">Couleur 1</label>
                <input type="color" class="form-control" id="couleur_1" name="couleur_1">
            </div>

            <div class="mb-3">
                <label for="couleur_2" class="form-label">Couleur 2</label>
                <input type="color" class="form-control" id="couleur_2" name="couleur_2">
            </div>

            <div class="mb-3">
                <label for="couleur_3" class="form-label">Couleur 3</label>
                <input type="color" class="form-control" id="couleur_3" name="couleur_3">
            </div>

            <div class="mb-3">
                <label for="url_infos" class="form-label">URL Informations</label>
                <input type="url" class="form-control" id="url_infos" name="url_infos">
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    @endif
</div>
@endsection
