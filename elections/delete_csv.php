<?php
// delete_csv.php

// Connexion à la base de données
$mysqli = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');

if ($mysqli->connect_error) {
    die("Échec de la connexion à la base de données: " . $mysqli->connect_error);
}

// Suppression des données de la table electeurs_problemes
$mysqli->query("DELETE FROM electeurs_problemes");

// Suppression des données de la table electeurs
$mysqli->query("DELETE FROM electeurs");

// Suppression des données de la table electeurs_temp
$mysqli->query("DELETE FROM electeurs_temp");

// Fermer la connexion
$mysqli->close();

// Redirection vers la page d'importation
header("Location: upload.php");
exit;
?>
