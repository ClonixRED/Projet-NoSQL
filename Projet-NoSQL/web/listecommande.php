<?php
session_start(); 
require_once './db.php';

if (!isset($_SESSION["user"])) {
    header("Location: ./index.php");
    exit();
}

if (!isset($_SESSION["user"]) || $_SESSION["source"] !== 'clients') {
    header("Location: ./index.php");
    exit();
}

// Récupérer l'ID du client connecté depuis la session
$clientId = $_SESSION["user"];

// Requête pour sélectionner les commandes du client connecté
$query = '
    SELECT c.id AS client_id, c.nom, c.prenom, co.id AS commande_id, co.produit, co.quantite, co.date_commande, co.statut, co.responsable
    FROM clients c
    JOIN commandes co ON c.id = co.client_id
    WHERE c.id = $1
    ORDER BY co.date_commande DESC';

// Exécuter la requête avec l'ID du client connecté
$result = pg_query_params($conn, $query, array($clientId));

if (!$result) {
    die("Erreur lors de l'exécution de la requête.");
}

// Séparer les commandes en fonction de leur statut
$commandesEnCours = [];
$commandesFinies = [];

while ($row = pg_fetch_assoc($result)) {
    if ($row['statut'] === 'En cours') {
        $commandesEnCours[] = $row;
    } else {
        $commandesFinies[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Commandes</title>
    <style>
        body {
            color: #ffffff;
            background-color: #292929;
            margin: 0;
            text-align: center;
        }
        table {
            width: 100%;
            margin: 60px auto 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 2px solid white;
        }
        th {
            background-color: #4d342482;
        }
        .rowPair {
            background-color: #a37150b0;
        }
        .rowImpair {
            background-color: #734d33b0;
        }
        .statusButton {
            padding: 5px 10px;
            border-radius: 5px;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }
        .statusEnCours {
            color: white;
        }
        .statusFini {
            color: white;
        }
    </style>
</head>
<body>

<?php include 'headerclient.php'; ?>

<h1>Mes commandes</h1>

<h2>Commandes en cours</h2>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Date de Commande</th>
            <th>Statut</th>
            <th>Responsable</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rank = 0;
        foreach ($commandesEnCours as $row) {
            $rowClass = ($rank % 2 == 0) ? 'rowPair' : 'rowImpair';
            $statusClass = 'statusEnCours';
            $toggleStatusText = 'Marquer comme Fini';

            echo "<tr class='{$rowClass}'>";
            echo "<td>" . ($rank + 1) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenom']) . " " . htmlspecialchars($row['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['produit']) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantite']) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['date_commande'])) . "</td>";
            echo "<td><span class='{$statusClass}'>" . htmlspecialchars($row['statut']) . "</span></td>";
            echo "<td>" . htmlspecialchars($row['responsable'] ?: 'Non assigné') . "</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
    </tbody>
</table>

<h2>Commandes finies</h2>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Date de Commande</th>
            <th>Statut</th>
            <th>Responsable</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rank = 0;
        foreach ($commandesFinies as $row) {
            $rowClass = ($rank % 2 == 0) ? 'rowPair' : 'rowImpair';
            $statusClass = 'statusFini';
            $toggleStatusText = 'Revenir à En cours';

            echo "<tr class='{$rowClass}'>";
            echo "<td>" . ($rank + 1) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenom']) . " " . htmlspecialchars($row['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['produit']) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantite']) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['date_commande'])) . "</td>";
            echo "<td><span class='{$statusClass}'>" . htmlspecialchars($row['statut']) . "</span></td>";
            echo "<td>" . htmlspecialchars($row['responsable'] ?: 'Non assigné') . "</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
    </tbody>
</table>

</body>
</html>
