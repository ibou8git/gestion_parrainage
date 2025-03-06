<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complément d'Informations</title>
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
        <h2>Complétez vos Informations de Contact</h2>
        <form method="POST" action="{{ route('electeurs.contact') }}">
            @csrf
            <label for="telephone">Numéro de téléphone</label>
            <input type="tel" name="telephone" id="telephone" required>

            <label for="email">Adresse Email</label>
            <input type="email" name="email" id="email" required>

            <button type="submit">Valider</button>
        </form>
    </div>
</body>
</html>
