<?php
session_start();
require_once "./db.php";

if (!isset($_SESSION["user"])) {
    
    header("Location: ./index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $numero = htmlspecialchars($_POST['numero']);
    $mail = htmlspecialchars($_POST['mail']);
    $password = htmlspecialchars($_POST['password']);
    $passwordhashed = password_hash($password, PASSWORD_BCRYPT, ["cost" => 15]);
   
    $query = 'INSERT INTO effectif (nom, prenom, numero, mail, password) VALUES ($1, $2, $3, $4, $5)';

    
    $result = pg_prepare($conn, "insert_effectif", $query);

    
    $result = pg_execute($conn, "insert_effectif", array($nom, $prenom, $numero, $mail, $passwordhashed));

    if ($result) {
        
        header("Location: ./allEffectif.php?status=success");
        exit();
    } else {
       
        header("Location: ./addEffectif.php?status=error");
        exit();
    }
} else {
    
    header("Location: ./addEffectif.php");
    exit();
}
?>
