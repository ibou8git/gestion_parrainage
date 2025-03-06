@extends('layouts.app')

@section('title', 'Gestion des Candidats')

@section('content')
<div class="container">
    <h2 class="text-center">Liste des Candidats</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('candidats.create') }}" class="btn btn-primary mb-3">Ajouter un candidat</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Parti Politique</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($candidats as $candidat)
            <tr>
                <td><img src="{{ asset('storage/' . $candidat->photo) }}" width="50"></td>
                <td>{{ $candidat->nom }}</td>
                <td>{{ $candidat->prenom }}</td>
                <td>{{ $candidat->parti_politique }}</td>
                <td>{{ $candidat->email }}</td>
                <td>{{ $candidat->telephone }}</td>
                <td>
                    <a href="{{ route('candidats.edit', $candidat) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('candidats.destroy', $candidat) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
