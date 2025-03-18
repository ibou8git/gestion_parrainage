<?php
session_start();

// Connexion à la base de données
$mysqli = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$message = "";

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $num_electeur = $_POST['num_electeur'];
    $cin = $_POST['cin'];

    // Vérifier si l'électeur existe dans la base de données
    $query = "SELECT * FROM electeurs WHERE num_electeur = ? AND cin = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $mysqli->error);
    }

    $stmt->bind_param("ss", $num_electeur, $cin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $electeur = $result->fetch_assoc();
        
        // Récupérer le code d'authentification depuis la table contacts
        $query_code = "SELECT code_auth FROM contacts WHERE num_electeur = ? ORDER BY created_at DESC LIMIT 1";
        $stmt_code = $mysqli->prepare($query_code);
        
        if ($stmt_code === false) {
            die("Erreur de préparation de la requête : " . $mysqli->error);
        }
        
        $stmt_code->bind_param("s", $num_electeur);
        $stmt_code->execute();
        $result_code = $stmt_code->get_result();
        
        if ($result_code->num_rows == 1) {
            $contact = $result_code->fetch_assoc();
            
            // Stocker le code récupéré dans la session
            $_SESSION['code_auth'] = $contact['code_auth'];
            $_SESSION['electeur_id'] = $electeur['id_electeur'];
            
            // Rediriger vers verification_code.php
            header("Location: verification_code.php");
            exit();
        } else {
            $message = "Erreur : Aucune information de contact trouvée pour cet électeur. Veuillez d'abord ajouter vos informations de contact.";
        }
        
        $stmt_code->close();
    } else {
        $message = "Erreur : Les informations fournies ne correspondent à aucun électeur.";
    }

    $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification de l'électeur</title>
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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            margin-top: 50px;
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
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #FFCC00;
        }

        input[type="text"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Authentification de l'électeur</h2>

        <?php if ($message): ?>
            <div class="alert"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label>Numéro de carte d'électeur :</label>
                <input type="text" name="num_electeur" required>
            </div>

            <div>
                <label>Numéro de carte d'identité nationale (CIN) :</label>
                <input type="text" name="cin" required>
            </div>

            <button type="submit">Vérifier</button>
        </form>
    </div>

    <!-- Script pour Bootstrap (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>