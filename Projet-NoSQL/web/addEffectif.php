<?php
session_start(); 
require_once './db.php';


if (!isset($_SESSION["user"])) {
    
    header("Location: ./index.php");
    exit();
}


$query = 'SELECT * FROM effectif';
$result = pg_query($conn, $query);

if (!$result) {
    die("Erreur lors de l'exécution de la requête.");
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Effectif</title>
    <style>
    body {
        color: #ffffff;
        background-color: #292929;
        margin: 0;
        font-family: Arial, sans-serif;
    }
    .formContainer {
        width: 90%;
        max-width: 600px;
        margin: 40px auto;
        padding: 20px;
        border-radius: 10px;
        background-color: #1c1c1c;
    }
    .textInputStyle, .selectInputStyle {
        width: calc(100% - 22px);
        height: 40px;
        text-align: center;
        font-weight: bold;
        border-radius: 10px;
        margin-bottom: 20px;
        border: solid 2px black;
        padding: 0 10px;
    }
    .submitButtonStyle {
        width: 100%;
        height: 45px;
        text-align: center;
        font-weight: bold;
        border-radius: 10px;
        border: solid 2px black;
        background-color: #d90368;
        color: #ffffff;
        cursor: pointer;
    }
    .submitButtonStyle:hover {
        background-color: #c70260;
    }
    .normalTitleStyle {
        font-weight: bold;
        color : #d90368;
        font-size: 1.5rem;
        margin-bottom: 20px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .formContainer {
            width: 95%;
            padding: 15px;
        }
        .textInputStyle {
            margin-bottom: 15px;
            height: 35px;
        }
        .submitButtonStyle {
            height: 40px;
        }
    }

    @media (max-width: 480px) {
        .formContainer {
            padding: 10px;
        }
        .textInputStyle {
            margin-bottom: 10px;
            height: 35px;
        }
        .submitButtonStyle {
            height: 35px;
            font-size: 14px;
        }
        .normalTitleStyle {
            font-size: 1.2rem;
        }
    }
    </style>

</head>
<body>

<?php include 'header.php'; ?>

<div class="formContainer">
    <div class="normalTitleStyle">Ajouter un Effectif</div>
    <form action="addEffectiflogic.php" method="post">
        <input type="text" name="prenom" class="textInputStyle" placeholder="Prénom" required>
        <input type="text" name="nom" class="textInputStyle" placeholder="Nom" required>
        <input type="text" name="numero" class="textInputStyle" placeholder="Numéro" required>
        <input type="email" name="mail" class="textInputStyle" placeholder="Email" required>
        <input type="password" name="password" class="textInputStyle" placeholder="Mot de passe" required>
        <button type="submit" class="submitButtonStyle">Ajouter</button>
    </form>
</div>

</body>
</html>
