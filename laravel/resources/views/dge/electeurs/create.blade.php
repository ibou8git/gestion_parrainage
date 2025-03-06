@extends('layouts.app')

@section('title', 'Ajouter un électeur')

@section('content')
<style>
    h2 {
        padding-top: 50px;
    }
    .container {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 80px;
        background: transparent;
        box-shadow: 5px 30px 56.1276px rgba(55, 55, 55, 0.12);
        position: relative;
        transition: all 0.35s ease;
        text-align: center;
        gap: 2rem;
    }
    .container img {
        background: transparent;
        box-shadow: 5px 30px 56.1276px rgba(55, 55, 55, 0.12);
        border-radius: 25px;
        height: 300px;
    }
    .form-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .form-container .field {
        height: 50px;
        width: 100%;
        margin-top: 20px;
    }
    .form-container .field input {
        height: 100%;
        width: 100%;
        outline: none;
        padding-left: 15px;
        border-radius: 15px;
        border: 1px solid lightgrey;
        border-bottom-width: 2px;
        font-size: 17px;
        transition: all 0.3s ease;
    }
    .form-container .field input:focus {
        border-color: green;
    }
    form .btn input[type="submit"]:hover {
        background-color: thistle;
        color: darkmagenta;
    }
    form .btn input[type="submit"] {
        height: 100%;
        width: 100%;
        z-index: 1;
        position: relative;
        background: green;
        border: none;
        color: #fff;
        padding-left: 0;
        border-radius: 15px;
        font-size: 20px;
        font-weight: 500;
        cursor: pointer;
    }
    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 10px;
    }
</style>

<h2 class="text-center">Ajouter un Électeur</h2>

<div class="container">
    <div class="pic">
        <img src="nissanCabstar.PNG" alt="">
    </div>
    @if (isset($error_message))
        <div class="error-message">{{ $error_message }}</div>
    @endif
    <div class="form-container">
        <form action="{{ route('electeurs.store') }}" method="POST">
            @csrf
            <div class="field mb-3">
                <input type="text" name="nom" placeholder="Nom" required>
            </div>
            <div class="field mb-3">
                <input type="text" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="field mb-3">
                <input type="text" name="carte_identite" placeholder="Carte d’Identité" required>
            </div>
            <div class="field mb-3">
                <input type="text" name="num_electeur" placeholder="Numéro d’Électeur" required>
            </div>
            <div class="field mb-3">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="field mb-3">
                <input type="text" name="telephone" placeholder="Téléphone" required>
            </div>
            <div class="field mb-3">
                <input type="text" name="bureau_vote" placeholder="Bureau de Vote" required>
            </div>
            <button type="submit" class="btn btn-success">Ajouter</button>
        </form>
    </div>
</div>
@endsection
