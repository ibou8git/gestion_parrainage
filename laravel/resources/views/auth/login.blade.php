@extends('layouts.app')

@section('title', 'Connexion DGE')

@section('content')
<div class="container">
    <div class="card p-4 shadow">
        <h2 class="text-center">Connexion DGE</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('auth.login.post') }}">
            @csrf
            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Se connecter</button>

            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('register.form') }}">Créer un compte</a>
        </div>
    </div>
</div>
@endsection
