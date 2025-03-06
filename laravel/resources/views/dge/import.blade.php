
<style>
    
</style>

@extends('layouts.app')

@section('title', 'Importer un fichier électoral')

@section('content')



@auth
    <div class="container">
        <div class="card">
            <!-- Message de succès après import -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <form action="{{ route('valider.importation') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Valider l’Importation</button>
                </form>
            @endif

            <!-- Message d'erreur -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Section Image -->
            <div class="image-section text-center">
                <img src="{{ asset('images/background.jpg') }}" alt="Image Description" class="img-fluid">
            </div>

            <!-- Section Formulaire -->
            <div class="form-section p-4">
                <h2 class="text-center mb-3">IMPORTATION</h2>
                <div class="form-container">
                    <form action="{{ route('import.electeurs') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="checksum" class="form-label">Empreinte SHA256</label>
                            <input type="text" name="checksum" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fichier" class="form-label">Fichier CSV</label>
                            <input type="file" name="fichier" class="form-control" accept=".csv" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Importer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning text-center">
        Vous devez être connecté pour importer des fichiers. <a href="{{ route('auth.login') }}">Se connecter</a>
    </div>
@endauth
<style>
    .container {
        max-width: 600px;
        margin-top: 20px;
    }
    .card {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    .image-section img {
        max-height: 200px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    .btn-success {
        width: 100%;
    }
</style>
