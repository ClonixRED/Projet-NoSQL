<?php
session_start(); // Démarrage de la session
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session
header("Location: ./index.php"); // Redirection vers la page de connexion
exit();
?>
