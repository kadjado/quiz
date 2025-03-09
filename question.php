<?php
require_once 'connectdb.php'; // Connexion à la base de données

// Récupérer toutes les questions
$stmt = $db->query("SELECT * FROM questions ORDER BY id ASC");
$stmt2 = $db->query("SELECT reponse FROM questions ORDER BY id ASC");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$reponse = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Retourner les questions en JSON
header('Content-Type: application/json');
echo json_encode($questions);
exit;
?>
