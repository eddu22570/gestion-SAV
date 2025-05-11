<?php
require_once 'includes/auth.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['username'], $_POST['password'])) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Identifiants incorrects";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Connexion</h2>
<form method="post">
    <input type="text" name="username" placeholder="Utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Connexion</button>
</form>
<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
</body>
</html>