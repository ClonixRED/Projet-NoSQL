<?php
session_start(); 
require_once './db.php';


if (!isset($_SESSION["user"])) {
    header("Location: ./index.php");
    exit();
}

if (!isset($_SESSION["user"]) || $_SESSION["source"] !== 'effectif') {
    
    header("Location: ./index.php");
    exit();
}

if (!isset($_POST['id']) || !isset($_POST['prenom']) || !isset($_POST['nom']) || !isset($_POST['numero']) || !isset($_POST['mail']) ) {
    die("Données manquantes.");
}

$id = intval($_POST['id']);
$prenom = htmlspecialchars($_POST['prenom']);
$nom = htmlspecialchars($_POST['nom']);
$numero = htmlspecialchars($_POST['numero']);
$mail = htmlspecialchars($_POST['mail']);


$query = 'UPDATE effectif SET prenom = $1, nom = $2, numero = $3, mail = $4 WHERE id = $5';
$result = pg_prepare($conn, "update_effectif", $query);
$result = pg_execute($conn, "update_effectif", array($prenom, $nom, $numero, $mail, $id));


if (!$result) {
    die("Erreur lors de l'exécution de la requête.");
}


header("Location: ./allEffectif.php?update=success");
exit();
?>
