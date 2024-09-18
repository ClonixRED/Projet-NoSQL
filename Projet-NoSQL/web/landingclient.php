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


$userId = $_SESSION["user"];


$userId = pg_escape_string($conn, $userId);


$query = "SELECT * FROM clients WHERE id = '$userId'";


$result = pg_query($conn, $query);


if (!$result) {
    die("Erreur lors de l'exécution de la requête.");
}


$userData = pg_fetch_assoc($result);

if (!$userData) {
    die("Utilisateur non trouvé.");
}


?>


<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
    <head>
        <title>Menu principal</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <style>
            body {
                color: #ffffff;
                background-color: #292929;
                margin: 0;
            }
            .imagePictureClass {
                margin: 10px;
                border-radius: 5px;
                border: solid 2px;
            }
            .imagePictureClassNoBorder {
                margin: 10px;
            }
            .center {
                text-align: center;
            }
            .underline {
                text-decoration-line: underline;
            }
            
        </style>
        
    </head> 

    <?php include 'headerclient.php'; ?>


    <body>

        <table style="width: 100%; text-align: center; margin: 60px 0px 20px 0px;">
            <tbody>
                <tr>
                    <td style="width: 20%;">&nbsp;</td>
                    <td style="width: 60%; color: #ffffff;">
                        <h1>MENU PRINCIPAL</h1>
                    </td>
                    <td style="width: 20%;">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>   
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <h1 class="p-5">Bonjour <?php echo ucfirst($userData["prenom"]); ?> <?php echo ucfirst($userData["nom"]); ?> !</h1>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>