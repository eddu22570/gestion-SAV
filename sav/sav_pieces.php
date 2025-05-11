<?php
require_once '../includes/auth.php';
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }
require_once '../includes/db.php';
$db = getDB();

$sav_id = intval($_GET['id'] ?? 0);

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_piece = trim($_POST['nom_piece'] ?? '');
    $quantite = intval($_POST['quantite'] ?? 1);
    $commentaire = trim($_POST['commentaire'] ?? '');

    if ($nom_piece && $quantite > 0) {
        $stmt = $db->prepare("INSERT INTO pieces (sav_id, nom_piece, quantite, commentaire) VALUES (?, ?, ?, ?)");
        $stmt->execute([$sav_id, $nom_piece, $quantite, $commentaire]);
        header("Location: sav_pieces.php?id=$sav_id");
        exit;
    }
}

// Suppression d'une pièce
if (isset($_GET['delete'])) {
    $piece_id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM pieces WHERE id=? AND sav_id=?");
    $stmt->execute([$piece_id, $sav_id]);
    header("Location: sav_pieces.php?id=$sav_id");
    exit;
}

// Récupération des pièces existantes
$stmt = $db->prepare("SELECT * FROM pieces WHERE sav_id = ?");
$stmt->execute([$sav_id]);
$pieces = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des pièces du dossier SAV #<?= $sav_id ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; }
        .container { background: #fff; max-width: 600px; margin: 40px auto; padding: 28px; border-radius: 10px; box-shadow: 0 2px 12px #0001; }
        h2 { color: #1976d2; }
        label { font-weight: bold; color: #1976d2; }
        input, textarea { width: 100%; margin-bottom: 12px; padding: 7px; border: 1px solid #b0c4de; border-radius: 5px; }
        button { background: #1976d2; color: #fff; border: none; border-radius: 5px; padding: 8px 16px; font-weight: 600; cursor: pointer; }
        button:hover { background: #1251a3; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #b0c4de; padding: 6px; }
        th { background: #e3eafc; }
        td { background: #f9fbfe; }
        .delete-btn { color: #fff; background: #d32f2f; border: none; padding: 3px 10px; border-radius: 4px; cursor: pointer; }
        .delete-btn:hover { background: #a31515; }
    </style>
</head>
<body>
<div class="container">
    <h2>Ajouter une pièce au dossier SAV #<?= $sav_id ?></h2>
    <form method="post">
        <label for="nom_piece">Désignation de la pièce</label>
        <input type="text" name="nom_piece" id="nom_piece" required>
        <label for="quantite">Quantité</label>
        <input type="number" name="quantite" id="quantite" min="1" value="1" required>
        <label for="commentaire">Commentaire (optionnel)</label>
        <input type="text" name="commentaire" id="commentaire">
        <button type="submit">Ajouter la pièce</button>
    </form>

    <h3>Pièces déjà ajoutées</h3>
    <?php if (count($pieces)): ?>
        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Quantité</th>
                    <th>Commentaire</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($pieces as $piece): ?>
                <tr>
                    <td><?= htmlspecialchars($piece['nom_piece']) ?></td>
                    <td style="text-align:center;"><?= htmlspecialchars($piece['quantite']) ?></td>
                    <td><?= htmlspecialchars($piece['commentaire']) ?></td>
                    <td>
                        <form method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $sav_id ?>">
                            <button type="submit" name="delete" value="<?= $piece['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer cette pièce ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="color:#888;">Aucune pièce ajoutée pour ce dossier.</div>
    <?php endif; ?>
    <div style="margin-top:24px;">
        <a href="bordereau.php?id=<?= $sav_id ?>" style="color:#1976d2; text-decoration:none; font-weight:bold;">&#8592; Retour au bordereau</a>
    </div>
</div>
</body>
</html>
