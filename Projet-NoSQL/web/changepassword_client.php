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
    $client_id = $_POST['client_id'];
    $new_password = trim($_POST['new_password']);

    if (!empty($client_id) && !empty($new_password)) {
        // Hacher le nouveau mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT, ["cost" => 15]);

        // Mettre à jour le mot de passe dans la table 'clients'
        $query = "UPDATE clients SET password = $1 WHERE id = $2";
        $result = pg_query_params($conn, $query, array($hashed_password, $client_id));

        if ($result) {
            $success = "Mot de passe du client changé avec succès.";
        } else {
            $error = "Erreur lors du changement du mot de passe.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

// Récupérer la liste des clients pour le menu déroulant
$clients_query = "SELECT id, nom, prenom FROM clients";
$clients = pg_query($conn, $clients_query);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer Mot de Passe - Client</title>
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

<h1>Changer le Mot de Passe d'un Client</h1>

<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form action="changepassword_client.php" method="POST">
    <select name="client_id" required>
        <option value="" disabled selected>Choisir un client</option>
        <?php while ($client = pg_fetch_assoc($clients)): ?>
            <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></option>
        <?php endwhile; ?>
    </select>

    <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
    <input type="submit" value="Changer Mot de Passe">
</form>

</body>
</html>
