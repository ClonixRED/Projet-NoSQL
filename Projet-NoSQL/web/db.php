<?php
$host = 'db'; // Nom du service PostgreSQL défini dans docker-compose.yml
$port = '5432';
$dbname = 'mydb';
$user = 'user';
$password = 'password';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Erreur de connexion à la base de données.");
} else {
    // echo "Connexion réussie à la base de données PostgreSQL.";
}
?>
