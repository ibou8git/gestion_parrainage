<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Candidat</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 5px; }
        .photo { max-width: 200px; }
        .back-btn { margin-top: 20px; display: inline-block; padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; border-radius: 3px; }
        .back-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Détails du Candidat</h2>
        <p><strong>Nom :</strong> {{ $candidat['nom'] }}</p>
        <p><strong>Slogan :</strong> {{ $candidat['slogan'] }}</p>
        <p><strong>Couleur du parti :</strong> {{ $candidat['couleur'] }}</p>
        <img src="{{ $candidat['photo'] }}" alt="Photo de {{ $candidat['nom'] }}" class="photo">
        <hr>
        <h3>Programme du Parti</h3>
        <p>{{ $candidat['programme'] }}</p>

        <a href="{{ route('electeurs.parrainage.form') }}" class="back-btn">Retour à la liste</a>
    </div>
</body>
</html>
