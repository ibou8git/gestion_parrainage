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
        /* Styles généraux */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 0 auto;
        }

        h1 {
            color: #0d6efd;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
            position: relative;
        }

        h1:after {
            content: "";
            display: block;
            width: 70px;
            height: 3px;
            background-color: #0d6efd;
            margin: 10px auto 0;
        }

        /* Formulaire */
        form {
            margin-top: 20px;
        }

        label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: #495057;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #0d6efd;
            outline: none;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
        }

        button[type="submit"] {
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }

        /* Message d'erreur */
        .error-message {
            background-color: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 5px solid #842029;
        }

        /* Classes utilitaires Bootstrap */
        .mt-5 {
            margin-top: 3rem !important;
        }
        
        .me-2 {
            margin-right: 0.5rem !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            input[type="text"], button[type="submit"] {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Vérification de vos informations d'identité</h1>

        <!-- Affichage du message d'erreur ou de confirmation -->
        <?php if ($message): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de vérification des informations -->
        <form action="identification_electeur.php" method="POST">
            <label for="num_electeur">Numéro de carte d'électeur :</label>
            <input type="text" name="num_electeur" id="num_electeur" required>

            <label for="cin">Numéro de carte d'identité nationale :</label>
            <input type="text" name="cin" id="cin" required>

            <label for="nom">Nom de famille :</label>
            <input type="text" name="nom" id="nom" required>

            <label for="bureau">Bureau de vote :</label>
            <input type="text" name="bureau" id="bureau" required>

            <button type="submit">
                <i class="fas fa-check-circle me-2"></i>Vérifier les informations
            </button>
        </form>
    </div>
</body>
</html>