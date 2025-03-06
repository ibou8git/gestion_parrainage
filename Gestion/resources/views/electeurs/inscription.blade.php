<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription du Parrain</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 500px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 5px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 3px; }
        button { padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inscription du Parrain</h2>
        <!-- Affichage des erreurs éventuelles -->
        @if(session('error'))
            <div style="color: red;">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('electeurs.inscription') }}">
            @csrf
            <label for="num_carte_electeur">Numéro de carte d'électeur</label>
            <input type="text" name="num_carte_electeur" id="num_carte_electeur" required>

            <label for="num_carte_identite">Numéro de carte d'identité nationale</label>
            <input type="text" name="num_carte_identite" id="num_carte_identite" required>

            <label for="nom_famille">Nom de famille</label>
            <input type="text" name="nom_famille" id="nom_famille" required>

            <label for="num_bureau_vote">Numéro de bureau de vote</label>
            <input type="text" name="num_bureau_vote" id="num_bureau_vote" required>

            <button type="submit">Suivant</button>
        </form>
    </div>
</body>
</html>
