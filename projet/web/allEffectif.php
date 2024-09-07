<?php
session_start(); 
require_once './db.php';


if (!isset($_SESSION["user"])) {
    
    header("Location: ./index.php");
    exit();
}


$query = 'SELECT * FROM effectif';
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
    <title>Effectif</title>
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
        .rowEffectifMe {
            background-color: #aa1ed3;
        }
        .profilePictureClass {
            width: 100px;
            height: 100px;
            border-radius: 5px;
            border: 2px solid white;
        }
        .editButtonClass, .deleteButtonClass {
            border-radius: 5px;
            padding: 5px 10px;
            margin: 2px 0;
            display: inline-block;
            border: none;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            text-decoration: none; 
            width: 20%; 
            box-sizing: border-box; 
            font-size: 16px;
        }

        .editButtonClass {
            background-color: #e0a800; 
            margin-right: 10px;
        }

        .deleteButtonClass {
            background-color: #dc3545; 
        }

    </style>
    <script>
        function confirmDeletion(id) {
            if (confirm("Voulez-vous vraiment supprimer cet effectif ?")) {
                window.location.href = 'deleteEffectif.php?id=' + id;
            }
        }
    </script>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Liste des effectifs</h1>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Actions</th>
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
            echo "<td>" . htmlspecialchars($row['numero']) . "</td>";
            echo "<td>" . htmlspecialchars($row['mail']) . "</td>";
            echo "<td>";
            echo "<a href='editEffectif.php?id=" . htmlspecialchars($row['id']) . "' class='editButtonClass'>Modifier</a>";
            echo "<button class='deleteButtonClass' onclick='confirmDeletion(" . htmlspecialchars($row['id']) . ")'>Supprimer</button>";
            echo "</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
    </tbody>
</table>

</body>
</html>
