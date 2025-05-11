<?php
require_once 'db.php';
$db = getDB();

// Table users
$db->exec("
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT,
    is_admin INTEGER DEFAULT 0
);
");

// Table sav
$db->exec("
CREATE TABLE IF NOT EXISTS sav (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client TEXT,
    produit TEXT,
    marque TEXT,
    numero_serie TEXT,
    motif TEXT,
    rma TEXT,
    date_demande TEXT,
    statut TEXT,
    created_by INTEGER,
    FOREIGN KEY(created_by) REFERENCES users(id)
);
");

// table pieces
$db->exec("
CREATE TABLE pieces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sav_id INT NOT NULL,
    nom_piece VARCHAR(255) NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    commentaire VARCHAR(255),
    FOREIGN KEY (sav_id) REFERENCES sav(id) ON DELETE CASCADE
);
");

// Table pièces jointes
$db->exec("
CREATE TABLE IF NOT EXISTS sav_pieces_jointes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sav_id INTEGER NOT NULL,
    nom_fichier TEXT NOT NULL,
    chemin TEXT NOT NULL,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
");

// Table commentaires
$db->exec("
CREATE TABLE IF NOT EXISTS sav_commentaires (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sav_id INTEGER NOT NULL,
    auteur TEXT NOT NULL,
    commentaire TEXT NOT NULL,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sav_id) REFERENCES sav(id) ON DELETE CASCADE
);
");

// Table entite
$db->exec("
CREATE TABLE IF NOT EXISTS entite (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    adresse TEXT,
    telephone TEXT,
    email TEXT,
    logo TEXT
);
");

// Création d'un admin par défaut : admin / admin
$admin = $db->prepare("INSERT OR IGNORE INTO users (username, password, is_admin) VALUES (?, ?, 1)");
$admin->execute(['admin', password_hash('admin', PASSWORD_DEFAULT)]);

// Création d'une entité par défaut si aucune n'existe
$check = $db->query("SELECT COUNT(*) FROM entite")->fetchColumn();
if ($check == 0) {
    $db->prepare("INSERT INTO entite (nom, adresse, telephone, email, logo) VALUES (?, ?, ?, ?, ?)")
       ->execute(['Ma Société', 'Adresse de la société', '0123456789', 'contact@masociete.fr', '']);
}

echo "Base initialisée. Utilisateur admin/admin créé.";
?>
