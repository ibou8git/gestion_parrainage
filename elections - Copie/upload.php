<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importation des électeurs</title>
    <style>
        /* Style global */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #e0e0e0;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1NiIgaGVpZ2h0PSIxMDAiPgo8cmVjdCB3aWR0aD0iNTYiIGhlaWdodD0iMTAwIiBmaWxsPSIjMkMzRTUwIj48L3JlY3Q+CjxwYXRoIGQ9Ik0yOCA2NkwwIDUwTDAgMTZMMjggMEw1NiAxNkw1NiA1MEwyOCA2NkwyOCAxMDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzFlMjEzMCIgc3Ryb2tlLXdpZHRoPSIyIj48L3BhdGg+CjxwYXRoIGQ9Ik0yOCAwTDI4IDM0TDAgNTBMMCA4NEwyOCAxMDBMNTYgODRMNTYgNTBMMjggMzQiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzFkMjUzMCIgc3Ryb2tlLXdpZHRoPSIyIj48L3BhdGg+Cjwvc3ZnPg==');
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

        /* Bouton de suppression */
        .delete-button {
            background-color: #DC143C;
            color: white;
            padding: 12px 30px;
            font-size: 1rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .delete-button:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .delete-button:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Importer la liste des électeurs</h1>
        <form action="import_csv.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Choisir un fichier CSV :</label>
                <input type="file" name="file" id="file" accept=".csv" required>
            </div>

            <div class="form-group">
                <label for="checksum">Empreinte SHA256 (checksum) :</label>
                <input type="text" name="checksum" id="checksum" required>
            </div>

            <button type="submit" name="submit">Importer</button>
        </form>

        <br>

        <a href="delete_csv.php" onclick="return confirm('Voulez-vous vraiment supprimer le fichier CSV ?');">
            <button class="delete-button" type="button">Supprimer le fichier CSV</button>
        </a>

        <br><br>

        <a href="logout_admin.php" class="btn-back">Déconnexion</a>
    </div>
</body>
</html>