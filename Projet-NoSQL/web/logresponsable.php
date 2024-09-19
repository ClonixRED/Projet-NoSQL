<?php
session_start();
require_once 'vendor/autoload.php'; // Charger MongoDB via Composer

if (!isset($_SESSION["user"])) {
    header("Location: ./index.php");
    exit();
}

if (!isset($_SESSION["user"]) || $_SESSION["source"] !== 'effectif') {
   
    header("Location: ./index.php");
    exit();
}

use MongoDB\Client;

// Connexion à MongoDB
$mongoClient = new Client("mongodb://mongo_db:27017");
$logsCollection = $mongoClient->mydb->commande_logs;

// Récupérer tous les logs
$logs = $logsCollection->find()->toArray();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs des Changements de Responsable</title>
    <style>
        body {
            color: #ffffff;
            background-color: #292929;
            margin: 0;
            text-align: center;
        }
        table {
            margin: 20px auto;
            width: 80%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid white;
            background-color: #4d342482;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Logs des Changements de Responsable</h1>

<table>
    <thead>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Client</th>
            <th>Ancien Responsable</th>
            <th>Nouveau Responsable</th>
            <th>Date de Changement</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log) { ?>
            <tr>
                <td><?= htmlspecialchars($log['produit']) ?></td>
                <td><?= htmlspecialchars($log['quantite']) ?></td>
                <td><?= htmlspecialchars($log['client']) ?></td>
                <td><?= htmlspecialchars($log['ancien_responsable']) ?></td>
                <td><?= htmlspecialchars($log['nouveau_responsable']) ?></td>
                <td><?= htmlspecialchars($log['date_changement']->toDateTime()->format('Y-m-d H:i:s')) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
