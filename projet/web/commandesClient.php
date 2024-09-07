<?php
session_start(); 
require_once './db.php';

if (!isset($_SESSION["user"])) {
    header("Location: ./index.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID de client non spécifié.");
}

$client_id = $_GET['id'];

// Requête pour récupérer les informations du client
$client_query = 'SELECT * FROM clients WHERE id = $1';
$client_result = pg_query_params($conn, $client_query, array($client_id));

if (!$client_result || pg_num_rows($client_result) == 0) {
    die("Client non trouvé.");
}

$client = pg_fetch_assoc($client_result);

// Requête pour récupérer les commandes du client
$commandes_query = 'SELECT * FROM commandes WHERE client_id = $1';
$commandes_result = pg_query_params($conn, $commandes_query, array($client_id));

if (!$commandes_result) {
    die("Erreur lors de l'exécution de la requête.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes de <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?></title>
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
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Commandes de <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?></h1>

<?php if (pg_num_rows($commandes_result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Date de commande</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 0;
            while ($row = pg_fetch_assoc($commandes_result)) {
                $rowClass = ($rank % 2 == 0) ? 'rowPair' : 'rowImpair';
                echo "<tr class='{$rowClass}'>";
                echo "<td>" . ($rank + 1) . "</td>";
                echo "<td>" . htmlspecialchars($row['produit']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantite']) . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($row['date_commande'])) . "</td>";
                echo "<td>" . htmlspecialchars($row['statut']) . "</td>";
                echo "</tr>";
                $rank++;
            }
            ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucune commande trouvée pour ce client.</p>
<?php endif; ?>

</body>
</html>
