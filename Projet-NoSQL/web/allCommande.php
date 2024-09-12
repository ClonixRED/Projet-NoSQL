<?php
session_start(); 
require_once './db.php';

if (!isset($_SESSION["user"])) {
    header("Location: ./index.php");
    exit();
}

// Mettre à jour le statut de la commande si un changement est demandé
if (isset($_GET['toggle_status']) && isset($_GET['commande_id'])) {
    $commande_id = $_GET['commande_id'];
    $current_status = $_GET['current_status'];

    // Changer le statut en fonction de son état actuel
    $new_status = ($current_status === 'En cours') ? 'Fini' : 'En cours';
    $updateQuery = "UPDATE commandes SET statut = $1 WHERE id = $2";
    pg_query_params($conn, $updateQuery, array($new_status, $commande_id));

    // Rediriger vers la page actuelle pour éviter la soumission du formulaire en rafraîchissant
    header("Location: allCommande.php");
    exit();
}

// Requête pour sélectionner toutes les commandes
$query = '
    SELECT c.id AS client_id, c.nom, c.prenom, co.id AS commande_id, co.produit, co.quantite, co.date_commande, co.statut, co.responsable
    FROM clients c
    JOIN commandes co ON c.id = co.client_id
    ORDER BY co.date_commande DESC';

$result = pg_query($conn, $query);

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

<?php include 'header.php'; ?>

<h1>Liste des Commandes</h1>

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
            <th>Action</th>
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
            echo "<td>";
            echo "<a href='allCommande.php?toggle_status=1&commande_id=" . htmlspecialchars($row['commande_id']) . "&current_status=" . htmlspecialchars($row['statut']) . "' class='statusButton " . $statusClass . "'>" . $toggleStatusText . "</a>";
            echo "</td>";
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
            <th>Action</th>
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
            echo "<td>";
            echo "<a href='allCommande.php?toggle_status=1&commande_id=" . htmlspecialchars($row['commande_id']) . "&current_status=" . htmlspecialchars($row['statut']) . "' class='statusButton " . $statusClass . "'>" . $toggleStatusText . "</a>";
            echo "</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
    </tbody>
</table>

</body>
</html>
