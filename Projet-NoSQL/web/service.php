<?php
session_start();
require_once './db.php';

date_default_timezone_set('Europe/Paris');

if (!isset($_SESSION["user"])) {
    header("Location: ./index.php");
    exit();
}

if (!isset($_SESSION["user"]) || $_SESSION["source"] !== 'effectif') {
    
    header("Location: ./index.php");
    exit();
}

$userId = $_SESSION["user"];
$userId = pg_escape_string($conn, $userId);

$query = "SELECT * FROM effectif WHERE id = '$userId'";
$result = pg_query($conn, $query);

if (!$result) {
    die("Erreur lors de l'exécution de la requête.");
}

$userData = pg_fetch_assoc($result);

if (!$userData) {
    die("Utilisateur non trouvé.");
}

$userprenom = $userData['prenom'];
$usernom = $userData['nom'];
$usernumero = $userData['numero'];

$currentTime = date('Y-m-d H:i:s');

// Requête pour récupérer toutes les périodes de service de l'utilisateur
$queryServicePeriods = "
    SELECT debutdeservice, findeservice 
    FROM service 
    WHERE effectifid = '$userId'
";
$resultServicePeriods = pg_query($conn, $queryServicePeriods);

if (!$resultServicePeriods) {
    die("Erreur lors de l'exécution de la requête des périodes de service.");
}

// Calcul du temps total de service en secondes
$totalServiceTime = 0;

while ($row = pg_fetch_assoc($resultServicePeriods)) {
    $debutdeservice = strtotime($row['debutdeservice']);
    $findeservice = $row['findeservice'] ? strtotime($row['findeservice']) : time(); // Si le service est en cours, utiliser l'heure actuelle
    $totalServiceTime += ($findeservice - $debutdeservice); // Additionner la durée de chaque période
}


$totalHours = floor($totalServiceTime / 3600);
$totalMinutes = floor(($totalServiceTime % 3600) / 60);
$totalSeconds = $totalServiceTime % 60;

$totalServiceTimeFormatted = sprintf("%02d:%02d:%02d", $totalHours, $totalMinutes, $totalSeconds);

// Requête pour obtenir les effectifs en service actuellement
$query2 = "
    SELECT e.nom, e.prenom, e.numero, s.debutdeservice 
    FROM effectif e
    JOIN service s ON e.id = s.effectifid
    WHERE s.findeservice IS NULL
";

$result2 = pg_query($conn, $query2);

if (!$result2) {
    die("Erreur lors de l'exécution de la requête.");
}

if (isset($_POST['start_service'])) {
    $queryCheckService = "SELECT * FROM service WHERE effectifid = $userId AND findeservice IS NULL";
    $checkServiceResult = pg_query($conn, $queryCheckService);

    if (pg_num_rows($checkServiceResult) > 0) {
        echo "Vous êtes déjà en service.";
    } else {
        $debutdeservice = date('Y-m-d H:i:s');
        $query = "INSERT INTO service (debutdeservice, effectifid) VALUES ('$debutdeservice', $userId)";
        $result = pg_query($conn, $query);
        if ($result) {
            header("Location: service.php");
            exit();
        } else {
            die("Erreur lors de la prise de service.");
        }
    }
}

if (isset($_POST['end_service'])) {
    $queryCheckService = "SELECT * FROM service WHERE effectifid = $userId AND findeservice IS NULL";
    $checkServiceResult = pg_query($conn, $queryCheckService);

    if (pg_num_rows($checkServiceResult) == 0) {
        echo "Vous n'êtes pas actuellement en service.";
    } else {
        $findeservice = date('Y-m-d H:i:s');
        $query = "UPDATE service SET findeservice = '$findeservice' WHERE effectifid = $userId AND findeservice IS NULL";
        $result = pg_query($conn, $query);
        if ($result) {
            header("Location: service.php");
            exit();
        } else {
            die("Erreur lors de la fin de service.");
        }
    }
}

