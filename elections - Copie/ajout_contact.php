<?php
//ajout_contact.php
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Charger l'autoloader de Composer
require 'vendor/autoload.php';

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;

// Configuration API de Brevo
$config = Configuration::getDefaultConfiguration()->setApiKey('api-key', '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'); // Remplace par ta clé API
$apiInstance = new TransactionalEmailsApi(new Client(), $config);

// Connexion à la base de données
$mysqli = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Message d'erreur ou de succès
$message = "";

// Récupérer le numéro d'électeur depuis l'URL
$num_electeur = isset($_GET['num_electeur']) ? $_GET['num_electeur'] : null;

if (!$num_electeur) {
    die("Erreur : Numéro d'électeur manquant dans l'URL.");
}

// Vérifier si le numéro d'électeur existe dans la table `electeurs`
$query = "SELECT * FROM electeurs WHERE num_electeur = ?";
$stmt = $mysqli->prepare($query);
if ($stmt === false) {
    die("Erreur de préparation de la requête : " . $mysqli->error);
}
$stmt->bind_param("s", $num_electeur);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Erreur : Le numéro d'électeur n'existe pas dans la table `electeurs`.");
}

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des informations saisies dans le formulaire
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];

    // Vérifier si l'e-mail ou le téléphone est déjà utilisé
    $query = "SELECT * FROM contacts WHERE email = ? OR telephone = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $mysqli->error);
    }
    $stmt->bind_param("ss", $email, $telephone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Afficher les résultats pour déboguer
        while ($row = $result->fetch_assoc()) {
            echo "Email existant : " . $row['email'] . "<br>";
            echo "Téléphone existant : " . $row['telephone'] . "<br>";
        }
        $message = "Erreur : Cet e-mail ou ce numéro de téléphone est déjà utilisé.";
    } else {
        // Génération du code d'authentification
$code_auth = rand(10000, 99999);  // Code d'authentification à 5 chiffres

// Démarrez la session et stockez le code dans la session
session_start(); // Démarrez la session si ce n'est pas déjà fait
$_SESSION['code_auth'] = $code_auth; // Stockez le code dans la session

// Enregistrer dans la base de données
$query = "INSERT INTO contacts (num_electeur, telephone, email, code_auth) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($query);
if ($stmt === false) {
    die("Erreur de préparation de la requête : " . $mysqli->error);
}
$stmt->bind_param("sssi", $num_electeur, $telephone, $email, $code_auth);

if ($stmt->execute()) {
    // Envoi du mail avec le code d'authentification via Brevo
    $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
        'to' => [['email' => $email, 'name' => 'Utilisateur']],  // L'email saisi dans le formulaire
        'sender' => ['email' => 'mayramarem@gmail.com', 'name' => 'Plateforme'], // Ton email ou celui de la plateforme
        'replyTo' => ['email' => 'mayramarem@gmail.com', 'name' => 'Plateforme'],
        'subject' => 'Votre code d\'authentification',
        'htmlContent' => "<strong>Bonjour,</strong><br><br>Votre code d'authentification est : <b>$code_auth</b><br><br>Cordialement.",
        'textContent' => "Bonjour,\n\nVotre code d'authentification est : $code_auth\n\nCordialement."
    ]);

    try {
        $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        // Si l'email est envoyé avec succès
        $message = "Un email avec le code d'authentification a été envoyé à $email.";
    } catch (Exception $e) {
        // En cas d'erreur d'envoi
        $message = 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage();
    }
} else {
    $message = "Erreur lors de l'enregistrement des informations de contact.";
}
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout des Informations de Contact</title>
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

        h1 {
            font-size: 2.2rem;
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

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(38, 41, 56, 0.8);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border-left: 4px solid #008000;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #FFCC00;
        }

        input[type="text"],
        input[type="email"] {
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

        p {
            text-align: center;
            color: #e0e0e0;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajout de vos informations de contact</h1>

        <!-- Affichage du message d'erreur ou de confirmation -->
        <?php if ($message) { echo "<p>$message</p>"; } ?>

        <!-- Formulaire d'ajout des informations de contact -->
        <form action="ajout_contact.php?num_electeur=<?php echo $num_electeur; ?>" method="POST">
    <label for="telephone">Numéro de téléphone :</label>
    <input type="text" name="telephone" id="telephone" required>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>

    <button type="submit">Ajouter les informations de contact</button>
    
    <!-- Ajouter un espace entre les boutons -->
    <div style="margin-top: 15px;"></div>
    
    <!-- Nouveau bouton pour retourner au menu -->
    <a href="index.php" class="btn btn-secondary" style="display: block; text-align: center; background: linear-gradient(to right, #3498db, #2980b9); color: white; padding: 12px 24px; font-size: 16px; border: none; border-radius: 50px; cursor: pointer; transition: all 0.3s ease; font-weight: 600; text-shadow: 1px 1px 1px rgba(0,0,0,0.5); box-shadow: 0 6px 12px rgba(0,0,0,0.3); text-decoration: none;">Retourner au menu</a>
</form>
        
    </div>

    <!-- Script pour Bootstrap (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>