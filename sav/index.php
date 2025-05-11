<?php
require_once '../includes/auth.php';
if (!isLoggedIn()) { header('Location: ../login.php'); exit; }
require_once '../includes/db.php';
$db = getDB();

$search_client = $_GET['client'] ?? '';
$search_produit = $_GET['produit'] ?? '';
$search_marque = $_GET['marque'] ?? '';
$search_numero_serie = $_GET['numero_serie'] ?? '';
$search_rma = $_GET['rma'] ?? '';
$search_motif = $_GET['motif'] ?? '';
$search_date = $_GET['date_demande'] ?? '';
$search_statut = $_GET['statut'] ?? '';

$where = [];
$params = [];

if ($search_client)        { $where[] = 'client LIKE ?';         $params[] = "%$search_client%"; }
if ($search_produit)       { $where[] = 'produit LIKE ?';        $params[] = "%$search_produit%"; }
if ($search_marque)        { $where[] = 'marque LIKE ?';         $params[] = "%$search_marque%"; }
if ($search_numero_serie)  { $where[] = 'numero_serie LIKE ?';   $params[] = "%$search_numero_serie%"; }
if ($search_rma)           { $where[] = 'rma LIKE ?';            $params[] = "%$search_rma%"; }
if ($search_motif)         { $where[] = 'motif LIKE ?';          $params[] = "%$search_motif%"; }
if ($search_date)          { $where[] = 'date_demande = ?';      $params[] = $search_date; }
if ($search_statut && $search_statut != 'Tous') {
    $where[] = 'statut = ?';
    $params[] = $search_statut;
}

