<?php
session_start();

// Définir la durée de session en secondes (exemple : 30 minutes = 1800 secondes)
$session_duration = 600; // 10 minutes
ini_set('session.gc_maxlifetime', $session_duration);

// Régénérer l'ID de session pour sécuriser les données
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > $session_duration) {
    // Si la session a dépassé la durée autorisée, la détruire et rediriger
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Si tout est correct, mettre à jour l'horodatage
$_SESSION['created'] = time();
?>
