<?php
session_start();
require_once './db.php'; // Connexion PostgreSQL
require_once 'vendor/autoload.php'; // Charger MongoDB via Composer

use MongoDB\Client;

// Vérifier si l'utilisateur est connecté et autorisé
if (!isset($_SESSION["user"]) || $_SESSION["source"] !== 'effectif') {
    header("Location: ./index.php");
    exit();
}

// Connexion à MongoDB
$mongoClient = new Client("mongodb://mongo_db:27017"); // Assurez-vous que le conteneur MongoDB s'appelle "mongo_db"
$logsCollection = $mongoClient->mydb->commande_logs;

// Récupérer la liste des effectifs
$effectifsQuery = "SELECT id, prenom, nom FROM effectif";
$effectifsResult = pg_query($conn, $effectifsQuery);

// Récupérer la liste des commandes avec détails
$commandesQuery = "SELECT commandes.id, commandes.produit, commandes.quantite, clients.nom AS client_nom, clients.prenom AS client_prenom, commandes.responsable 
                   FROM commandes 
                   JOIN clients ON commandes.client_id = clients.id";
$commandesResult = pg_query($conn, $commandesQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commandeId = $_POST['commande_id'];
    $effectifId = $_POST['effectif_id'];

  // Récupérer l'ancien responsable
$oldResponsableQuery = "SELECT responsable FROM commandes WHERE id = $1";
$oldResponsableResult = pg_query_params($conn, $oldResponsableQuery, array($commandeId));

// Si la requête ne retourne aucune ligne ou que la colonne 'responsable' est NULL, on assigne "Non assigné"
$oldResponsable = pg_fetch_result($oldResponsableResult, 0, 'responsable');

// Vérifier si l'ancien responsable est NULL ou vide et assigner "Non assigné"
if (empty($oldResponsable)) {
    $oldResponsable = "Non assigné";
}


    // Récupérer le nom complet de l'effectif sélectionné
    $effectifQuery = "SELECT prenom, nom FROM effectif WHERE id = $1";
    $effectifResult = pg_query_params($conn, $effectifQuery, array($effectifId));
    $effectif = pg_fetch_assoc($effectifResult);
    $newResponsable = $effectif['prenom'] . ' ' . $effectif['nom'];

    // Récupérer les détails de la commande (produit, quantité, client)
    $commandeDetailsQuery = "SELECT commandes.produit, commandes.quantite, clients.prenom AS client_prenom, clients.nom AS client_nom 
                             FROM commandes 
                             JOIN clients ON commandes.client_id = clients.id 
                             WHERE commandes.id = $1";
    $commandeDetailsResult = pg_query_params($conn, $commandeDetailsQuery, array($commandeId));
    $commandeDetails = pg_fetch_assoc($commandeDetailsResult);
    
    $produit = $commandeDetails['produit'];
    $quantite = $commandeDetails['quantite'];
    $client = $commandeDetails['client_prenom'] . ' ' . $commandeDetails['client_nom'];

    // Mettre à jour la commande avec le nouveau responsable
    $assignQuery = "UPDATE commandes SET responsable = $1 WHERE id = $2";
    pg_query_params($conn, $assignQuery, array($newResponsable, $commandeId));

    // Insérer le changement de responsable dans MongoDB
    $log = [
        'commande_id' => $commandeId,
        'produit' => $produit,
        'quantite' => $quantite,
        'client' => $client,
        'ancien_responsable' => $oldResponsable,
        'nouveau_responsable' => $newResponsable,
        'date_changement' => new MongoDB\BSON\UTCDateTime()
    ];
    $logsCollection->insertOne($log);

    // Rediriger après le changement
    header("Location: allCommande.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner un Responsable à une Commande</title>
    <style>
        body {
            color: #ffffff;
            background-color: #292929;
            margin: 0;
            text-align: center;
        }
        form {
            display: inline-block;
            margin-top: 50px;
            padding: 20px;
            border: 2px solid white;
            border-radius: 10px;
            background-color: #4d342482;
        }
        label, select, button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            text-align: left;
        }
        select, button {
            padding: 10px;
            border: 1px solid #fff;
            border-radius: 5px;
            background-color: #734d33b0;
            color: white;
            font-size: 16px;
            text-align: center;
        }
        button {
            cursor: pointer;
            font-weight: bold;
            background-color: #4d342482;
        }
        button:hover {
            background-color: #734d33;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Assigner un Responsable à une Commande</h1>

<form method="POST" action="assignercommande.php">
    <label for="effectif_id">Choisissez un effectif :</label>
    <select name="effectif_id" required>
        <?php while ($effectif = pg_fetch_assoc($effectifsResult)) { ?>
            <option value="<?= htmlspecialchars($effectif['id']) ?>">
                <?= htmlspecialchars($effectif['prenom'] . ' ' . $effectif['nom']) ?>
            </option>
        <?php } ?>
    </select>

    <label for="commande_id">Choisissez une commande :</label>
    <select name="commande_id" required>
        <?php while ($commande = pg_fetch_assoc($commandesResult)) { ?>
            <option value="<?= htmlspecialchars($commande['id']) ?>">
                Commande de <?= htmlspecialchars($commande['quantite']) ?> <?= htmlspecialchars($commande['produit']) ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">Assigner</button>
</form>

</body>
</html>
