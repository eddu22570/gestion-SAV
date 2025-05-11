<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }
$db = getDB();

// Pour débogage, active l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = intval($_GET['id'] ?? 0);
$sav_id = intval($_GET['sav_id'] ?? 0);

if ($id && $sav_id) {
    $stmt = $db->prepare("SELECT * FROM sav_pieces_jointes WHERE id = ?");
    $stmt->execute([$id]);
    $pj = $stmt->fetch();

    if ($pj) {
        // Supprime le fichier physique
        if (file_exists($pj['chemin'])) {
            if (!unlink($pj['chemin'])) {
                echo "Erreur lors de la suppression du fichier physique : ".htmlspecialchars($pj['chemin']);
                exit;
            }
        }
        // Supprime la ligne en base
        $stmt = $db->prepare("DELETE FROM sav_pieces_jointes WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        echo "Pièce jointe introuvable en base.";
        exit;
    }
} else {
    echo "Paramètres manquants.";
    exit;
}

// Redirection vers la fiche de modification
header("Location: modifier.php?id=$sav_id");
exit;
