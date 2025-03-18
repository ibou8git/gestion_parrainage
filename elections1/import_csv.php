<?php
// import_csv.php

if (isset($_POST['submit'])) {
    // Vérification de l'empreinte SHA256
    $uploadedChecksum = $_POST['checksum'];
    $file = $_FILES['file']['tmp_name'];

    // Calculer le checksum du fichier téléchargé
    $fileChecksum = hash_file('sha256', $file);

    // Vérification de la correspondance des empreintes
    if ($uploadedChecksum !== $fileChecksum) {
        echo "L'empreinte SHA256 ne correspond pas.";
        exit;
    }

    // Connexion à la base de données
    $mysqli = new mysqli('mysql-parrainages.alwaysdata.net', '404600_electeurs', 'P@sser-123', 'parrainages_elections');

    if ($mysqli->connect_error) {
        die("Échec de la connexion à la base de données: " . $mysqli->connect_error);
    }

    // Ouvrir le fichier CSV
    if (($handle = fopen($file, "r")) !== FALSE) {
        // Ignorer l'entête du CSV
        fgetcsv($handle);

        // Préparer la requête d'insertion
        $stmtInsert = $mysqli->prepare("INSERT INTO electeurs_temp (num_electeur, nom, prenom, cin, date_naissance, commune, lieu_de_vote, bureau, checksum) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) < 8) continue; // Vérifier que toutes les colonnes sont présentes

            // Récupération des données dans le bon ordre
            list($num_electeur, $nom, $prenom, $cin, $date_naissance, $commune, $lieu_de_vote, $bureau) = $data;

            // Vérification si l'électeur existe déjà dans electeurs
            $stmtCheck = $mysqli->prepare("SELECT id_electeur FROM electeurs WHERE cin = ? OR num_electeur = ?");
            $stmtCheck->bind_param('ss', $cin, $num_electeur);
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();

            if ($result->num_rows == 0) {
                // Insertion dans electeurs_temp
                $stmtInsert->bind_param('sssssssss', $num_electeur, $nom, $prenom, $cin, $date_naissance, $commune, $lieu_de_vote, $bureau, $fileChecksum);
                $stmtInsert->execute();
            }

            $stmtCheck->close();
        }

        // Transférer les données valides vers electeurs
        $mysqli->query("INSERT INTO electeurs (num_electeur, nom, prenom, cin, date_naissance, commune, lieu_de_vote, bureau)
                        SELECT num_electeur, nom, prenom, cin, date_naissance, commune, lieu_de_vote, bureau FROM electeurs_temp");

        // Vider la table electeurs_temp
        $mysqli->query("DELETE FROM electeurs_temp");

        // Fermeture des ressources
        fclose($handle);
        $stmtInsert->close();
        $mysqli->close();

        echo "Importation CSV réussie.";
        echo '<br><br>';
        echo '<a href="upload.php"><button type="button">Retour à la page d\'importation</button></a>';
    } else {
        echo "Erreur lors de l'ouverture du fichier CSV.";
    }
}
?>
