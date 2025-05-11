<?php
require_once '../includes/auth.php';
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }
require_once '../includes/db.php';
$db = getDB();

$id = intval($_GET['id'] ?? 0);

// Récupérer le dossier SAV
$stmt = $db->prepare("SELECT * FROM sav WHERE id = ?");
$stmt->execute([$id]);
$dossier = $stmt->fetch();
if (!$dossier) {
    echo "Dossier SAV introuvable.";
    exit;
}

// Traitement de la modification du dossier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_sav'])) {
    $client = $_POST['client'] ?? '';
    $produit = $_POST['produit'] ?? '';
    $marque = $_POST['marque'] ?? '';
    $numero_serie = $_POST['numero_serie'] ?? '';
    $motif = $_POST['motif'] ?? '';
    $rma = $_POST['rma'] ?? '';
    $date_demande = $_POST['date_demande'] ?? '';
    $statut = $_POST['statut'] ?? '';

    $stmt = $db->prepare("UPDATE sav SET client=?, produit=?, marque=?, numero_serie=?, motif=?, rma=?, date_demande=?, statut=? WHERE id=?");
    $stmt->execute([$client, $produit, $marque, $numero_serie, $motif, $rma, $date_demande, $statut, $id]);
    header("Location: modifier.php?id=$id");
    exit;
}

// Traitement de l'upload de pièce jointe
if (isset($_POST['ajouter_piece']) && isset($_FILES['piece_jointe']) && $_FILES['piece_jointe']['error'] == UPLOAD_ERR_OK) {
    $nom = basename($_FILES['piece_jointe']['name']);
    $upload_dir = '../uploads/sav/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $chemin = $upload_dir . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/','_', $nom);

    // Optionnel : Limiter la taille et le type de fichier
    $taille_max = 5 * 1024 * 1024; // 5 Mo
    $types_autorises = ['pdf','jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($nom, PATHINFO_EXTENSION));
    if ($_FILES['piece_jointe']['size'] > $taille_max) {
        $msg = "Fichier trop volumineux (max 5 Mo).";
    } elseif (!in_array($ext, $types_autorises)) {
        $msg = "Type de fichier non autorisé.";
    } elseif (move_uploaded_file($_FILES['piece_jointe']['tmp_name'], $chemin)) {
        $stmt = $db->prepare("INSERT INTO sav_pieces_jointes (sav_id, nom_fichier, chemin) VALUES (?, ?, ?)");
        $stmt->execute([$id, $nom, $chemin]);
        header("Location: modifier.php?id=$id");
        exit;
    } else {
        $msg = "Erreur lors de l'upload du fichier.";
    }
}

