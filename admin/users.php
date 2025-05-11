<?php
require_once '../includes/auth.php';
if (!isLoggedIn() || !isAdmin()) { header('Location: ../login.php'); exit; }
require_once '../includes/db.php';
$db = getDB();

// Ajout utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)");
    $stmt->execute([
        $_POST['username'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        isset($_POST['is_admin']) ? 1 : 0
    ]);
    header('Location: users.php');
    exit;
}

// Liste
$users = $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Utilisateurs</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h2>Gestion des utilisateurs</h2>
<form method="post">
    <input type="text" name="username" placeholder="Utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <label><input type="checkbox" name="is_admin"> Admin</label>
    <button type="submit">Ajouter</button>
</form>
<table>
<tr><th>ID</th><th>Utilisateur</th><th>Admin</th></tr>
<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['username']) ?></td>
    <td><?= $u['is_admin'] ? 'Oui' : 'Non' ?></td>
</tr>
<?php endforeach; ?>
</table>
<a href="../index.php">Retour</a>
</body>
</html>