$queryCheckService = "SELECT * FROM service WHERE effectifid = $userId AND findeservice IS NULL";
$checkServiceResult = pg_query($conn, $queryCheckService);
$isInService = pg_num_rows($checkServiceResult) > 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prise de service</title>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <style>
        body {
            color: #ffffff;
            background-color: #292929;
            margin: 0;
        }
        .formContainer {
            width: 80%;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .submitButtonStyle {
            width: 100%;
            height: 45px;
            text-align: center;
            font-weight: bold;
            border-radius: 10px;
            border: solid 2px black;
            background-color: #54e654;
            color: #fff;
            cursor: pointer;
        }
        .submitButtonStyle:hover {
            background-color: #32CD32;
        }
        .submitButtonStyle2 {
            width: 100%;
            height: 45px;
            text-align: center;
            font-weight: bold;
            border-radius: 10px;
            border: solid 2px black;
            background-color: #d90368;
            color: #ffffff;
            cursor: pointer;
        }
        .submitButtonStyle2:hover {
            background-color: #c70260;
        }
        .normalTitleStyle2 {
            font-weight: bold;
            color: #ffffff;
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-align: center;
        }
        .ficheRecap {
            background-color: #3e9d94;
            text-align: center;
            margin: auto;
            padding: 10px 10px 20px 10px;
            border-radius: 10px;
            color: #01161e;
            width: 500px;
        }
        .ficheRecap h4 {
            padding: 10px;
            font-weight: bold;
        }
        .ficheRecap table {
            width: 100%;
        }
        .ficheRecap td {
            padding: 5px;
        }
        .titleSummaryStyle {
            font-weight: bold;
        }
        .tableContainer {
            width: 90%;
            margin: 0 auto;
            margin-top: 20px;
            background-color: #1c1c1c;
            padding: 20px;
            border-radius: 10px;
        }
        .tableContainer table {
            font-size: 18px;
            width: 100%;
            border-collapse: collapse;
            
        }
        .tableContainer th, .tableContainer td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .tableContainer th {
            background-color: #4CAF50;
            color: white;
        }
        .tableContainer tr:hover {
            background-color: #f5f5f5;
        }
        input[name="start_service"], input[name="end_service"] {
            background-color: #54e654;
            color: #fff;
            border: none;
            border-radius: 30px;
            font-size: 1.5rem;
        }
        input[name="end_service"] {
            background-color: #d90368;
        }
        input[name="start_service"]:hover {
            background-color: #32CD32;
        }
        input[name="end_service"]:hover {
            background-color: #c70260;
        }

        .generalContainer {
            display : flex ;
            flex-direction: column;
        }
    </style>
    <script>
        setInterval(function() {
            window.location.reload();
        }, 90000);
    </script>
</head>
    <body style="width: 100%; text-align: center;">
        <?php require './header.php'; ?>

        <h1 style="color: #ffffff; margin: 60px 0px 20px 0px;">PRISE DE SERVICE</h1>
        <div class='generalContainer'>
                <div class="formContainer">
                    <?php if ($isInService) { ?>
                        <form method="post" action="">
                            <input type="submit" name="end_service" value="Terminer le service" class="submitButtonStyle2">
                        </form>
                    <?php } else { ?>
                        <form method="post" action="">
                            <input type="submit" name="start_service" value="Prendre le service" class="submitButtonStyle">
                        </form>
                    <?php } ?>
                </div>
                
                <div class="ficheRecap">
                    <h4>Fiche récapitulative</h4>
                    <table>
                        <tr>
                            <td class="titleSummaryStyle">Prénom :</td>
                            <td><?php echo ucfirst($userprenom); ?></td>
                        </tr>
                        <tr>
                            <td class="titleSummaryStyle">Nom :</td>
                            <td><?php echo ucfirst(strtolower($usernom)); ?></td>
                        </tr>
                        <tr>
                            <td class="titleSummaryStyle">Numéro :</td>
                            <td><?php echo $usernumero; ?></td>
                        </tr>
                        <tr>
                            <td class="titleSummaryStyle">Total service :</td>
                            <td><?php echo $totalServiceTimeFormatted; ?></td>
                        </tr>
                    </table>
                </div>
                
                <div class="tableContainer">
                    <h4 style="text-align: center; color: #ffffff;">Liste des employés en service</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Effectif</th>
                                <th>Numéro</th>
                                <th>Début de service</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = pg_fetch_assoc($result2)) { ?>
                                <tr>
                                    <td><?php echo ucfirst($row['prenom']).' '.ucfirst(strtolower($row['nom'])); ?></td>
                                    <td><?php echo $row['numero']; ?></td>
                                    <td><?php echo $row['debutdeservice']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div> 
        </body>
</html>
