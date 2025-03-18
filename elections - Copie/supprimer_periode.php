<?php
// supprimer_periode.php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion_admin.php");
    exit();
}

$id_periode = $_GET['id_periode']; // Récupérer l'ID de la période à supprimer

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'elections');

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Supprimer la période de la base de données
$sql = "DELETE FROM periode_parrainage WHERE id_periode = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_periode);

if ($stmt->execute()) {
    header("Location: dashboard_admin.php"); // Rediriger vers le dashboard après la suppression
} else {
    echo "Erreur lors de la suppression : " . $stmt->error;
}

$stmt->close();
$conn->close();
?>