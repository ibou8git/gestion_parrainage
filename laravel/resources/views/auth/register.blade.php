<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Inscription</h3>
                    </div>
                    <div class="card-body">
                        <!-- Formulaire d'inscription -->
                        <form method="POST" action="{{ route('register.post') }}">
    @csrf <!-- Token CSRF pour la sécurité -->

    <!-- Champ Nom complet (name) -->
    <div class="mb-3">
        <label for="name" class="form-label">Nom complet</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <!-- Champ Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <!-- Champ Nom d'utilisateur (username) -->
    <div class="mb-3">
        <label for="username" class="form-label">Nom d'utilisateur</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>

    <!-- Champ Mot de passe (password) -->
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <!-- Champ Confirmation du mot de passe -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
    </div>

    <!-- Champ Rôle (role) -->
    <div class="mb-3">
        <label for="role" class="form-label">Rôle</label>
        <select class="form-select" id="role" name="role" required>
            <option value="member" selected>Membre</option>
            <option value="admin">Administrateur</option>
            <option value="editor">Éditeur</option>
        </select>
    </div>

    <!-- Bouton de soumission -->
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </div>
</form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
