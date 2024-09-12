<?php
session_start(); // Démarrage de la session
require_once "./db.php";

if (!empty($_POST["mail"]) && !empty($_POST["password"])) {
    // Si les champs mail et password ne sont pas vides
    $mail = htmlspecialchars($_POST["mail"]);
    $password = htmlspecialchars($_POST["password"]);

    // Transformer le mail en minuscule
    $mail = strtolower($mail);

    // Préparer la requête SQL pour récupérer les données de l'utilisateur
    $query = 'SELECT \'effectif\' AS source, id, mail, password
              FROM effectif
              WHERE mail = $1

              UNION ALL

              SELECT \'clients\' AS source, id, mail, password
              FROM clients
              WHERE mail = $1';

    // Préparer la requête avec PostgreSQL
    $result = pg_prepare($conn, "check_user", $query);

    if (!$result) {
        die("Erreur lors de la préparation de la requête.");
    }

    // Exécuter la requête avec le mail en paramètre
    $result = pg_execute($conn, "check_user", array($mail));

    if (!$result) {
        die("Erreur lors de l'exécution de la requête.");
    }
    
    // Récupérer les données de l'utilisateur
    $data = pg_fetch_assoc($result);

    if ($data) {
        // Vérification du format de l'email avec filter_var
        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            // Vérification du mot de passe
            if ($password == $data["password"]) {
                // Vérifier que la clé 'id' existe dans le tableau
                if (isset($data["id"])) {
                    // Stocker la source de l'utilisateur dans la session
                    $_SESSION["user"] = $data["id"];
                    $_SESSION["source"] = $data["source"];
                    
                    // Redirection en fonction de la source
                    if ($_SESSION["source"] == 'clients') {
                        header("Location: ./landingclient.php");
                    } else {
                        header("Location: ./landing.php");
                    }
                    die();
                } else {
                    // Clé 'id' manquante
                    header("Location: ./index.php?login_err=unknown");
                    die();
                }
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
