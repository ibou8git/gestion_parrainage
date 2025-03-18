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
// Modification: Retrait de la condition sur statut qui n'existe pas
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

// Récupérer la liste des candidats avec les informations de l'électeur
$query = "SELECT c.id, e.nom, e.prenom, c.slogan, c.photo
          FROM candidats c
          JOIN electeurs e ON c.num_electeur = e.num_electeur";
$result = $mysqli->query($query);

if ($result === false) {
    die("Erreur lors de l'exécution de la requête : " . $mysqli->error);
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styles généraux */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h2.text-center {
            color: #0d6efd;
            margin-bottom: 30px;
            font-weight: bold;
            position: relative;
        }

        h2.text-center:after {
            content: "";
            display: block;
            width: 50px;
            height: 3px;
            background-color: #0d6efd;
            margin: 10px auto 0;
        }

        /* Tableau des candidats */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th {
            background-color: #0d6efd;
            color: white;
            font-weight: 500;
            padding: 12px;
            text-align: left;
        }

        .table td {
            padding: 12px;
            vertical-align: middle;
        }

        .table tr:hover {
            background-color: #f5f5f5;
        }

        .table img {
            border-radius: 50%;
            object-fit: cover;
            height: 50px;
            width: 50px;
            border: 2px solid #dee2e6;
        }

        /* Boutons */
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }

        .btn-success {
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(25, 135, 84, 0.3);
        }

        .btn-secondary {
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
        }

        /* Messages */
        .celebration-message {
            background-color: #d1e7dd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            animation: fadeIn 1s ease-in-out;
            border-left: 5px solid #198754;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-check {
            color: #198754;
            font-size: 40px;
            display: block;
            margin: 0 auto 10px;
        }

        .alert-info {
            border-left: 5px solid #0dcaf0;
            border-radius: 4px;
            background-color: #cff4fc;
            color: #055160;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .table {
                font-size: 14px;
            }
            
            .table th, .table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Liste des candidats</h2>

        <?php if ($parrainage_existant): ?>
            <div class="celebration-message text-center">
                <i class="fas fa-check-circle success-check mb-3"></i>
                <h4>Bravo! Votre parrainage a été enregistré avec succès!</h4>
                <p class="mb-0">Vous avez contribué au processus démocratique. Votre voix compte!</p>
            </div>
        <?php endif; ?>

        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <table class="table table-striped">
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
                <?php if (!empty($candidats)): ?>
                    <?php foreach ($candidats as $candidat): ?>
                        <tr>
                            <td><?= htmlspecialchars($candidat['nom'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($candidat['prenom'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($candidat['slogan'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if (!empty($candidat['photo'])): ?>
                                    <img src="<?= htmlspecialchars($candidat['photo']); ?>" alt="Photo du candidat" width="50">
                                <?php else: ?>
                                    Aucune photo
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($parrainage_existant): ?>
                                    <?php if ($candidat_parraine_id == $candidat['id']): ?>
                                        <button class="btn btn-success" disabled>
                                            <i class="fas fa-check-circle me-1"></i> Parrainé
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>
                                            Indisponible
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="validation_parrainage.php?candidat_id=<?= $candidat['id']; ?>" class="btn btn-primary">Parrainer</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucun candidat trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if (!$parrainage_existant): ?>
            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle me-2"></i> 
                Vous ne pouvez parrainer qu'un seul candidat. Une fois votre choix fait, il ne sera plus possible de le modifier.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>