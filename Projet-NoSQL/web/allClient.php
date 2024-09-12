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


$query = 'SELECT * FROM clients';
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
    <title>Liste des Clients</title>
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
        .actionButton {
            border-radius: 5px;
            padding: 5px 10px;
            display: inline-block;
            border: none;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            width: 40%; 
            box-sizing: border-box; 
            font-size: 16px;
        }
        .viewOrdersButton {
            background-color: #17a2b8;
        }
        .viewOrdersButton:hover {
            background-color: #138496;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Liste des Clients</h1>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Adresse</th>
            <th>Date de Création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rank = 0;
        while ($row = pg_fetch_assoc($result)) {
            $rowClass = ($rank % 2 == 0) ? 'rowPair' : 'rowImpair';
            echo "<tr class='{$rowClass}'>";
            echo "<td>" . ($rank + 1) . "</td>";
            echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['numero']) . "</td>";
            echo "<td>" . htmlspecialchars($row['mail']) . "</td>";
            echo "<td>" . htmlspecialchars($row['adresse']) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['date_creation'])) . "</td>";
            echo "<td>";
            echo "<a href='commandesClient.php?id=" . htmlspecialchars($row['id']) . "' class='actionButton viewOrdersButton'>Voir Commandes</a>";
            echo "</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
    </tbody>
</table>

</body>
</html>
