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

if (!isset($_GET['id'])) {
    die("ID de l'effectif non spécifié.");
}

$effectifId = intval($_GET['id']);


$query = 'SELECT * FROM effectif WHERE id = $1';
$result = pg_prepare($conn, "fetch_effectif", $query);
$result = pg_execute($conn, "fetch_effectif", array($effectifId));


if (!$result) {
    die("Erreur lors de l'exécution de la requête.");
}


$effectifData = pg_fetch_assoc($result);

if (!$effectifData) {
    die("Effectif non trouvé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Effectif</title>
    <style>
        body {
            color: #ffffff;
            background-color: #292929;
            margin: 0;
        }
        .formContainer {
            width: 80%;
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
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="formContainer">
    <div class="normalTitleStyle">Modifier un Effectif</div>
    <form action="editEffectiflogic.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($effectifData['id']); ?>">
        <input type="text" name="prenom" class="textInputStyle" placeholder="Prénom" value="<?php echo htmlspecialchars($effectifData['prenom']); ?>" required>
        <input type="text" name="nom" class="textInputStyle" placeholder="Nom" value="<?php echo htmlspecialchars($effectifData['nom']); ?>" required>
        <input type="text" name="numero" class="textInputStyle" placeholder="Numéro" value="<?php echo htmlspecialchars($effectifData['numero']); ?>" required>
        <input type="email" name="mail" class="textInputStyle" placeholder="Email" value="<?php echo htmlspecialchars($effectifData['mail']); ?>" required>
        <input type="password" name="password" class="textInputStyle" placeholder="Mot de passe" value="<?php echo htmlspecialchars($effectifData['password']); ?>" required>
        <button type="submit" class="submitButtonStyle">Modifier</button>
    </form>
</div>

</body>
</html>
