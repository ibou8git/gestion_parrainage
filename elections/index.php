<?php
//index.php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion des Parrainages</title>
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
            padding: 60px 40px;
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
            font-size: 2.2rem;
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

        .info-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 40px;
            text-align: center;
            font-weight: 300;
            color: #e0e0e0;
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Section usage du site */
        .info-section {
            background-color: rgba(38, 41, 56, 0.8);
            border-radius: 12px;
            padding: 30px 40px;
            margin: 30px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            border-left: 4px solid #008000;
        }
        
        .info-section h3 {
            color: #FFCC00;
            font-size: 1.5rem;
            margin-bottom: 20px;
            font-weight: 600;
            position: relative;
            padding-bottom: 12px;
        }
        
        .info-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, #008000, #FFCC00);
        }

        .steps-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        .step-card {
            flex: 1;
            min-width: 250px;
            background: rgba(30, 33, 48, 0.6);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-top: 3px solid;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }

        .step-card:nth-child(1) {
            border-color: #008000;
        }

        .step-card:nth-child(2) {
            border-color: #FFCC00;
        }

        .step-card:nth-child(3) {
            border-color: #DC143C;
        }

        .step-title {
            font-weight: 600;
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .step-title span {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            margin-right: 10px;
            font-weight: 700;
        }

        .step-desc {
            color: #bbb;
            font-weight: 300;
            line-height: 1.6;
        }

        /* Bouton de parrainage */
        .cta-section {
            text-align: center;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }

        .cta-text {
            color: #e0e0e0;
            font-size: 1.2rem;
            margin-bottom: 25px;
        }

        .btn-parrainer {
            background: linear-gradient(to right, #008000, #FFCC00, #DC143C);
            color: white;
            padding: 16px 36px;
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

        .btn-parrainer:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        }
        
        .btn-parrainer:active {
            transform: translateY(1px);
        }

        .btn-parrainer::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s ease;
        }

        .btn-parrainer:hover::before {
            left: 100%;
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
            <a href="connexion_candidat.php">Candidat</a>
            <a href="identification_electeur.php">Électeur</a>
            <a href="authentification_electeur.php">Parrainages</a>
            <a href="connexion_admin.php">DGE</a>
        </div>
    </div>

    <div class="container">
        <!-- Section principale avec design sombre -->
        <div class="main-content">
            <!-- Titre amélioré -->
            <h1 class="main-title">GESTION DES PARRAINAGES DE CANDIDATURE<br>D'ÉLECTIONS PRÉSIDENTIELLES POUR LE SÉNÉGAL</h1>

            <!-- Texte explicatif -->
            <p class="info-text">
                Les parrainages sont une étape essentielle dans le processus démocratique des élections présidentielles. Ils permettent aux citoyens sénégalais de soutenir activement les candidats de leur choix et de contribuer à la vitalité de notre démocratie.
            </p>

            <!-- Tutoriel sur le site -->
            <div class="info-section">
                <h3>Comment utiliser ce site ?</h3>
                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-title"><span>1</span>Électeurs</div>
                        <p class="step-desc">Connectez-vous avec vos identifiants pour parrainer le candidat de votre choix en toute sécurité.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-title"><span>2</span>Candidats</div>
                        <p class="step-desc">Inscrivez-vous et suivez en temps réel l'évolution de vos parrainages à travers votre tableau de bord.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-title"><span>3</span>Administrateurs</div>
                        <p class="step-desc">Gérez efficacement les listes électorales et définissez les périodes officielles de parrainage.</p>
                    </div>
                </div>
            </div>

            <!-- Bouton pour encourager à parrainer -->
            <div class="cta-section">
                <p class="cta-text">Vous souhaitez soutenir un candidat pour les prochaines élections présidentielles ?</p>
                <a href="identification_electeur.php">
                    <button class="btn-parrainer">Je veux parrainer un candidat</button>
                </a>
            </div>
        </div>
    </div>

    <!-- Script pour Bootstrap (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>