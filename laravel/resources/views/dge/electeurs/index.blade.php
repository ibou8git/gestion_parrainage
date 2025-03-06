@extends('layouts.app')

@section('title', 'Liste des Électeurs')

@section('content')
<style>
    h2 {
        padding-top: 50px;
        font-size: 2rem;
    }
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        background: transparent;
        box-shadow: 5px 30px 56.1276px rgba(55, 55, 55, 0.12);
        text-align: center;
    }
    .btn-primary {
        background-color: darkslateblue;
        border-color: darkslateblue;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 15px;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: thistle;
        color: darkmagenta;
    }
    .card {
        width: 100%;
        padding: 20px;
        border-radius: 15px;
        background: #fff;
        box-shadow: 5px 30px 56.1276px rgba(55, 55, 55, 0.12);
    }
    .d-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid lightgray;
        padding: 10px 0;
    }
    .d-flex img {
        border-radius: 50%;
        height: 50px;
        width: 50px;
    }
    .d-flex .flex-grow-1 {
        display: flex;
        flex-direction: column;
    }
    .d-flex .flex-grow-1 strong {
        font-size: 1.1rem;
    }
    .d-flex .btn {
        border-radius: 15px;
        font-size: 14px;
    }
    .d-flex .btn-sm {
        padding: 5px 10px;
    }
</style>

<div class="container">
    <h2>Liste des Électeurs</h2>

    <a href="{{ route('electeurs.create') }}" class="btn btn-primary mb-3">Ajouter un électeur</a>

    <div class="card p-3">
        @foreach ($electeurs as $electeur)
        <div class="d-flex align-items-center border-bottom py-2">
            <img src="https://via.placeholder.com/50" class="rounded-circle me-3">
            <div class="flex-grow-1">
                <strong>{{ $electeur->nom }} {{ $electeur->prenom }}</strong>  
                <p class="mb-0">{{ $electeur->email }} | {{ $electeur->telephone }}</p>
            </div>
            <a href="{{ route('electeurs.edit', $electeur) }}" class="btn btn-warning btn-sm me-2">Modifier</a>
            <form action="{{ route('electeurs.destroy', $electeur) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection
