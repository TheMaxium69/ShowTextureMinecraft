<?php

if (!empty($_GET['pictureName'])){

    $pictureName = $_GET['pictureName'];

} else {

    $pictureName = "skinDefault.png";

}


// Vérifiez si la GD Library est activée
if (!extension_loaded('gd')) {
    echo 'La bibliothèque GD n\'est pas activée.';
    exit;
}

// Chemin vers les images sources
$baseImagePath = $pictureName;
$overlayImagePath = $pictureName;

// Position de départ du rognage de l'image de base
$cropX = 8;
$cropY = 8;
$cropWidth = 16 - $cropX;
$cropHeight = 16 - $cropY;

// Charger l'image de base
$baseImage = imagecreatefrompng($baseImagePath);
if (!$baseImage) {
    echo 'Impossible de charger l\'image de base.';
    exit;
}

// Charger l'image de superposition
$overlayImage = imagecreatefrompng($overlayImagePath);
if (!$overlayImage) {
    echo 'Impossible de charger l\'image de superposition.';
    exit;
}

// Créer une image vide pour l'image rognée de base
$croppedBaseImage = imagecreatetruecolor($cropWidth, $cropHeight);

// Préserver la transparence pour l'image de base
imagealphablending($croppedBaseImage, false);
imagesavealpha($croppedBaseImage, true);
$transparent = imagecolorallocatealpha($croppedBaseImage, 0, 0, 0, 127);
imagefill($croppedBaseImage, 0, 0, $transparent);

// Rogner l'image de base
imagecopyresampled(
    $croppedBaseImage, $baseImage,
    0, 0, $cropX, $cropY,
    $cropWidth, $cropHeight,
    $cropWidth, $cropHeight
);

// Assurez-vous que l'overlay conserve sa transparence
imagealphablending($croppedBaseImage, true);
imagesavealpha($croppedBaseImage, true);

// Position où l'image de superposition sera appliquée
$overlayX = -40; // Changer si nécessaire
$overlayY = -8; // Changer si nécessaire

// Superposition de l'image avec transparence
imagecopy($croppedBaseImage, $overlayImage, $overlayX, $overlayY, 0, 0, imagesx($overlayImage), imagesy($overlayImage));

// Définir l'en-tête HTTP pour afficher l'image
header('Content-Type: image/png');

// Afficher l'image finale
imagepng($croppedBaseImage);

// Libérer la mémoire
imagedestroy($baseImage);
imagedestroy($croppedBaseImage);
imagedestroy($overlayImage);
?>