<?php
session_start();

if (!isset($_SESSION['code_validation'])) {
    header("Location: validation_parrainage.php");
    exit();
}

$message = "";

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code_saisi = $_POST['code_validation'];

    if ($code_saisi == $_SESSION['code_validation']) {
        // Code valide, afficher la confirmation
        $message = "Confirmation du parrainage";
        unset($_SESSION['code_validation']); // Supprimer le code de la session
    } else {
        $message = "Erreur : Code de validation incorrect.";
    }
} else {
    // Rediriger si le formulaire n'a pas été soumis
    header("Location: validation_parrainage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation du parrainage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Style global avec nouveau fond */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2C3E50 0%, #1E2130 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1NiIgaGVpZ2h0PSIxMDAiPgo8cmVjdCB3aWR0aD0iNTYiIGhlaWdodD0iMTAwIiBmaWxsPSIjMkMzRTUwIj48L3JlY3Q+CjxwYXRoIGQ9Ik0yOCA2NkwwIDUwTDAgMTZMMjggMEw1NiAxNkw1NiA1MEwyOCA2NkwyOCAxMDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzFlMjEzMCIgc3Ryb2tlLXdpZHRoPSIyIj48L3BhdGg+CjxwYXRoIGQ9Ik0yOCAwTDI4IDM0TDAgNTBMMCA4NEwyOCAxMDBMNTYgODRMNTYgNTBMMjggMzQiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzFkMjUzMCIgc3Ryb2tlLXdpZHRoPSIyIj48L3BhdGg+Cjwvc3ZnPg==');
            background-attachment: fixed;
            color: #fff;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            margin-top: 50px;
            margin-bottom: 30px;
            overflow: hidden;
            background-color: #1E2130;
        }

        h2 {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .btn-primary {
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.5);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        }

        .btn-primary:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?= htmlspecialchars($message); ?></h2>

        <?php if ($message === "Confirmation du parrainage"): ?>
            <div class="text-center mt-4">
                <a href="liste_candidats.php" class="btn btn-primary">Retour à la liste des candidats</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Script pour Bootstrap (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>