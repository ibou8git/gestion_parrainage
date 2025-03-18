<?php
session_start();

// Vérifier si l'électeur est authentifié
if (!isset($_SESSION['electeur_id'])) {
    header("Location: authentification_electeur.php");
    exit();
}

$electeur_id = $_SESSION['electeur_id'];

// Connexion à la base de données
$mysqli = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Vérifier si l'électeur a déjà parrainé un candidat
$query_parrainage = "SELECT candidat_id FROM parrainages WHERE electeur_id = ?";
$stmt_parrainage = $mysqli->prepare($query_parrainage);

if ($stmt_parrainage === false) {
    die("Erreur de préparation de la requête : " . $mysqli->error);
}

$stmt_parrainage->bind_param("i", $electeur_id);
$stmt_parrainage->execute();
$result_parrainage = $stmt_parrainage->get_result();
$parrainage_existant = false;
$candidat_parraine_id = null;

if ($result_parrainage->num_rows > 0) {
    $parrainage_existant = true;
    $row_parrainage = $result_parrainage->fetch_assoc();
    $candidat_parraine_id = $row_parrainage['candidat_id'];
}

$stmt_parrainage->close();

// Récupérer la liste des candidats
$query = "SELECT c.id, e.nom, e.prenom, c.slogan, c.photo FROM candidats c JOIN electeurs e ON c.num_electeur = e.num_electeur";
$result = $mysqli->query($query);

$candidats = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $candidats[] = $row;
    }
} else {
    $message = "Aucun candidat trouvé.";
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des candidats</title>
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
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            margin-bottom: 30px;
            overflow: hidden;
            background-color: #1E2130;
            color: #fff;
            padding: 30px;
        }

        .navbar {
            background-color: #1E2130;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s;
            position: relative;
        }

        .navbar a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            transition: width 0.3s ease;
        }

        .navbar a:hover {
            color: #FFCC00;
        }

        .navbar a:hover:after {
            width: 100%;
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-left .senegal-flag {
            width: 50px;
            height: 33px;
            margin-right: 15px;
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            overflow: hidden;
            border-radius: 4px;
        }

        .main-title {
            font-size: 2.2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .info-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 40px;
            text-align: center;
            font-weight: 300;
            color: #e0e0e0;
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #fff;
            background-color: rgba(38, 41, 56, 0.8);
            border-radius: 8px;
            overflow: hidden;
        }

        .table th, .table td {
            padding: 12px;
            vertical-align: middle;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(30, 33, 48, 0.6);
            color: #FFCC00;
        }

        .table tbody + tbody {
            border-top: 2px solid rgba(255, 255, 255, 0.1);
        }

        .btn-primary, .btn-secondary, .btn-success {
            transition: all 0.3s ease;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.5);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(to right, #6c757d, #495057);
            color: white;
        }

        .btn-success {
            background: linear-gradient(to right, #28a745, #218838);
            color: white;
        }

        .btn-primary:hover, .btn-secondary:hover, .btn-success:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        }

        .btn-primary:active, .btn-secondary:active, .btn-success:active {
            transform: translateY(1px);
        }

        .btn-primary::before, .btn-secondary::before, .btn-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s ease;
        }

        .btn-primary:hover::before, .btn-secondary:hover::before, .btn-success:hover::before {
            left: 100%;
        }

        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-info {
            background-color: rgba(23, 162, 184, 0.2);
            border-color: rgba(23, 162, 184, 0.3);
            color: #17a2b8;
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Barre de navigation avec la même couleur que le fond principal -->
    <div class="navbar">
        <!-- Partie gauche : Drapeau du Sénégal -->
        <div class="navbar-left">
            <!-- Drapeau du Sénégal en SVG avec étoile correcte -->
            <div class="senegal-flag">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600">
                    <rect width="300" height="600" fill="#00853f"/>
                    <rect x="300" width="300" height="600" fill="#fdef42"/>
                    <rect x="600" width="300" height="600" fill="#e31b23"/>
                    <!-- Étoile à 5 branches au lieu du triangle -->
                    <path d="M450,200 L472.7,273.2 L550,273.2 L486.6,317.6 L509.3,390.8 L450,346.4 L390.7,390.8 L413.4,317.6 L350,273.2 L427.3,273.2 Z" fill="#00853f"/>
                </svg>
            </div>
            <span style="color: white; font-weight: 600; letter-spacing: 0.5px;">Parrainages Sénégal</span>
        </div>

        <!-- Partie droite : Liens de navigation -->
        <div class="navbar-right">
            <a href="authentification_electeur.php">Parrainages</a>
            <a href="connexion_admin.php">DGE</a>
            <a href="connexion_candidat.php">Candidat</a>
            <a href="identification_electeur.php">Électeur</a>
        </div>
    </div>

    <div class="container">
        <h2 class="main-title">Liste des candidats</h2>
        <?php if (isset($message)): ?>
            <div class="alert alert-info text-center"> <?= htmlspecialchars($message); ?> </div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Slogan</th>
                    <th>Photo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($candidats as $candidat): ?>
                    <tr>
                        <td><?= htmlspecialchars($candidat['nom']); ?></td>
                        <td><?= htmlspecialchars($candidat['prenom']); ?></td>
                        <td><?= htmlspecialchars($candidat['slogan']); ?></td>
                        <td>
                            <?php if (!empty($candidat['photo'])): ?>
                                <img src="<?= htmlspecialchars($candidat['photo']); ?>" alt="Photo" width="50">
                            <?php else: ?>
                                Aucune photo
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($parrainage_existant && $candidat_parraine_id == $candidat['id']): ?>
                                <button class="btn btn-success" disabled>Parrainé</button>
                            <?php elseif ($parrainage_existant): ?>
                                <button class="btn btn-secondary" disabled>Indisponible</button>
                            <?php else: ?>
                                <a href="validation_parrainage.php?candidat_id=<?= $candidat['id']; ?>" class="btn btn-primary">Parrainer</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">Retour au menu principal</a>
        </div>
    </div>

    <!-- Script pour Bootstrap (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