$sql = "SELECT * FROM sav";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY id DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$sav = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestion SAV</title>
    <link rel="stylesheet" href="../style.css">
    <style>
    body {
        margin: 0;
        padding: 0;
        background: #f8fafc;
    }
    h2 {
        margin: 30px 0 10px 0;
        color: #2a7ae2;
        text-align: center;
    }
    .glpi-add-btn {
        float: right;
        margin: 18px 24px 18px 0;
        background: #2a7ae2;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 20px;
        font-size: 1em;
        font-weight: 500;
        cursor: pointer;
        box-shadow: 0 2px 6px #0001;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .glpi-add-btn:hover {
        background: #1558b0;
        text-decoration: none;
        color: #fff;
    }
    .glpi-search-bar {
        background: #e3f0ff;
        border-radius: 8px;
        padding: 12px 18px;
        margin: 24px 0 18px 0;
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        align-items: center;
        overflow-x: auto;
        scrollbar-width: thin;
        width: 100%;
        box-sizing: border-box;
    }
    .glpi-search-bar input,
    .glpi-search-bar select {
        min-width: 150px;
        padding: 7px 8px;
        border: 1px solid #cfd8dc;
        border-radius: 5px;
        font-size: 1em;
        background: #f1f5f8;
    }
    .glpi-search-bar button {
        background: #2a7ae2;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 7px 18px;
        font-weight: 600;
        cursor: pointer;
        font-size: 1em;
    }
    .glpi-search-bar button:hover {
        background: #1558b0;
    }
    .glpi-search-bar a {
        margin-left: 8px;
        color: #2a7ae2;
        text-decoration: underline;
        font-size: 0.98em;
    }
    .glpi-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        min-width: 1200px;
    }
    .glpi-table th, .glpi-table td {
        padding: 12px 8px;
        text-align: left;
        border-bottom: 1px solid #e3e6ea;
        font-size: 1em;
    }
    .glpi-table th {
        background: #e3f0ff;
        color: #2a7ae2;
        font-weight: 600;
    }
    .glpi-table tr:last-child td {
        border-bottom: none;
    }
    .glpi-status {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
        font-size: 1em;
    }
    .glpi-status.attente { color: #fbbf24; }
    .glpi-status.cours { color: #38bdf8; }
    .glpi-status.termine { color: #22c55e; }
    .glpi-status.renvoye { color: #f87171; }
    .glpi-status svg { width: 18px; height: 18px; vertical-align: middle; }
    @media (max-width: 900px) {
        .glpi-search-bar { width: 100vw; }
        .glpi-search-bar {
            flex-wrap: wrap;
            overflow-x: visible;
        }
        .glpi-search-bar input,
        .glpi-search-bar select,
        .glpi-search-bar button,
        .glpi-search-bar a {
            min-width: 100%;
            flex: 1 1 100%;
            margin-bottom: 8px;
        }
        .glpi-table {
            min-width: 900px;
        }
    }
    </style>
</head>
<body>
<h2>Gestion des dossiers SAV</h2>
<a href="nouveau.php" class="glpi-add-btn">
    &#x2795; Nouveau dossier SAV
</a>

<form method="get" class="glpi-search-bar">
    <input type="text" name="client" placeholder="Client" value="<?= htmlspecialchars($search_client) ?>">
    <input type="text" name="produit" placeholder="Produit" value="<?= htmlspecialchars($search_produit) ?>">
    <input type="text" name="marque" placeholder="Marque" value="<?= htmlspecialchars($search_marque) ?>">
    <input type="text" name="numero_serie" placeholder="N° série" value="<?= htmlspecialchars($search_numero_serie) ?>">
    <input type="text" name="rma" placeholder="RMA" value="<?= htmlspecialchars($search_rma) ?>">
    <input type="text" name="motif" placeholder="Motif" value="<?= htmlspecialchars($search_motif) ?>">
    <input type="date" name="date_demande" value="<?= htmlspecialchars($search_date) ?>">
    <select name="statut">
        <option value="Tous" <?= $search_statut=='Tous'?'selected':'' ?>>Tous statuts</option>
        <option value="En attente" <?= $search_statut=='En attente'?'selected':'' ?>>En attente</option>
        <option value="En cours" <?= $search_statut=='En cours'?'selected':'' ?>>En cours</option>
        <option value="Terminé" <?= $search_statut=='Terminé'?'selected':'' ?>>Terminé</option>
        <option value="Renvoyé" <?= $search_statut=='Renvoyé'?'selected':'' ?>>Renvoyé</option>
    </select>
    <button type="submit">🔍 Rechercher</button>
    <a href="index.php">Réinitialiser</a>
</form>

<table class="glpi-table">
<tr>
    <th>ID</th>
    <th>Client</th>
    <th>Produit</th>
    <th>Marque</th>
    <th>Numéro de série</th>
    <th>Motif</th>
    <th>RMA</th>
    <th>Date</th>
    <th>Statut</th>
    <th>Actions</th>
</tr>
<?php foreach ($sav as $dossier): ?>
<tr>
    <td><?= $dossier['id'] ?></td>
    <td><?= htmlspecialchars($dossier['client']) ?></td>
    <td><?= htmlspecialchars($dossier['produit']) ?></td>
    <td><?= htmlspecialchars($dossier['marque']) ?></td>
    <td><?= htmlspecialchars($dossier['numero_serie']) ?></td>
    <td><?= htmlspecialchars($dossier['motif']) ?></td>
    <td><?= htmlspecialchars($dossier['rma']) ?></td>
    <td><?= htmlspecialchars($dossier['date_demande']) ?></td>
    <td>
        <?php
            $statut = $dossier['statut'];
            $class = '';
            $icon = '';
            switch ($statut) {
                case 'En attente':
                    $class = 'attente';
                    $icon = '<svg fill="none" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" stroke="#fbbf24" stroke-width="3"/><path d="M10 5v5l3 3" stroke="#fbbf24" stroke-width="2" stroke-linecap="round"/></svg>';
                    break;
                case 'En cours':
                    $class = 'cours';
                    $icon = '<svg fill="none" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" stroke="#38bdf8" stroke-width="3"/><path d="M10 10v-5" stroke="#38bdf8" stroke-width="2" stroke-linecap="round"/><circle cx="10" cy="10" r="3" fill="#38bdf8"/></svg>';
                    break;
                case 'Terminé':
                    $class = 'termine';
                    $icon = '<svg fill="none" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" stroke="#22c55e" stroke-width="3"/><path d="M7 11l2 2l4-4" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                    break;
                case 'Renvoyé':
                    $class = 'renvoye';
                    $icon = '<svg fill="none" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" stroke="#f87171" stroke-width="3"/><path d="M10 5v5h5" stroke="#f87171" stroke-width="2" stroke-linecap="round"/><path d="M10 10l-3-3" stroke="#f87171" stroke-width="2" stroke-linecap="round"/></svg>';
                    break;
                default:
                    $icon = '';
            }
        ?>
        <span class="glpi-status <?= $class ?>"><?= $icon ?><?= htmlspecialchars($statut) ?></span>
    </td>
    <td>
        <a href="modifier.php?id=<?= $dossier['id'] ?>" title="Modifier">
            <svg width="18" height="18" fill="none" viewBox="0 0 20 20" style="vertical-align:middle;"><path d="M14.7 3.3a1 1 0 0 1 1.4 1.4l-9 9a1 1 0 0 1-.4.24l-3 1a1 1 0 0 1-1.26-1.26l1-3a1 1 0 0 1 .24-.4l9-9z" stroke="#2a7ae2" stroke-width="1.2" fill="#e3f0ff"/></svg>
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<a href="../index.php" style="margin-left:18px; color:#2a7ae2;">&#8592; Retour accueil</a>
</body>
</html>
