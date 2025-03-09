<?php
session_start();

// Générer une chaîne aléatoire de 6 caractères
$captcha_text = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 6);
$_SESSION['captcha'] = $captcha_text;

// Créer une image
$image = imagecreatetruecolor(150, 50);

// Définition des couleurs
$background_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
$line_color = imagecolorallocate($image, 200, 200, 200);
$dot_color = imagecolorallocate($image, 100, 100, 100);

// Remplir l'arrière-plan
imagefilledrectangle($image, 0, 0, 150, 50, $background_color);

// Ajouter du bruit avec des lignes
for ($i = 0; $i < 5; $i++) {
    imageline($image, rand(0, 150), rand(0, 50), rand(0, 150), rand(0, 50), $line_color);
}

// Ajouter du bruit avec des points
for ($i = 0; $i < 100; $i++) {
    imagesetpixel($image, rand(0, 150), rand(0, 50), $dot_color);
}

// Ajouter le texte du CAPTCHA
$font = __DIR__ . '/arial.ttf'; // Assurez-vous d'avoir une police TTF dans le dossier
imagettftext($image, 23, rand(-10, 10), 20, 35, $text_color, $font, $captcha_text);

// Envoyer l'image
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>

