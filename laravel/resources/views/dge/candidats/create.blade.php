@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Ajouter un Candidat</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('candidats.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-bold">Nom</label>
                        <input type="text" class="form-control rounded-pill" id="nom" name="nom" required>
                    </div>

                    <div class="mb-3">
                        <label for="prenom" class="form-label fw-bold">Prénom</label>
                        <input type="text" class="form-control rounded-pill" id="prenom" name="prenom" required>
                    </div>

                    <div class="mb-3">
                        <label for="parti_politique" class="form-label fw-bold">Parti Politique</label>
                        <input type="text" class="form-control rounded-pill" id="parti_politique" name="parti_politique" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control rounded-pill" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="telephone" class="form-label fw-bold">Téléphone</label>
                        <input type="text" class="form-control rounded-pill" id="telephone" name="telephone" required>
                    </div>

                    <div class="mb-3">
                        <label for="slogan" class="form-label fw-bold">Slogan</label>
                        <input type="text" class="form-control rounded-pill" id="slogan" name="slogan">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label fw-bold">Photo</label>
                <input type="file" class="form-control rounded-pill" id="photo" name="photo" accept="image/*">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">Ajouter</button>
                <a href="{{ route('candidats.index') }}" class="btn btn-secondary btn-lg rounded-pill px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

<style>
    .container {
        max-width: 700px;
    }

    .card {
        border-radius: 20px;
        background: #fff;
    }

    .form-control {
        border: 2px solid #ddd;
        padding: 10px 15px;
    }

    .form-label {
        color: #333;
    }

    .btn-primary {
        background-color: #1877F2;
        border: none;
    }

    .btn-primary:hover {
        background-color: #125db4;
    }

    .btn-secondary {
        background-color: #E4E6EB;
        border: none;
        color: black;
    }

    .btn-secondary:hover {
        background-color: #d3d6db;
    }
</style>
@endsection
