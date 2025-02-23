<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du Code</title>
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
        <h2>Vérification de votre Code d'Authentification</h2>
        <form method="POST" action="{{ route('electeurs.verification') }}">
            @csrf
            <label for="code">Saisissez le code reçu</label>
            <input type="text" name="code" id="code" required>
            <button type="submit">Valider le Code</button>
        </form>
        <br>
        <form method="POST" action="{{ route('electeurs.renvoyer_code') }}">
            @csrf
            <button type="submit">Renvoyer le Code</button>
        </form>
    </div>
</body>
</html>
