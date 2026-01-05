<?php
$inputFile = __DIR__ . '/public/images/bg2.jpg';
$outputFile = __DIR__ . '/public/images/bg2.webp';

if (!file_exists($inputFile)) {
    die("File input tidak ditemukan di: $inputFile");
}

$image = imagecreatefromjpeg($inputFile);
if (!$image) {
    die("Gagal membuka gambar.");
}

// Get original dimensions
$width = imagesx($image);
$height = imagesy($image);

// Optional: Resize if > 1920 width to reduce size further
if ($width > 1920) {
    $newWidth = 1920;
    $newHeight = ($height / $width) * $newWidth;
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    imagedestroy($image);
    $image = $newImage;
    echo "Resized to 1920px width. \n";
}

// Convert to WebP with 60% quality (aggressive compression for background)
if (imagewebp($image, $outputFile, 60)) {
    echo "Sukses konversi ke $outputFile. \n";
    echo "Ukuran file asli: " . round(filesize($inputFile) / 1024, 2) . " KB\n";
    echo "Ukuran file baru: " . round(filesize($outputFile) / 1024, 2) . " KB\n";
    imagedestroy($image);
} else {
    echo "Gagal konversi.\n";
}
