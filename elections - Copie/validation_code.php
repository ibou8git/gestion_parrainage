<?php
session_start();

if (!isset($_SESSION['code_authentification'])) {
    header("Location: enregistrement_parrain.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code_saisi = trim($_POST['code']);

    if ($code_saisi == $_SESSION['code_authentification']) {
        // Code valide, enregistrer le parrain dans la base de données
        $conn = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');
        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO parrains (num_electeur, email, telephone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_SESSION['num_electeur'], $_SESSION['email'], $_SESSION['telephone']);

        if ($stmt->execute()) {
            $message = "Votre compte a été créé avec succès !";
            header("Location: parrainer_candidat.php");
            exit();
        } else {
            $message = "Erreur lors de la création du compte.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $message = "Code d'authentification incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation du code</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- CSS de index.php (ajoutez votre propre fichier CSS si nécessaire) -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .alert {
            margin-top: 20px;
        }
        .form-control {
            border-radius: 0.25rem;
            border-color: #ced4da;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Validation du code d'authentification</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="code">Code reçu par email/SMS :</label>
                <input type="text" id="code" class="form-control" name="code" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Valider</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>