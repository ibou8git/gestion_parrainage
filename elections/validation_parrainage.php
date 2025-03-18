<?php
session_start();

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
$config = Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
$apiInstance = new TransactionalEmailsApi(new Client(), $config);

if (!isset($_SESSION['electeur_id'])) {
    header("Location: authentification_electeur.php");
    exit();
}

if (!isset($_GET['candidat_id'])) {
    header("Location: liste_candidats.php");
    exit();
}

$candidat_id = $_GET['candidat_id'];
$electeur_id = $_SESSION['electeur_id'];

// Connexion à la base de données
$mysqli = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$message = "";

// Récupérer l'email de l'électeur
$query_email = "SELECT c.email, e.num_electeur FROM contacts c 
                JOIN electeurs e ON c.num_electeur = e.num_electeur 
                WHERE e.id_electeur = ? 
                ORDER BY c.created_at DESC LIMIT 1";
$stmt_email = $mysqli->prepare($query_email);

if ($stmt_email === false) {
    die("Erreur de requête : " . $mysqli->error);
}

$stmt_email->bind_param("i", $electeur_id);
$stmt_email->execute();
$result_email = $stmt_email->get_result();

if ($result_email->num_rows == 0) {
    die("Aucune information de contact trouvée pour cet électeur.");
}

$row = $result_email->fetch_assoc();
$email = $row['email'];
$num_electeur = $row['num_electeur'];

// Générer un code unique
$code_validation = rand(10000, 99999);

// Enregistrer le parrainage
$query = "INSERT INTO parrainages (electeur_id, candidat_id, code_validation) VALUES (?, ?, ?)";
$stmt = $mysqli->prepare($query);

if ($stmt === false) {
    die("Erreur de requête : " . $mysqli->error);
}

$stmt->bind_param("iii", $electeur_id, $candidat_id, $code_validation);

if ($stmt->execute()) {
    $_SESSION['code_validation'] = $code_validation;
    
    $query_update = "UPDATE contacts SET code_auth = ? WHERE num_electeur = ?";
    $stmt_update = $mysqli->prepare($query_update);
    
    if ($stmt_update === false) {
        die("Erreur de mise à jour : " . $mysqli->error);
    }
    
    $stmt_update->bind_param("ss", $code_validation, $num_electeur);
    $stmt_update->execute();
    $stmt_update->close();
    
    // Envoi du mail
    $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
        'to' => [['email' => $email, 'name' => 'Utilisateur']],
        'sender' => ['email' => 'mayramarem@gmail.com', 'name' => 'Plateforme'],
        'replyTo' => ['email' => 'mayramarem@gmail.com', 'name' => 'Plateforme'],
        'subject' => 'Votre code de validation de parrainage',
        'htmlContent' => "<strong>Bonjour,</strong><br><br>Votre code de validation de parrainage est : <b>$code_validation</b><br><br>Cordialement.",
        'textContent' => "Bonjour,\n\nVotre code de validation de parrainage est : $code_validation\n\nCordialement."
    ]);

    try {
        $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        $message = "Un email avec le code de validation a été envoyé à $email.";
    } catch (Exception $e) {
        $message = "Erreur lors de l'envoi du mail : " . $e->getMessage();
    }
} else {
    $message = "Erreur lors de l'enregistrement du parrainage : " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation du parrainage</title>
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
        <h2>Validation du parrainage</h2>

        <?php if ($message): ?>
            <div class="alert"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="confirmation_parrainage.php">
            <label>Code de validation :</label>
            <input type="text" name="code_validation" class="form-control" required>
            <button type="submit" class="btn-primary">Valider</button>
        </form>
    </div>
</body>
</html>