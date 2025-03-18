<?php
session_start();

// Vérifier si le numéro d'électeur est présent dans la session
if (!isset($_SESSION['num_electeur'])) {
    header("Location: etape1_enregistrement.php");
    exit();
}

$num_electeur = $_SESSION['num_electeur'];

// Connexion à la base de données
$servername = "mysql-parrainages.alwaysdata.net";
$username = "404600_electeurs";
$password = "P@sser-123";
$dbname = "parrainages_elections";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$message = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $slogan = trim($_POST['slogan']);
    $couleur1 = trim($_POST['couleur1']);
    $couleur2 = trim($_POST['couleur2']);
    $couleur3 = trim($_POST['couleur3']);
    $url_campagne = trim($_POST['url_campagne']);

    // Vérifier que les champs obligatoires sont remplis
    if (empty($email) || empty($mot_de_passe)) {
        $message = "Erreur : Tous les champs obligatoires doivent être remplis.";
    } else {
        // Vérifier si l'email est déjà utilisé par un candidat
        $stmt_check_email = $conn->prepare("SELECT id FROM candidats WHERE email = ?");
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $stmt_check_email->store_result();

        if ($stmt_check_email->num_rows > 0) {
            $message = "Erreur : Cet email est déjà utilisé par un autre candidat.";
        } else {
            // Hachage du mot de passe
            $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

            // Gestion de l'upload de la photo
            $photo_candidat = null;
            if (!empty($_FILES["photo"]["name"])) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true); // Créer le dossier s'il n'existe pas
                }
                $photo_candidat = $target_dir . basename($_FILES["photo"]["name"]);
                $imageFileType = strtolower(pathinfo($photo_candidat, PATHINFO_EXTENSION));

                // Vérifier si le fichier est une image
                $check = getimagesize($_FILES["photo"]["tmp_name"]);
                if ($check === false) {
                    $message = "Le fichier n'est pas une image valide.";
                } elseif ($_FILES["photo"]["size"] > 500000) {
                    $message = "L'image est trop lourde (max 500KB).";
                } elseif (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
                    $message = "Seuls les fichiers JPG, JPEG et PNG sont autorisés.";
                } else {
                    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_candidat)) {
                        $message = "Erreur lors de l'upload de la photo.";
                        $photo_candidat = null;
                    }
                }
            }

            // Insérer le candidat dans la base de données
            $stmt = $conn->prepare("INSERT INTO candidats (email, telephone, mot_de_passe, slogan, couleur1, couleur2, couleur3, url_campagne, photo, num_electeur) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $email, $telephone, $mot_de_passe_hash, $slogan, $couleur1, $couleur2, $couleur3, $url_campagne, $photo_candidat, $num_electeur);

            if ($stmt->execute()) {
                $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                header("Location: connexion_candidat.php");
                exit();
            } else {
                $message = "Erreur lors de l'inscription : " . $stmt->error;
            }
            $stmt->close();
        }
        $stmt_check_email->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étape 2 : Informations complémentaires - Gestion des Parrainages</title>
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
        }

        /* Navbar mise à jour avec la même couleur que le fond bleu foncé */
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

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-left .senegal-flag {
            width: 50px;
            height: 33px;
            margin-right: 15px;
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            overflow: hidden;
            border-radius: 4px;
        }

        /* Conteneur du formulaire */
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            background-color: #1E2130;
            color: #fff;
        }

        /* En-tête du formulaire */
        .form-header {
            background-color: rgba(38, 41, 56, 0.8);
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .form-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
            text-align: center;
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        /* Corps du formulaire */
        .form-body {
            padding: 30px;
            position: relative;
        }

        .form-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgcGF0dGVyblRyYW5zZm9ybT0icm90YXRlKDQ1KSI+PHJlY3Qgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIiBmaWxsPSIjMUIyMDJBIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI3BhdHRlcm4pIiBvcGFjaXR5PSIwLjA4Ii8+PC9zdmc+');
            opacity: 0.3;
            z-index: 0;
        }

        /* Style pour le message d'alerte */
        .alert {
            background-color: rgba(38, 41, 56, 0.8);
            border-left: 4px solid #FFCC00;
            color: #FFFFFF;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        /* Style pour les champs du formulaire */
        .form-label {
            color: #e0e0e0;
            font-weight: 500;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            border-radius: 6px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25);
            color: #FFFFFF;
        }

        .form-control:disabled {
            background-color: rgba(255, 255, 255, 0.05);
            color: #AAAAAA;
        }

        /* Style pour les couleurs */
        .colors-container {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .color-input {
            width: 60px;
            height: 40px;
            border: none;
            padding: 0;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .color-input::-webkit-color-swatch-wrapper {
            padding: 0;
        }

        .color-input::-webkit-color-swatch {
            border: none;
            border-radius: 6px;
        }

        /* Bouton de soumission */
        .btn-submit {
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            color: white;
            padding: 14px 30px;
            font-size: 18px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.5);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
            margin-top: 10px;
            z-index: 1;
        }

        .btn-submit:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        }
        
        .btn-submit:active {
            transform: translateY(1px);
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s ease;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        /* Groupes de formulaire */
        .form-group {
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        /* Style spécial pour le champ file */
        .form-control[type="file"] {
            padding: 10px;
        }

        .form-control[type="file"]::file-selector-button {
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            cursor: pointer;
            margin-right: 15px;
            transition: all 0.3s;
        }

        .form-control[type="file"]::file-selector-button:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        /* Footer du formulaire */
        .form-footer {
            padding: 20px 30px;
            background-color: rgba(30, 33, 48, 0.8);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Barre de navigation avec la même couleur que le fond principal -->
    <div class="navbar">
        <!-- Partie gauche : Drapeau du Sénégal -->
        <div class="navbar-left">
            <!-- Drapeau du Sénégal en SVG avec étoile correcte -->
            <div class="senegal-flag">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600">
                    <rect width="300" height="600" fill="#00853f"/>
                    <rect x="300" width="300" height="600" fill="#fdef42"/>
                    <rect x="600" width="300" height="600" fill="#e31b23"/>
                    <!-- Étoile à 5 branches au lieu du triangle -->
                    <path d="M450,200 L472.7,273.2 L550,273.2 L486.6,317.6 L509.3,390.8 L450,346.4 L390.7,390.8 L413.4,317.6 L350,273.2 L427.3,273.2 Z" fill="#00853f"/>
                </svg>
            </div>
            <span style="color: white; font-weight: 600; letter-spacing: 0.5px;">Parrainages Sénégal</span>
        </div>

        <!-- Partie droite : Liens de navigation -->
        <div class="navbar-right">
            <a href="authentification_electeur.php">Parrainages</a>
            <a href="connexion_admin.php">DGE</a>
            <a href="connexion_candidat.php">Candidat</a>
            <a href="identification_electeur.php">Électeur</a>
        </div>
    </div>

    <div class="container">
        <div class="form-header">
            <h2>Étape 2 : Informations complémentaires</h2>
        </div>

        <div class="form-body">
            <?php if ($message): ?>
                <div class="alert"><?= htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Numéro d'électeur :</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($num_electeur); ?>" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Email :</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Numéro de téléphone :</label>
                    <input type="text" class="form-control" name="telephone">
                </div>

                <div class="form-group">
                    <label class="form-label">Mot de passe :</label>
                    <input type="password" class="form-control" name="mot_de_passe" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Slogan :</label>
                    <input type="text" class="form-control" name="slogan">
                </div>

                <div class="form-group">
                    <label class="form-label">Couleurs du parti :</label>
                    <div class="colors-container">
                        <input type="color" class="color-input" name="couleur1" value="#008000">
                        <input type="color" class="color-input" name="couleur2" value="#FFCC00">
                        <input type="color" class="color-input" name="couleur3" value="#DC143C">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">URL de campagne :</label>
                    <input type="url" class="form-control" name="url_campagne">
                </div>

                <div class="form-group">
                    <label class="form-label">Photo :</label>
                    <input type="file" class="form-control" name="photo" accept="image/*">
                </div>

                <button type="submit" class="btn-submit w-100">S'inscrire</button>
            </form>
        </div>
    </div>

    <!-- Script pour Bootstrap (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>