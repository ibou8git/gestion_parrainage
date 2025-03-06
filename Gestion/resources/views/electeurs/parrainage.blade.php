<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix du Candidat</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 5px; }
        .candidat { border: 1px solid #ccc; padding: 10px; margin: 10px; border-radius: 3px; }
        .candidat img { max-width: 100px; }
        button { padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Liste des Candidats</h2>
        <div id="liste-candidats">
            <!-- Exemple statique. À remplacer par des données dynamiques -->
            <div class="candidat">
                <p><strong>Nom :</strong> Candidat A</p>
                <p><strong>Slogan :</strong> Pour un avenir meilleur</p>
                <p><strong>Couleur du parti :</strong> Bleu</p>
                <img src="/images/candidatA.jpg" alt="Photo Candidat A">
                <br>
                <button onclick="window.location.href='{{ route('electeurs.candidat.details', ['id' => 1]) }}'">Détails</button>
                <button onclick="parrainer(1)">Parrainer</button>
            </div>
            <div class="candidat">
                <p><strong>Nom :</strong> Candidat B</p>
                <p><strong>Slogan :</strong> Ensemble pour le changement</p>
                <p><strong>Couleur du parti :</strong> Rouge</p>
                <img src="/images/candidatB.jpg" alt="Photo Candidat B">
                <br>
                <button onclick="window.location.href='{{ route('electeurs.candidat.details', ['id' => 2]) }}'">Détails</button>
                <button onclick="parrainer(2)">Parrainer</button>
            </div>
        </div>
    </div>
    
    <script>
        function parrainer(candidatId) {
            // Envoyer la sélection au backend via AJAX (comme indiqué dans notre précédent code)
            fetch('/api/electeurs/parrainage', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ candidat_id: candidatId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Un code à usage unique a été envoyé sur votre email et téléphone.');
                    window.location.href = '/electeurs/confirmer_parrainage';
                } else {
                    alert('Erreur : ' + data.error);
                }
            })
            .catch(err => console.error(err));
        }
    </script>
</body>
</html>
