<?php
session_start(); // Démarrage de la session
require_once "./db.php";

if (!empty($_POST["mail"]) && !empty($_POST["password"])) {
    // Si les champs mail et password ne sont pas vides
    // Patch XSS
    $mail = htmlspecialchars($_POST["mail"]);
    $password = htmlspecialchars($_POST["password"]);

    // Transformer le mail en minuscule
    $mail = strtolower($mail);

    // Préparer la requête SQL pour récupérer les données de l'utilisateur
    $query = 'SELECT id, mail, numero, password FROM effectif WHERE mail = $1';
    
    // Préparer la requête avec PostgreSQL
    $result = pg_prepare($conn, "check_user", $query);
    
    // Exécuter la requête avec le mail en paramètre
    $result = pg_execute($conn, "check_user", array($mail));
    
    // Récupérer les données de l'utilisateur
    $data = pg_fetch_assoc($result);
    $row = pg_num_rows($result);

    // Si l'utilisateur existe
    if ($row > 0) {
        // Vérification du format de l'email avec filter_var
        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            // Vérification du mot de passe
            if ($password == $data["password"]){
                // Création de la session et redirection vers landing.php
                $_SESSION["user"] = $data["id"];
                header("Location: ./landing.php");
                die();
            } else {
                // Mot de passe incorrect
                header("Location: ./index.php?login_err=password");
                die();
            }
        } else {
            // Email incorrect
            header("Location: ./index.php?login_err=email");
            die();
        }
    } else {
        // Utilisateur non existant
        header("Location: ./index.php?login_err=already");
        die();
    }
} else {
    // Si le formulaire est soumis sans données
    header("Location: ./index.php");
    die();
}
?>
