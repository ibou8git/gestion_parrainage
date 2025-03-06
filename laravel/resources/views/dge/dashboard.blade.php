@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Tableau de Bord - DGE</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body text-center">
                    <h4 class="card-title">Électeurs</h4>
                    <p class="card-text display-4">{{ $electeurs }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h4 class="card-title">Candidats</h4>
                    <p class="card-text display-4">{{ $candidats }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body text-center">
                    <h4 class="card-title">Parrainages</h4>
                    <p class="card-text display-4">{{ $parrainages }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
