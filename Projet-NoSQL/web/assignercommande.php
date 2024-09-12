<?php
session_start();
require_once './db.php';

if (!isset($_SESSION["user"])) {
    header("Location: ./index.php");
    exit();
}

// Récupérer la liste des effectifs
$effectifsQuery = "SELECT id, prenom, nom FROM effectif";
$effectifsResult = pg_query($conn, $effectifsQuery);

// Récupérer la liste des commandes
$commandesQuery = "SELECT id, produit, quantite FROM commandes";
$commandesResult = pg_query($conn, $commandesQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commandeId = $_POST['commande_id'];
    $effectifId = $_POST['effectif_id'];

    // Récupérer le nom complet de l'effectif sélectionné
    $effectifQuery = "SELECT prenom, nom FROM effectif WHERE id = $1";
    $effectifResult = pg_query_params($conn, $effectifQuery, array($effectifId));
    $effectif = pg_fetch_assoc($effectifResult);
    $responsable = $effectif['prenom'] . ' ' . $effectif['nom'];

    // Mettre à jour la commande avec le responsable
    $assignQuery = "UPDATE commandes SET responsable = $1 WHERE id = $2";
    pg_query_params($conn, $assignQuery, array($responsable, $commandeId));

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
