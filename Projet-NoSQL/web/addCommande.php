<?php
session_start(); 
require_once './db.php';

if (!isset($_SESSION["user"])) {
    header("Location: ./index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $mail = trim($_POST['mail']);
    $numero = trim($_POST['numero']);
    $adresse = trim($_POST['adresse']);
    $produit = trim($_POST['produit']);
    $quantite = (int) trim($_POST['quantite']);

    // Vérifier si le client existe déjà
    $clientQuery = "SELECT id FROM clients WHERE mail = $1";
    $clientResult = pg_query_params($conn, $clientQuery, array($mail));
    
    if (pg_num_rows($clientResult) > 0) {
        // Client existe, récupérer son ID
        $client = pg_fetch_assoc($clientResult);
        $client_id = $client['id'];
    } else {
        // Insérer un nouveau client
        $insertClientQuery = "INSERT INTO clients (nom, prenom, mail, numero, adresse) VALUES ($1, $2, $3, $4, $5) RETURNING id";
        $insertClientResult = pg_query_params($conn, $insertClientQuery, array($nom, $prenom, $mail, $numero, $adresse));
        
        if ($insertClientResult) {
            $client = pg_fetch_assoc($insertClientResult);
            $client_id = $client['id'];
        } else {
            $error = "Erreur lors de l'ajout du client.";
        }
    }

    // Insérer la commande si le client est bien défini
    if (isset($client_id)) {
        $insertCommandeQuery = "INSERT INTO commandes (client_id, produit, quantite) VALUES ($1, $2, $3)";
        $insertCommandeResult = pg_query_params($conn, $insertCommandeQuery, array($client_id, $produit, $quantite));
        
        if ($insertCommandeResult) {
            $success = "Commande ajoutée avec succès !";
        } else {
            $error = "Erreur lors de l'ajout de la commande.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Commande</title>
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
        input[type="text"], input[type="email"], input[type="number"] {
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

        p.notice {
            color: #ffca28;
            margin-bottom: 20px;
            font-size: 16px;
        }
        
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Ajouter une Commande</h1>

<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<p class="notice">N'entrez que le mail si le client est existant.</p>

<form action="addCommande.php" method="POST">
    <input type="text" name="nom" placeholder="Nom">
    <input type="text" name="prenom" placeholder="Prénom">
    <input type="email" name="mail" placeholder="Email" required>
    <input type="text" name="numero" placeholder="Numéro de téléphone">
    <input type="text" name="adresse" placeholder="Adresse">
    <input type="text" name="produit" placeholder="Produit" required>
    <input type="number" name="quantite" placeholder="Quantité" required>
    <input type="submit" value="Ajouter Commande">
</form>

</body>
</html>
