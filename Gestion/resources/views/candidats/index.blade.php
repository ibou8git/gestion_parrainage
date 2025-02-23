@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Liste des candidats</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('candidats.create') }}" class="btn btn-primary mb-3">Ajouter un candidat</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Parti Politique</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidats as $candidat)
            <tr>
                <td>{{ $candidat->nom }}</td>
                <td>{{ $candidat->prenom }}</td>
                <td>{{ $candidat->parti_politique }}</td>
                <td>
                    <a href="{{ route('candidats.show', $candidat->id) }}" class="btn btn-info btn-sm">Voir</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
