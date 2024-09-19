<?php

if (!empty($_GET['pictureName'])) {
    $pictureName = $_GET['pictureName'];
} else {
    $pictureName = "skinDefault.png";
}

if (!empty($_GET['scale'])) {
    $scale = $_GET['scale'];
} else {
    $scale = 1;
}

// Vérifiez si la GD Library est activée
if (!extension_loaded('gd')) {
    echo 'La bibliothèque GD n\'est pas activée.';
    exit;
}

// Chemin vers l'image source
$imagePath = $pictureName;

// Charger l'image
$image = imagecreatefrompng($imagePath);
if (!$image) {
    echo 'Impossible de charger l\'image.';
    exit;
}

// Dimensions de l'image
$imageWidth = imagesx($image);
$imageHeight = imagesy($image);

// Dimensions de l'image redimensionnée
$scaleFactor = $scale;
$targetWidth = $imageWidth * $scaleFactor;
$targetHeight = $imageHeight * $scaleFactor;

// Créer l'image redimensionnée
$targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

// Préserver la transparence
imagealphablending($targetImage, false);
imagesavealpha($targetImage, true);
$transparentTarget = imagecolorallocatealpha($targetImage, 0, 0, 0, 127);
imagefill($targetImage, 0, 0, $transparentTarget);

// Redimensionner l'image
imagecopyresampled($targetImage, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $imageWidth, $imageHeight);

// Définir l'en-tête HTTP pour afficher l'image
header('Content-Type: image/png');

// Afficher l'image redimensionnée
imagepng($targetImage);

// Libérer la mémoire
imagedestroy($image);
imagedestroy($targetImage);
?>