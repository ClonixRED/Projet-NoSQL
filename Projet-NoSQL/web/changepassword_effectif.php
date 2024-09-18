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

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $effectif_id = $_POST['effectif_id'];
    $new_password = trim($_POST['new_password']);

    if (!empty($effectif_id) && !empty($new_password)) {
        // Hacher le nouveau mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT, ["cost" => 15]);

        // Mettre à jour le mot de passe dans la table 'effectif'
        $query = "UPDATE effectif SET password = $1 WHERE id = $2";
        $result = pg_query_params($conn, $query, array($hashed_password, $effectif_id));

        if ($result) {
            $success = "Mot de passe de l'effectif changé avec succès.";
        } else {
            $error = "Erreur lors du changement du mot de passe.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

// Récupérer la liste des effectifs pour le menu déroulant
$effectif_query = "SELECT id, nom, prenom FROM effectif";
$effectifs = pg_query($conn, $effectif_query);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer Mot de Passe - Effectif</title>
    <style>
        body {
            color: #ffffff;
            background-color: #292929;
            margin: 0;
            text-align: center;
        }
        form {
            width: 80%;
            max-width: 600px;
            margin: 60px auto;
            padding: 20px;
            background-color: #333;
            border-radius: 10px;
        }
        select, input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 2px solid white;
            border-radius: 5px;
            background-color: #444;
            color: white;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .error {
            color: #dc3545;
            margin-bottom: 10px;
        }
        .success {
            color: #28a745;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Changer le Mot de Passe d'un Effectif</h1>

<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form action="changepassword_effectif.php" method="POST">
    <select name="effectif_id" required>
        <option value="" disabled selected>Choisir un effectif</option>
        <?php while ($effectif = pg_fetch_assoc($effectifs)): ?>
            <option value="<?= $effectif['id'] ?>"><?= htmlspecialchars($effectif['prenom'] . ' ' . $effectif['nom']) ?></option>
        <?php endwhile; ?>
    </select>

    <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
    <input type="submit" value="Changer Mot de Passe">
</form>

</body>
</html>
