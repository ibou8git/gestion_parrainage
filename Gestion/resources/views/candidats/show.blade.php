@extends('layouts.app')

@section('title', 'Détails du candidat')

@section('content')
<div class="container">
    <h2>Détails du candidat</h2>

    <div class="card">
        <div class="card-header">
            <h4>{{ $candidat->nom }} {{ $candidat->prenom }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Numéro de carte électorale:</strong> {{ $candidat->numero_carte }}</p>
            <p><strong>Date de naissance:</strong> {{ $candidat->date_naissance }}</p>
            <p><strong>Email:</strong> {{ $candidat->email }}</p>
            <p><strong>Téléphone:</strong> {{ $candidat->telephone }}</p>
            <p><strong>Parti politique:</strong> {{ $candidat->parti_politique }}</p>
            <p><strong>Slogan:</strong> {{ $candidat->slogan }}</p>
            <p><strong>URL Informations:</strong> <a href="{{ $candidat->url_infos }}" target="_blank">{{ $candidat->url_infos }}</a></p>

            @if ($candidat->photo)
                <p><strong>Photo:</strong> <img src="{{ asset('storage/' . $candidat->photo) }}" alt="Photo du candidat" class="img-fluid"></p>
            @endif
        </div>
    </div>

    <a href="{{ route('candidats.index') }}" class="btn btn-secondary mt-4">Retour à la liste des candidats</a>
</div>
@endsection
