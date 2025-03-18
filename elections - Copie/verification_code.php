<?php
session_start();

// Vérifier si l'électeur est authentifié
if (!isset($_SESSION['electeur_id'])) {
    header("Location: authentification_electeur.php");
    exit();
}

// Connexion à la base de données
$mysqli = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');

if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Récupérer les informations de l'électeur
$electeur_id = $_SESSION['electeur_id'];
$query = "SELECT nom, prenom, date_naissance, bureau FROM electeurs WHERE id_electeur = ?";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    die("Erreur de requête : " . $mysqli->error);
}

$stmt->bind_param("i", $electeur_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $electeur = $result->fetch_assoc();
} else {
    die("Erreur : Électeur non trouvé.");
}

$stmt->close();
$mysqli->close();

$message = "";

// Vérification du code soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code_saisi = trim($_POST['code']);

    if (isset($_SESSION['code_auth']) && $code_saisi === $_SESSION['code_auth']) {
        header("Location: liste_candidats.php");
        exit();
    } else {
        $message = "Code incorrect. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du Code</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2C3E50 0%, #1E2130 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .container {
            max-width: 450px;
            width: 90%;
            background-color: #1E2130;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h2 {
            color: #FFCC00;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .card {
            background: rgba(44, 62, 80, 0.7);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .card-title {
            color: #FFCC00;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        .card-text {
            color: #e0e0e0;
            margin: 5px 0;
            font-size: 1rem;
        }

        .alert {
            background-color: rgba(220, 53, 69, 0.8);
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background-color: #2C3E50;
            border: 1px solid #444;
            color: #fff;
            border-radius: 5px;
            text-align: center;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #FFCC00;
            outline: none;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            border: none;
            border-radius: 50px;
            font-weight: 600;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: #fff;
            margin-top: 15px;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Vérification du Code</h2>

        <div class="card">
            <h5 class="card-title">Informations de l'électeur</h5>
            <p class="card-text"><strong>Nom :</strong> <?= htmlspecialchars($electeur['nom']); ?></p>
            <p class="card-text"><strong>Prénom :</strong> <?= htmlspecialchars($electeur['prenom']); ?></p>
            <p class="card-text"><strong>Date de naissance :</strong> <?= htmlspecialchars($electeur['date_naissance']); ?></p>
            <p class="card-text"><strong>Bureau :</strong> <?= htmlspecialchars($electeur['bureau']); ?></p>
        </div>

        <?php if ($message): ?>
            <div class="alert"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Code d'authentification :</label>
            <input type="text" name="code" class="form-control" required>
            <button type="submit" class="btn-primary">Valider</button>
        </form>
    </div>
</body>
</html>