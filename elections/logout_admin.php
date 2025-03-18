<?php
// logout_admin.php

session_start(); // Démarre la session
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session

// Redirection vers la page de connexion
header("Location: connexion_admin.php");
exit;
?>
