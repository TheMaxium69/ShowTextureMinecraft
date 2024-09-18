<?php

if (!empty($_GET['pictureName'])){

    $pictureName = $_GET['pictureName'];

} else {

    $pictureName = 'capeDefault.png';

}

// Vérifiez si la GD Library est activée
if (!extension_loaded('gd')) {
    echo 'La bibliothèque GD n\'est pas activée.';
    exit;
}

// Chemin vers l'image source
$sourceImagePath = $pictureName;

// Position de départ du rognage
$cropX = 1;
$cropY = 1;

// Dimensions du rognage
$cropWidth = 11 - $cropX;
$cropHeight = 17 - $cropY;

// Créez une image à partir du fichier source
$sourceImage = imagecreatefrompng($sourceImagePath);
if (!$sourceImage) {
    echo 'Impossible de charger l\'image.';
    exit;
}

// Créez une image vide avec les dimensions du rognage
$croppedImage = imagecreatetruecolor($cropWidth, $cropHeight);

// Préservez la transparence si l'image source en contient
imagealphablending($croppedImage, false);
imagesavealpha($croppedImage, true);
$transparent = imagecolorallocatealpha($croppedImage, 0, 0, 0, 127);
imagefill($croppedImage, 0, 0, $transparent);

// Rognage de l'image
imagecopyresampled(
    $croppedImage, $sourceImage,
    0, 0, $cropX, $cropY,
    $cropWidth, $cropHeight,
    $cropWidth, $cropHeight
);

// Définissez l'en-tête HTTP pour afficher l'image
header('Content-Type: image/png');

// Affichez l'image rognée
imagepng($croppedImage);

// Libérez la mémoire
imagedestroy($sourceImage);
imagedestroy($croppedImage);
?>