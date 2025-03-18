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

$message = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Vérifier que la date de début est avant la date de fin
    if ($date_debut >= $date_fin) {
        $message = "La date de début doit être avant la date de fin.";
    } else {
        // Insérer les dates dans la base de données
        $sql = "INSERT INTO periode_parrainage (date_debut, date_fin) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $date_debut, $date_fin);

        if ($stmt->execute()) {
            $message = "Période de parrainage définie avec succès !";
        } else {
            $message = "Erreur lors de la définition de la période : " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
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

        h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #FFCC00;
            margin-bottom: 20px;
        }

        .alert {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #e0e0e0;
            text-align: center;
        }

        form {
            background-color: rgba(38, 41, 56, 0.8);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border-left: 4px solid #008000;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #FFCC00;
        }

        input[type="date"] {
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

        .btn-warning, .btn-danger {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-warning {
            background-color: #FFCC00;
            color: #1E2130;
        }

        .btn-danger {
            background-color: #DC143C;
            color: white;
        }

        .btn-warning:hover, .btn-danger:hover {
            opacity: 0.8;
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
    <!-- Barre de navigation -->
    <div class="navbar">
        <div class="navbar-left">
            <a href="index.php">
            <span style="color: white; font-weight: 600; letter-spacing: 0.5px;">Page principale</span></a>
        </div>
        <div class="navbar-right">
            <a href="liste_electeurs_admin.php">Électeurs</a>
            <a href="liste_candidats_admin.php">Candidats</a>
            <a href="upload.php">Upload CSV</a>
            <a href="logout_admin.php">Deconnexion</a>
        </div>
    </div>

    <div class="container">
        <h2>Dashboard Admin</h2>

        <?php if ($message): ?>
            <div class="alert"><?= $message; ?></div>
        <?php endif; ?>

        <h4>Définir la période de parrainage</h4>
        <form method="POST">
            <div>
                <label for="date_debut">Date de début :</label>
                <input type="date" name="date_debut" required>
            </div>

            <div>
                <label for="date_fin">Date de fin :</label>
                <input type="date" name="date_fin" required>
            </div>

            <button type="submit">Définir la période</button>
        </form>

        <hr>

        <h4>Gérer les périodes de parrainage</h4>
        <table>
            <thead>
                <tr>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer toutes les périodes de parrainage
                $sql = "SELECT * FROM periode_parrainage ORDER BY date_debut DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['date_debut'] . "</td>
                                <td>" . $row['date_fin'] . "</td>
                                <td>
                                    <a href='modifier_periode.php?id_periode=" . $row['id_periode'] . "' class='btn btn-warning'>Modifier</a>
                                    <a href='supprimer_periode.php?id_periode=" . $row['id_periode'] . "' class='btn btn-danger'>Supprimer</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>Aucune période définie.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Script pour Bootstrap (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>