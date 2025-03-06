<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DGE - Accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Bienvenue sur le Système de Gestion des Élections</h1>
        
        <div class="d-flex justify-content-center mt-4">
            <a href="{{ route('dge.electeurs') }}" class="btn btn-primary mx-2">Liste des Électeurs</a>
            <a href="{{ route('dge.candidats') }}" class="btn btn-success mx-2">Liste des Candidats</a>
            <a href="{{ route('dge.dashboard') }}" class="btn btn-info mx-2">Dashboard DGE</a>
        </div>
      

    </div>
</body>
</html>
