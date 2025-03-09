<?php
$host = "localhost";
$port = "5432";
$dbname = "quiz_db";
$password = "quiz@2025#forUsers";
$username = "user_connect_db";

try {
    // Création d'une nouvelle connexion PDO
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);

    // Configurer PDO pour lever les exceptions en cas d'erreur
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // En cas d'erreur de connexion, afficher l'erreur
    die("Erreur de connexion à la base de données PostgreSQL : " . $e->getMessage());
}
?>