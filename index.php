<?php
require_once 'includes/auth.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
require_once 'includes/db.php';
$db = getDB();

$total = $db->query("SELECT COUNT(*) FROM sav")->fetchColumn();
$en_attente = $db->query("SELECT COUNT(*) FROM sav WHERE statut = 'En attente'")->fetchColumn();
$en_cours = $db->query("SELECT COUNT(*) FROM sav WHERE statut = 'En cours'")->fetchColumn();
$termine = $db->query("SELECT COUNT(*) FROM sav WHERE statut = 'Terminé'")->fetchColumn();
$renvoye = $db->query("SELECT COUNT(*) FROM sav WHERE statut = 'Renvoyé'")->fetchColumn();
$aujourdhui = $db->prepare("SELECT COUNT(*) FROM sav WHERE date_demande = ?");
$aujourdhui->execute([date('Y-m-d')]);
$aujourdhui = $aujourdhui->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Accueil SAV</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .dashboard {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
        margin: 30px auto 40px auto;
        max-width: 950px;
    }
    .dashboard-card {
        background: #f8fafc;
        border-radius: 16px;
        box-shadow: 0 2px 12px #0002;
        padding: 28px 32px 20px 32px;
        min-width: 185px;
        text-align: center;
        flex: 1 1 180px;
        border-top: 6px solid #2a7ae2;
        position: relative;
        transition: transform 0.15s;
    }
    .dashboard-card:hover {
        transform: translateY(-5px) scale(1.03);
        box-shadow: 0 4px 18px #0003;
    }
    .dashboard-card .icon {
        width: 36px;
        height: 36px;
        margin-bottom: 12px;
        display: inline-block;
    }
    .dashboard-card.total     { border-top-color: #2a7ae2; }
    .dashboard-card.attente   { border-top-color: #fbbf24; }
    .dashboard-card.cours     { border-top-color: #38bdf8; }
    .dashboard-card.termine   { border-top-color: #22c55e; }
    .dashboard-card.renvoye   { border-top-color: #f87171; }
    .dashboard-card.jour      { border-top-color: #64748b; }
    .dashboard-card h3 {
        margin: 0 0 10px 0;
        color: #222;
        font-size: 1.1em;
        font-weight: 600;
    }
    .dashboard-card .stat {
        font-size: 2.4em;
        color: #2a7ae2;
        margin-bottom: 0;
        font-weight: bold;
    }
    .dashboard-card.attente .stat { color: #fbbf24; }
    .dashboard-card.cours  .stat { color: #38bdf8; }
    .dashboard-card.termine .stat { color: #22c55e; }
    .dashboard-card.renvoye .stat { color: #f87171; }
    .dashboard-card.jour .stat { color: #64748b; }
    @media (max-width: 900px) {
        .dashboard { flex-direction: column; align-items: center; }
        .dashboard-card { width: 90%; min-width: 0; margin-bottom: 16px; }
    }
    </style>
</head>
<body>
<h1>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></h1>

<div class="dashboard">
    <div class="dashboard-card total">
        <span class="icon">
            <!-- Icône "tickets" -->
            <svg fill="none" viewBox="0 0 32 32"><rect x="4" y="7" width="24" height="18" rx="4" fill="#2a7ae2" opacity="0.13"/><rect x="7" y="10" width="18" height="12" rx="2" fill="#2a7ae2" opacity="0.35"/><rect x="10" y="13" width="12" height="6" rx="1" fill="#2a7ae2"/></svg>
        </span>
        <h3>Total SAV</h3>
        <div class="stat"><?= $total ?></div>
    </div>
    <div class="dashboard-card attente">
        <span class="icon">
            <!-- Icône "sablier" -->
            <svg fill="none" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" stroke="#fbbf24" stroke-width="3" fill="none"/><path d="M10 10 L22 22 M22 10 L10 22" stroke="#fbbf24" stroke-width="2" stroke-linecap="round"/></svg>
        </span>
        <h3>En attente</h3>
        <div class="stat"><?= $en_attente ?></div>
    </div>
    <div class="dashboard-card cours">
        <span class="icon">
            <!-- Icône "progress" -->
            <svg fill="none" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" stroke="#38bdf8" stroke-width="3" fill="none"/><path d="M16 16 L16 7" stroke="#38bdf8" stroke-width="3" stroke-linecap="round"/><circle cx="16" cy="16" r="4" fill="#38bdf8"/></svg>
        </span>
        <h3>En cours</h3>
        <div class="stat"><?= $en_cours ?></div>
    </div>
    <div class="dashboard-card termine">
        <span class="icon">
            <!-- Icône "check" -->
            <svg fill="none" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" stroke="#22c55e" stroke-width="3" fill="none"/><path d="M10 17 l4 4 l8-8" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <h3>Terminés</h3>
        <div class="stat"><?= $termine ?></div>
    </div>
    <div class="dashboard-card renvoye">
        <span class="icon">
            <!-- Icône "retour" -->
            <svg fill="none" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" stroke="#f87171" stroke-width="3" fill="none"/><path d="M16 9 v7 h7" stroke="#f87171" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 16 l-5 -5" stroke="#f87171" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <h3>Renvoyés</h3>
        <div class="stat"><?= $renvoye ?></div>
    </div>
    <div class="dashboard-card jour">
        <span class="icon">
            <!-- Icône "calendrier" -->
            <svg fill="none" viewBox="0 0 32 32"><rect x="7" y="10" width="18" height="13" rx="3" fill="#64748b" opacity="0.15"/><rect x="10" y="13" width="12" height="7" rx="2" fill="#64748b" opacity="0.35"/><rect x="14" y="17" width="4" height="3" rx="1" fill="#64748b"/></svg>
        </span>
        <h3>SAV aujourd'hui</h3>
        <div class="stat"><?= $aujourdhui ?></div>
    </div>
</div>

<ul>
    <li><a href="sav/">Gérer les SAV</a></li>
    <?php if (isAdmin()): ?>
    <li><a href="admin/users.php">Gérer les utilisateurs</a></li>
    <li><a href="admin/entite.php">Paramètres de l'entité</a></li>
    <?php endif; ?>
    <li><a href="a_propos.php">À propos</a></li>
    <li><a href="logout.php">Déconnexion</a></li>
</ul>
</body>
</html>
