<?php
//identification_electeur.php
// Connexion à la base de données
$mysqli = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Message de retour
$message = "";

// Si le formulaire d'authentification a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['num_electeur'])) {
    $num_electeur = $_POST['num_electeur'];
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $bureau = $_POST['bureau'];

    // Vérification des informations d'authentification dans la base de données
    $query = "SELECT * FROM electeurs WHERE num_electeur = ? AND cin = ? AND nom = ? AND bureau = ?";
    $stmt = $mysqli->prepare($query);

    // Vérifier si la préparation de la requête a échoué
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $mysqli->error);
    }

    $stmt->bind_param("ssss", $num_electeur, $cin, $nom, $bureau);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si les informations ne correspondent à aucun électeur
    if ($result->num_rows == 0) {
        $message = "Erreur : Les informations d'identification ne correspondent à aucun électeur enregistré.";
    } else {
        // Si les informations sont correctes, rediriger vers la page suivante (ajout des informations de contact)
        header("Location: ajout_contact.php?num_electeur=" . urlencode($num_electeur));
        exit();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification des Informations d'Identité</title>
    <!-- Ajout des liens vers Bootstrap et Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
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

        .alert {
            background-color: rgba(255, 0, 0, 0.2);
            border: 1px solid rgba(255, 0, 0, 0.5);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #ffcccc;
            text-align: center;
        }

        form {
            background-color: rgba(38, 41, 56, 0.8);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border-left: 4px solid #008000;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #FFCC00;
        }

        input[type="num_electeur"],
        input[type="cin"],
        input[type="nom"],
        input[type="bureau"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            background-color: rgba(30, 33, 48, 0.6);
            color: #fff;
            font-size: 1rem;
        }

        button[type="submit"] {
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
            display: block;
            width: 100%;
        }

        button[type="submit"]:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        }

        button[type="submit"]:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Vérification de vos informations d'identité</h2>

        <!-- Affichage du message d'erreur ou de confirmation -->
        <?php if ($message): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de vérification des informations -->
        <form action="identification_electeur.php" method="POST">

            <div>
            <label for="num_electeur">Numéro de carte d'électeur :</label>
            <input type="num_electeur" name="num_electeur" id="num_electeur" required>
            </div>

            <div>
            <label for="cin">Numéro de carte d'identité nationale :</label>
            <input type="cin" name="cin" id="cin" required>
            </div>

            <div>
            <label for="nom">Nom de famille :</label>
            <input type="nom" name="nom" id="nom" required>
            </div>

            <div>
            <label for="bureau">Bureau de vote :</label>
            <input type="bureau" name="bureau" id="bureau" required>
            </div>

            <button type="submit">
                <i class="fas fa-check-circle me-2"></i>Vérifier les informations
            </button>
        </form>
    </div>
</body>
</html>