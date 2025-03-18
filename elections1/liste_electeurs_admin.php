<?php
session_start();

// Vérifier si l'administrateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion_admin.php");
    exit();
}

// Connexion à la base de données
$host = "mysql-parrainages.alwaysdata.net";
$user = "404600_electeurs";
$password = "P@sser-123";
$database = "parrainages_elections";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupération des électeurs
$sql = "SELECT * FROM electeurs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Électeurs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2C3E50 0%, #1E2130 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: #fff;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #1E2130;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #FFCC00;
        }
        table {
            width: 100%;
            background-color: rgba(38, 41, 56, 0.8);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        th {
            background-color: rgba(30, 33, 48, 0.6);
            color: #FFCC00;
            font-weight: 600;
        }
        td {
            color: #e0e0e0;
        }
        .btn-danger {
            background-color: #DC143C;
            color: white;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            opacity: 0.8;
        } 

    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Liste des Électeurs</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>CIN</th>
                    <th>Numéro Électeur</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_electeur'] ?></td>
                        <td><?= htmlspecialchars($row['nom']) ?></td>
                        <td><?= htmlspecialchars($row['prenom']) ?></td>
                        <td><?= htmlspecialchars($row['cin']) ?></td>
                        <td><?= htmlspecialchars($row['num_electeur']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
