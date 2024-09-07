<?php
session_start();
require_once "./db.php";

if (!isset($_SESSION["user"])) {
    
    header("Location: ./index.php");
    exit();
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); 

    
    $query = 'DELETE FROM effectif WHERE id = $1';

   
    $result = pg_prepare($conn, "delete_effectif", $query);

    
    $result = pg_execute($conn, "delete_effectif", array($id));

    if ($result) {
        
        header("Location: ./allEffectif.php?status=success");
        exit();
    } else {
        
        header("Location: ./allEffectif.php?status=error");
        exit();
    }
} else {
    
    header("Location: ./allEffectif.php?status=invalid_id");
    exit();
}
?>
