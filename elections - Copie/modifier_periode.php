<?php
// modifier_periode.php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion_admin.php");
    exit();
}

$id_periode = $_GET['id_periode']; // Récupérer l'ID de la période à modifier

// Connexion à la base de données
$conn = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer les détails de la période à modifier
$sql = "SELECT * FROM periode_parrainage WHERE id_periode = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_periode);
$stmt->execute();
$result = $stmt->get_result();
$periode = $result->fetch_assoc();

$message = "";

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Vérifier que la date de début est avant la date de fin
    if ($date_debut >= $date_fin) {
        $message = "La date de début doit être avant la date de fin.";
    } else {
        // Mettre à jour la période dans la base de données
        $sql = "UPDATE periode_parrainage SET date_debut = ?, date_fin = ? WHERE id_periode = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $date_debut, $date_fin, $id_periode);

        if ($stmt->execute()) {
            $message = "Période mise à jour avec succès !";
        } else {
            $message = "Erreur lors de la mise à jour : " . $stmt->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la période</title>
    <link rel="stylesheet" href="">
    <style>
        /* Style global */
body {
    font-family: 'Poppins', sans-serif;
    background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1NiIgaGVpZ2h0PSIxMDAiPgo8cmVjdCB3aWR0aD0iNTYiIGhlaWdodD0iMTAwIiBmaWxsPSIjMkMzRTUwIj48L3JlY3Q+CjxwYXRoIGQ9Ik0yOCA2NkwwIDUwTDAgMTZMMjggMEw1NiAxNkw1NiA1MEwyOCA2NkwyOCAxMDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzFlMjEzMCIgc3Ryb2tlLXdpZHRoPSIyIj48L3BhdGg+CjxwYXRoIGQ9Ik0yOCAwTDI4IDM0TDAgNTBMMCA4NEwyOCAxMDBMNTYgODRMNTYgNTBMMjggMzQiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzFkMjUzMCIgc3Ryb2tlLXdpZHRoPSIyIj48L3BhdGg+Cjwvc3ZnPg==');
    margin: 0;
    padding: 0;
    min-height: 100vh;
    color: #e0e0e0;
    
}

.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 40px;
    background-color: rgba(30, 33, 48, 0.9);
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

/* Navbar */
.navbar {
    background-color: #1E2130;
    padding: 20px;
    border-radius: 8px 8px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    margin-bottom: 20px;
}

.navbar a {
    color: white;
    font-size: 18px;
    text-decoration: none;
    transition: color 0.3s;
}

.navbar a:hover {
    color: #FFCC00;
}

/* Titre de la page */
.page-title {
    text-align: center;
    font-size: 2rem;
    font-weight: 600;
    color: #FFCC00;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
}

/* Formulaire */
.form-group {
    margin-bottom: 20px;
}

label {
    font-size: 1rem;
    font-weight: 600;
    color: #e0e0e0;
    display: block;
    margin-bottom: 10px;
}

input, select, textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #444;
    background-color: #2C3E50;
    color: #e0e0e0;
    font-size: 1rem;
    transition: all 0.3s ease;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #FFCC00;
}

textarea {
    resize: vertical;
}

button {
    background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
    color: white;
    padding: 12px 30px;
    font-size: 1rem;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}

button:hover {
    transform: translateY(-3px) scale(1.03);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

button:active {
    transform: translateY(1px);
}

/* Section de confirmation ou erreur */
.alert {
    background-color: rgba(255, 99, 71, 0.8);
    color: white;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.alert.success {
    background-color: rgba(34, 193, 195, 0.8);
}

.alert.error {
    background-color: rgba(255, 99, 71, 0.8);
}

.alert p {
    margin: 0;
}

/* Footer */
footer {
    text-align: center;
    padding: 20px 0;
    background-color: #1E2130;
    color: #e0e0e0;
    font-size: 1rem;
    margin-top: 40px;
}

/* Bouton de retour */
.btn-back {
    background-color: #444;
    color: #e0e0e0;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 20px;
    display: inline-block;
    text-decoration: none;
}

.btn-back:hover {
    background-color: #FFCC00;
    color: #1E2130;
}

    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Modifier la période de parrainage</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="date_debut" class="form-label">Date de début :</label>
                <input type="date" class="form-control" name="date_debut" value="<?= $periode['date_debut'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="date_fin" class="form-label">Date de fin :</label>
                <input type="date" class="form-control" name="date_fin" value="<?= $periode['date_fin'] ?>" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
        </form>
    </div>
</body>
</html>