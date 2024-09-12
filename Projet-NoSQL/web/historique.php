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

// Requête pour obtenir l'historique des services
$query = '
    SELECT e.prenom, e.nom, e.numero, e.mail, s.debutdeservice, s.findeservice
    FROM service s
    JOIN effectif e ON s.effectifid = e.id
    ORDER BY s.debutdeservice DESC
';
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
    <title>Historique des Services</title>
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
        .rowEffectifPair {
            background-color: #a37150b0;
        }
        .rowEffectifImpair {
            background-color: #734d33b0;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Historique des Services</h1>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Effectif</th>
            <th>Début de Service</th>
            <th>Fin de Service</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rank = 0;
        
        while ($row = pg_fetch_assoc($result)) {
            $rowClass = ($rank % 2 == 0) ? 'rowEffectifPair' : 'rowEffectifImpair';
            echo "<tr class='{$rowClass}'>";
            echo "<td>" . ($rank + 1) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenom']) . " " . htmlspecialchars($row['nom']) . "</td>";
            echo "<td>" . htmlspecialchars($row['debutdeservice']) . "</td>";
            echo "<td>" . (is_null($row['findeservice']) ? 'En cours' : htmlspecialchars($row['findeservice'])) . "</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
    </tbody>
</table>

</body>
</html>
