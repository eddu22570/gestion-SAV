<?php
require_once '../includes/auth.php';
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }
require_once '../includes/db.php';
$db = getDB();

$id = intval($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM sav WHERE id = ?");
$stmt->execute([$id]);
$dossier = $stmt->fetch();
if (!$dossier) die("Dossier SAV introuvable.");

$stmt = $db->query("SELECT * FROM entite LIMIT 1");
$entite = $stmt->fetch();

// Récupération des pièces envoyées pour ce dossier SAV
$stmt = $db->prepare("SELECT * FROM pieces WHERE sav_id = ?");
$stmt->execute([$dossier['id']]);
$pieces = $stmt->fetchAll();

$fournisseur = [];
if (!empty($dossier['fournisseur_id'])) {
    $stmt = $db->prepare("SELECT * FROM fournisseurs WHERE id = ?");
    $stmt->execute([$dossier['fournisseur_id']]);
    $fournisseur = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bordereau d'envoi SAV #<?= $dossier['id'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; }
        .bordereau { background: #fff; max-width: 750px; margin: 40px auto; padding: 32px; border-radius: 12px; box-shadow: 0 2px 12px #0001; }
        .entite-bloc { display: flex; align-items: center; gap: 18px; margin-bottom: 18px; }
        .entite-bloc img { max-width: 90px; max-height: 60px; }
        .entite-info { font-size: 1.08em; }
        h2 { text-align: center; color: #1976d2; margin-top: 0; }
        .section { border-top: 1px solid #e0e0e0; padding-top: 20px; margin-top: 20px; }
        .info { margin-bottom: 13px; }
        .label { font-weight: bold; color: #1976d2; width: 180px; display: inline-block; vertical-align: top; }
        .barcode { margin-top: 5px; margin-bottom: 8px; }
        .footer { margin-top: 35px; text-align: center; font-size: 0.97em; color: #888; }
        .print-btn { float: right; margin-bottom: 18px; background: #1976d2; color: #fff; border: none; border-radius: 5px; padding: 8px 16px; font-weight: 600; cursor: pointer; font-size: 1em;}
        .print-btn:hover { background: #1251a3; }
        table.pieces { width:100%; border-collapse:collapse; margin-bottom:10px; }
        table.pieces th, table.pieces td { border:1px solid #b0c4de; padding:6px; }
        table.pieces th { background:#e3eafc; }
        table.pieces td { background:#f9fbfe; }
        @media print {
            .print-btn { display: none; }
            body { background: none; }
            .bordereau { box-shadow: none; }
        }
    </style>
</head>
<body>
<div class="bordereau">
    <button class="print-btn" onclick="window.print()">🖨️ Imprimer</button>
    <!-- Bloc entité expéditeur -->
    <?php if ($entite): ?>
        <div class="entite-bloc">
            <?php if ($entite['logo']): ?>
                <img src="<?= htmlspecialchars($entite['logo']) ?>" alt="Logo">
            <?php endif; ?>
            <div class="entite-info">
                <div style="font-size:1.2em; font-weight:bold; color:#1976d2;"><?= htmlspecialchars($entite['nom']) ?></div>
                <div><?= nl2br(htmlspecialchars($entite['adresse'])) ?></div>
                <div><?= htmlspecialchars($entite['telephone']) ?> | <?= htmlspecialchars($entite['email']) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <h2>Bordereau d'envoi SAV #<?= $dossier['id'] ?></h2>

    <!-- Section client -->
    <div class="section">
        <div class="info"><span class="label">Client :</span> <?= htmlspecialchars($dossier['client']) ?></div>
        <?php if (!empty($dossier['adresse_client'])): ?>
            <div class="info"><span class="label">Adresse client :</span> <?= nl2br(htmlspecialchars($dossier['adresse_client'])) ?></div>
        <?php endif; ?>
        <?php if (!empty($dossier['tel_client'])): ?>
            <div class="info"><span class="label">Téléphone client :</span> <?= htmlspecialchars($dossier['tel_client']) ?></div>
        <?php endif; ?>
        <?php if (!empty($dossier['email_client'])): ?>
            <div class="info"><span class="label">Email client :</span> <?= htmlspecialchars($dossier['email_client']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Section produit -->
    <div class="section">
        <div class="info"><span class="label">Produit :</span> <?= htmlspecialchars($dossier['produit']) ?></div>
        <div class="info"><span class="label">Marque :</span> <?= htmlspecialchars($dossier['marque']) ?></div>
        <div class="info">
            <span class="label">Numéro de série :</span>
            <?= htmlspecialchars($dossier['numero_serie']) ?><br>
            <?php if (!empty($dossier['numero_serie'])): ?>
                <img class="barcode" src="../includes/barcode.php?text=<?= urlencode($dossier['numero_serie']) ?>" alt="Code-barres numéro de série" style="max-width:300px;">
            <?php endif; ?>
        </div>
        <div class="info"><span class="label">Motif :</span> <?= htmlspecialchars($dossier['motif']) ?></div>
        <div class="info"><span class="label">Date demande :</span> <?= htmlspecialchars(date('d/m/Y', strtotime($dossier['date_demande']))) ?></div>
    </div>

    <!-- Section pièces envoyées -->
    <div class="section">
        <div style="margin-bottom:10px; color:#1976d2; font-weight:bold;">
            Liste des pièces envoyées
        </div>
        <?php if (count($pieces)): ?>
            <table class="pieces">
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Quantité</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($pieces as $piece): ?>
                    <tr>
                        <td><?= htmlspecialchars($piece['nom_piece']) ?></td>
                        <td style="text-align:center;"><?= htmlspecialchars($piece['quantite']) ?></td>
                        <td><?= htmlspecialchars($piece['commentaire'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div style="color:#555; font-size:0.97em;">
                Nous vous prions de bien vouloir vérifier la liste des pièces ci-dessus à la réception du colis.<br>
                Pour toute question ou réclamation, contactez notre service SAV.
            </div>
        <?php else: ?>
            <div style="color:#888;">Aucune pièce envoyée pour ce dossier.</div>
        <?php endif; ?>
    </div>

    <!-- Section SAV -->
    <div class="section">
        <div class="info">
            <span class="label">RMA :</span>
            <?= htmlspecialchars($dossier['rma']) ?><br>
            <?php if (!empty($dossier['rma'])): ?>
                <img class="barcode" src="../includes/barcode.php?text=<?= urlencode($dossier['rma']) ?>" alt="Code-barres RMA" style="max-width:300px;">
            <?php endif; ?>
        </div>
        <div class="info"><span class="label">Statut :</span> <?= htmlspecialchars($dossier['statut']) ?></div>
        <?php if (!empty($dossier['technicien'])): ?>
            <div class="info"><span class="label">Technicien :</span> <?= htmlspecialchars($dossier['technicien']) ?></div>
        <?php endif; ?>
        <?php if (!empty($dossier['commentaire'])): ?>
            <div class="info"><span class="label">Commentaire :</span> <?= nl2br(htmlspecialchars($dossier['commentaire'])) ?></div>
        <?php endif; ?>
    </div>

    <!-- Section fournisseur -->
    <?php if ($fournisseur): ?>
        <div class="section">
            <div class="info"><span class="label">Fournisseur :</span> <?= htmlspecialchars($fournisseur['nom']) ?></div>
            <div class="info"><span class="label">Adresse fournisseur :</span> <?= nl2br(htmlspecialchars($fournisseur['adresse'])) ?></div>
            <div class="info"><span class="label">Contact fournisseur :</span> <?= htmlspecialchars($fournisseur['contact']) ?></div>
        </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        Merci d’inclure ce bordereau dans votre colis.<br>
        Généré automatiquement par le système SAV.
    </div>
</div>
</body>
</html>
