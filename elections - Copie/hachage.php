<?php
$mot_de_passe = 'passer';
$mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);
echo $mot_de_passe_hache;
?>