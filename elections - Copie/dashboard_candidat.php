<?php
//dashboard_candidat.php
session_start();

// Connexion à la base de données
$host = "mysql-parrainages.alwaysdata.net";
$user = "404600_electeurs";
$password = "P@sser-123";
$database = "parrainages_elections";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifier si le candidat est connecté
if (!isset($_SESSION['candidat_id'])) {
    header("Location: connexion_candidat.php");
    exit();
}

$candidat_id = $_SESSION['candidat_id'];

// Récupérer les informations du candidat et de l'électeur associé
$sql = "SELECT c.email, c.telephone, c.slogan, c.couleur1, c.couleur2, c.couleur3, c.url_campagne, c.photo,
               e.num_electeur, e.nom, e.prenom, e.cin, e.date_naissance, e.commune, e.lieu_de_vote, e.bureau
        FROM candidats c
        JOIN electeurs e ON c.num_electeur = e.num_electeur
        WHERE c.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $candidat_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si les données sont trouvées
if ($result->num_rows > 0) {
    $candidat = $result->fetch_assoc();
} else {
    echo "Aucun candidat trouvé.";
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $slogan = htmlspecialchars(trim($_POST['slogan']));
    $couleur1 = htmlspecialchars(trim($_POST['couleur1']));
    $couleur2 = htmlspecialchars(trim($_POST['couleur2']));
    $couleur3 = htmlspecialchars(trim($_POST['couleur3']));
    $url_campagne = htmlspecialchars(trim($_POST['url_campagne']));

    // Gestion du téléversement de la photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Récupérer le chemin de l'ancienne photo
        $ancienne_photo = $candidat['photo'];

        // Supprimer l'ancienne photo si elle existe
        if (!empty($ancienne_photo) && file_exists($ancienne_photo)) {
            unlink($ancienne_photo); // Supprimer le fichier
        }

        // Téléverser la nouvelle photo
        $dossier_upload = "uploads/";
        $nom_fichier = basename($_FILES['photo']['name']);
        $chemin_fichier = $dossier_upload . uniqid() . "_" . $nom_fichier;

        // Vérifier le type de fichier
        $type_fichier = strtolower(pathinfo($chemin_fichier, PATHINFO_EXTENSION));
        $types_autorises = ["jpg", "jpeg", "png", "gif"];

        if (in_array($type_fichier, $types_autorises)) {
            // Déplacer le fichier téléversé
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $chemin_fichier)) {
                // Mettre à jour le chemin de la photo dans la base de données
                $sql_update_photo = "UPDATE candidats SET photo = ? WHERE id = ?";
                $stmt_update_photo = $conn->prepare($sql_update_photo);
                $stmt_update_photo->bind_param("si", $chemin_fichier, $candidat_id);

                if (!$stmt_update_photo->execute()) {
                    echo "Erreur lors de la mise à jour de la photo.";
                }
                $stmt_update_photo->close();
            } else {
                echo "Erreur lors du téléversement de la photo.";
            }
        } else {
            echo "Format de fichier non autorisé. Utilisez JPG, JPEG, PNG ou GIF.";
        }
    }

    // Mettre à jour les autres informations du candidat
    $sql_update = "UPDATE candidats SET telephone = ?, slogan = ?, couleur1 = ?, couleur2 = ?, couleur3 = ?, url_campagne = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssi", $telephone, $slogan, $couleur1, $couleur2, $couleur3, $url_campagne, $candidat_id);

    if ($stmt_update->execute()) {
        header("Location: dashboard_candidat.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour des informations.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Candidat</title>
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

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            margin-bottom: 30px;
            overflow: hidden;
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

        /* Style pour la section principale */
        .main-content {
            background-color: #1E2130;
            color: #fff;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .main-content::before {
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

        .main-title {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        /* Style des formulaires */
        form {
            background-color: rgba(38, 41, 56, 0.8);
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            border-left: 4px solid #008000;
        }

        .form-control {
            background-color: rgba(30, 33, 48, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: rgba(46, 49, 65, 0.8);
            border-color: #FFCC00;
            box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25);
            color: white;
        }

        .form-control:disabled {
            background-color: rgba(25, 28, 40, 0.5);
            color: #aaa;
        }

        label {
            color: #FFCC00;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        h4 {
            color: #e0e0e0;
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        h4::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, #008000, #FFCC00);
        }

        /* Style pour la photo du candidat */
        .profile-photo {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        .profile-photo img {
            border: 4px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            object-fit: cover;
            width: 150px;
            height: 150px;
        }

        .profile-photo img:hover {
            transform: scale(1.05);
            border-color: #FFCC00;
        }

        /* Style pour les couleurs du parti */
        .color-picker-container {
            display: flex;
            gap: 10px;
        }

        .form-control-color {
            width: 50px;
            height: 50px;
            padding: 3px;
            border-radius: 8px;
            cursor: pointer;
            background-color: rgba(30, 33, 48, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-control-color::-webkit-color-swatch {
            border-radius: 4px;
            border: none;
        }

        .form-control-color::-moz-color-swatch {
            border-radius: 4px;
            border: none;
        }

        /* Style pour le bouton */
        .btn-primary {
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            color: white;
            padding: 12px 30px;
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
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        }
        
        .btn-primary:active {
            transform: translateY(1px);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-secondary {
            background-color: rgba(38, 41, 56, 0.8);
            color: #e0e0e0;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
        }

        .btn-secondary:hover {
            background-color: rgba(46, 49, 65, 0.9);
            border-color: #FFCC00;
            color: #FFCC00;
        }

        /* Style pour les liens */
        a {
            color: #FFCC00;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        a:hover {
            color: #DC143C;
            text-decoration: underline;
        }

        /* Disposition des champs en 2 colonnes sur les grands écrans */
        @media (min-width: 768px) {
            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }
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
        <div class="main-content">
            <h1 class="main-title">TABLEAU DE BORD DU CANDIDAT</h1>

            <!-- Bouton pour afficher la liste des candidats -->
            <div class="text-center mb-4">
                <a href="liste_candidats.php" class="btn btn-primary">Voir la liste des candidats</a>
            </div>

            <!-- Affichage de la photo du candidat -->
            <div class="profile-photo">
                <?php if (!empty($candidat['photo'])): ?>
                    <img src="<?= htmlspecialchars($candidat['photo']); ?>" alt="Photo du candidat" class="rounded-circle">
                <?php else: ?>
                    <div style="width: 150px; height: 150px; background-color: rgba(30, 33, 48, 0.6); border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; color: #aaa; font-size: 48px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <p class="mt-2 text-muted">Aucune photo disponible</p>
                <?php endif; ?>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <h4>Informations personnelles</h4>
                
                <!-- Informations de l'électeur (non modifiables) -->
                <div class="form-grid">
                    <div class="mb-3">
                        <label>Numéro d'électeur</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidat['num_electeur']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Nom</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidat['nom']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Prénom</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidat['prenom']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label>CIN</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidat['cin']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Date de naissance</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidat['date_naissance']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Commune</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidat['commune']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Lieu de vote</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidat['lieu_de_vote']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Bureau de vote</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($candidat['bureau']); ?>" disabled>
                    </div>
                </div>

                <h4 class="mt-4">Informations de campagne</h4>
                
                <!-- Informations modifiables -->
                <div class="form-grid">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($candidat['email']); ?>">
                    </div>

                    <div class="mb-3">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($candidat['telephone']); ?>">
                    </div>

                    <div class="mb-3">
                        <label>Slogan</label>
                        <input type="text" name="slogan" class="form-control" value="<?= htmlspecialchars($candidat['slogan']); ?>">
                    </div>

                    <div class="mb-3">
                        <label>URL de campagne</label>
                        <input type="url" name="url_campagne" class="form-control" value="<?= htmlspecialchars($candidat['url_campagne']); ?>">
                        <?php if (!empty($candidat['url_campagne'])): ?>
                            <p class="mt-2"><a href="<?= htmlspecialchars($candidat['url_campagne']); ?>" target="_blank">Voir la page de campagne <i class="fas fa-external-link-alt ms-1"></i></a></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Couleurs du parti -->
                <div class="mb-4">
                    <label>Couleurs du parti</label>
                    <div class="color-picker-container">
                        <input type="color" name="couleur1" value="<?= htmlspecialchars($candidat['couleur1']); ?>" class="form-control-color">
                        <input type="color" name="couleur2" value="<?= htmlspecialchars($candidat['couleur2']); ?>" class="form-control-color">
                        <input type="color" name="couleur3" value="<?= htmlspecialchars($candidat['couleur3']); ?>" class="form-control-color">
                    </div>
                    <small class="text-muted">Ces couleurs représenteront votre identité visuelle</small>
                </div>

                <!-- Champ pour téléverser une nouvelle photo -->
                <div class="mb-4">
                    <label>Photo de profil</label>
                    <input type="file" name="photo" class="form-control">
                    <small class="text-muted">Formats acceptés: JPG, JPEG, PNG, GIF - Max: 2Mo</small>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ajout de FontAwesome pour les icônes -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>