// Traitement de l'ajout de commentaire
if (isset($_POST['ajouter_commentaire']) && !empty($_POST['commentaire'])) {
    $auteur = $_SESSION['username'] ?? 'Utilisateur'; // adapte selon ta gestion d'utilisateurs
    $commentaire = trim($_POST['commentaire']);
    $stmt = $db->prepare("INSERT INTO sav_commentaires (sav_id, auteur, commentaire) VALUES (?, ?, ?)");
    $stmt->execute([$id, $auteur, $commentaire]);
    header("Location: modifier.php?id=$id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier dossier SAV</title>
    <style>
    body { background: #f8fafc; font-family: sans-serif; }
    .form-bloc { background: #fff; border-radius: 8px; padding: 24px; max-width: 700px; margin: 30px auto 18px auto; box-shadow: 0 2px 12px #0001; }
    label { display: block; margin-top: 14px; color: #2a7ae2; font-weight: 600; }
    input, select, textarea { width: 100%; padding: 7px 8px; border: 1px solid #cfd8dc; border-radius: 5px; font-size: 1em; background: #f1f5f8; }
    button { background: #2a7ae2; color: #fff; border: none; border-radius: 5px; padding: 8px 18px; font-weight: 600; cursor: pointer; font-size: 1em; margin-top: 18px;}
    button:hover { background: #1558b0; }
    .pj-list { margin: 10px 0 20px 0; padding-left: 18px; }
    .pj-list li { margin-bottom: 6px; }
    .pj-list a { color: #2a7ae2; }
    .pj-list .suppr { color: #f87171; margin-left: 8px; text-decoration: none; }
    .msg { color: red; margin-bottom: 10px; }
    textarea { width: 100%; padding: 7px 8px; border: 1px solid #cfd8dc; border-radius: 5px; font-size: 1em; background: #f1f5f8; margin-bottom: 8px;}
    .bordereau-btn { float:right; margin-bottom:15px; background:#1976d2; color:#fff; padding:8px 14px; border-radius:5px; text-decoration:none; font-weight:600;}
    .bordereau-btn:hover { background:#1558b0; }
    </style>
</head>
<body>
<div class="form-bloc">
    <a href="bordereau.php?id=<?= $dossier['id'] ?>" target="_blank" class="bordereau-btn">📄 Imprimer le bordereau d'envoi</a>
    <h2>Modifier le dossier SAV #<?= $dossier['id'] ?></h2>
    <?php if (!empty($msg)) echo '<div class="msg">'.htmlspecialchars($msg).'</div>'; ?>
    <form method="post">
        <label>Client</label>
        <input type="text" name="client" value="<?= htmlspecialchars($dossier['client']) ?>" required>
        <label>Produit</label>
        <input type="text" name="produit" value="<?= htmlspecialchars($dossier['produit']) ?>" required>
        <label>Marque</label>
        <input type="text" name="marque" value="<?= htmlspecialchars($dossier['marque']) ?>" required>
        <label>Numéro de série</label>
        <input type="text" name="numero_serie" value="<?= htmlspecialchars($dossier['numero_serie']) ?>">
        <label>Motif</label>
        <input type="text" name="motif" value="<?= htmlspecialchars($dossier['motif']) ?>">
        <label>RMA</label>
        <input type="text" name="rma" value="<?= htmlspecialchars($dossier['rma']) ?>">
        <label>Date de demande</label>
        <input type="date" name="date_demande" value="<?= htmlspecialchars($dossier['date_demande']) ?>">
        <label>Statut</label>
        <select name="statut">
            <option value="En attente" <?= $dossier['statut']=='En attente'?'selected':'' ?>>En attente</option>
            <option value="En cours" <?= $dossier['statut']=='En cours'?'selected':'' ?>>En cours</option>
            <option value="Terminé" <?= $dossier['statut']=='Terminé'?'selected':'' ?>>Terminé</option>
            <option value="Renvoyé" <?= $dossier['statut']=='Renvoyé'?'selected':'' ?>>Renvoyé</option>
        </select>
        <button type="submit" name="modifier_sav">Enregistrer les modifications</button>
    </form>

    <h3>Pièces jointes</h3>
    <form method="post" enctype="multipart/form-data" style="margin-bottom:12px;">
        <input type="file" name="piece_jointe" required>
        <button type="submit" name="ajouter_piece">Ajouter</button>
    </form>
    <ul class="pj-list">
    <?php
    $stmt = $db->prepare("SELECT * FROM sav_pieces_jointes WHERE sav_id = ?");
    $stmt->execute([$dossier['id']]);
    while ($pj = $stmt->fetch()) {
        echo '<li>
            <a href="'.htmlspecialchars($pj['chemin']).'" target="_blank">'.htmlspecialchars($pj['nom_fichier']).'</a>
            <a class="suppr" href="supprimer_piece.php?id='.$pj['id'].'&sav_id='.$dossier['id'].'" onclick="return confirm(\'Supprimer cette pièce jointe ?\')">🗑️</a>
        </li>';
    }
    ?>
    </ul>

    <h3>Commentaires internes</h3>
    <form method="post" style="margin-bottom:12px;">
        <textarea name="commentaire" rows="3" placeholder="Ajouter un commentaire interne..." required></textarea>
        <button type="submit" name="ajouter_commentaire">Ajouter</button>
    </form>
    <ul style="list-style: none; padding: 0;">
    <?php
    $stmt = $db->prepare("SELECT * FROM sav_commentaires WHERE sav_id = ? ORDER BY date_ajout DESC");
    $stmt->execute([$dossier['id']]);
    while ($com = $stmt->fetch()) {
        echo '<li style="margin-bottom:10px;">
            <div style="color:#2a7ae2;font-weight:bold;">'.htmlspecialchars($com['auteur']).'</div>
            <div style="background:#f1f5f8;padding:8px;border-radius:5px;">'.nl2br(htmlspecialchars($com['commentaire'])).'</div>
            <div style="font-size:0.9em;color:#888;">'.htmlspecialchars($com['date_ajout']).'</div>
        </li>';
    }
    ?>
    </ul>

    <a href="sav_pieces.php?id=<?= $dossier['id'] ?>" style="background:#1976d2; color:#fff; padding:7px 14px; border-radius:5px; text-decoration:none; font-weight:bold;">Gérer les pièces envoyées</a>
    <a href="index.php" style="color:#2a7ae2;">&#8592; Retour à la liste</a>
</div>
</body>
</html>
