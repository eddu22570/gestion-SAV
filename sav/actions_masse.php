<?php
require_once '../includes/auth.php';
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }
require_once '../includes/db.php';

if (!isset($_POST['ids']) || !is_array($_POST['ids']) || !isset($_POST['action'])) {
    header('Location: index.php');
    exit;
}

$db = getDB();
$ids = array_map('intval', $_POST['ids']);

switch ($_POST['action']) {
    case 'changer_statut':
        if (!isset($_POST['nouveau_statut'])) break;
        $stmt = $db->prepare("UPDATE sav SET statut = ? WHERE id IN (" . implode(',', $ids) . ")");
        $stmt->execute([$_POST['nouveau_statut']]);
        break;
    case 'supprimer':
        $stmt = $db->prepare("DELETE FROM sav WHERE id IN (" . implode(',', $ids) . ")");
        $stmt->execute();
        break;
}
header('Location: index.php');
exit;
?>
