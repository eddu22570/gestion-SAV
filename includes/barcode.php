<?php
require_once __DIR__ . '/barcode39/Barcode39.php';

$text = $_GET['text'] ?? '';
if ($text === '') {
    http_response_code(400);
    exit('Paramètre text manquant');
}

$barcode = new Barcode39();
$barcode->barcode_text = $text;
$barcode->draw_text = false; // true pour afficher le texte sous le code-barres
$barcode->barcode_bar_thick = 3;
$barcode->barcode_bar_thin = 1;
$barcode->barcode_height = 50;

header('Content-Type: image/png');
$barcode->draw(0, 0);
?>
