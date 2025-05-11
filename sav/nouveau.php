<?php
require_once '../includes/auth.php';
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO sav (client, produit, marque, numero_serie, motif, rma, date_demande, statut, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['client'],
        $_POST['produit'],
        $_POST['marque'],
        $_POST['numero_serie'],
        $_POST['motif'],
        $_POST['rma'],
        date('Y-m-d'),
        'En attente',
        $_SESSION['user_id']
    ]);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nouveau SAV</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h2>Nouveau dossier SAV</h2>
<form method="post">
    <input type="text" name="client" placeholder="Client" required>
    <input type="text" name="produit" placeholder="Produit" required>
    <input type="text" name="marque" placeholder="Marque" required>
    <input type="text" name="numero_serie" placeholder="Numéro de série" required>
    <input type="text" name="motif" placeholder="Motif" required>
    <input type="text" name="rma" placeholder="Numéro RMA (si existant)">
    <button type="submit">Créer</button>
</form>
<a href="index.php">Retour</a>
</body>
</html>
