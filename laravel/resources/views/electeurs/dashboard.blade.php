@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tableau de Bord des Électeurs</h1>
    <p>Bienvenue, {{ Auth::guard('electeur')->user()->email }} !</p>
    <a href="{{ route('electeurs.logout') }}" class="btn btn-danger">Se déconnecter</a>
</div>
@endsection