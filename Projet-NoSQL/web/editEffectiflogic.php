<?php
session_start(); 
require_once './db.php';


if (!isset($_SESSION["user"])) {
    header("Location: ./index.php");
    exit();
}


if (!isset($_POST['id']) || !isset($_POST['prenom']) || !isset($_POST['nom']) || !isset($_POST['numero']) || !isset($_POST['mail']) || !isset($_POST['password'])) {
    die("Données manquantes.");
}

$id = intval($_POST['id']);
$prenom = htmlspecialchars($_POST['prenom']);
$nom = htmlspecialchars($_POST['nom']);
$numero = htmlspecialchars($_POST['numero']);
$mail = htmlspecialchars($_POST['mail']);
$password = htmlspecialchars($_POST['password']);


$query = 'UPDATE effectif SET prenom = $1, nom = $2, numero = $3, mail = $4, password = $5 WHERE id = $6';
$result = pg_prepare($conn, "update_effectif", $query);
$result = pg_execute($conn, "update_effectif", array($prenom, $nom, $numero, $mail, $password, $id));


if (!$result) {
    die("Erreur lors de l'exécution de la requête.");
}


header("Location: ./allEffectif.php?update=success");
exit();
?>
