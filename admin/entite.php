<?php
require_once '../includes/auth.php';
if (!isLoggedIn() || ($_SESSION['is_admin'] ?? 0) != 1) {
    header('Location: ../login.php');
    exit;
}
require_once '../includes/db.php';
$db = getDB();

$stmt = $db->query("SELECT * FROM entite LIMIT 1");
$entite = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $email = $_POST['email'] ?? '';
    $logo = $entite['logo'];

    // Gestion upload logo
    if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif'])) {
            $logo_dir = '../uploads/';
            if (!is_dir($logo_dir)) mkdir($logo_dir, 0777, true);
            $logo = $logo_dir . 'logo_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['logo']['tmp_name'], $logo);
        }
    }

    $stmt = $db->prepare("UPDATE entite SET nom=?, adresse=?, telephone=?, email=?, logo=? WHERE id=?");
    $stmt->execute([$nom, $adresse, $telephone, $email, $logo, $entite['id']]);
    header("Location: entite.php?ok=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de l'entité</title>
    <style>
        body { background: #f8fafc; font-family: sans-serif; }
        .form-bloc { background: #fff; border-radius: 8px; padding: 24px; max-width: 500px; margin: 30px auto; box-shadow: 0 2px 12px #0001; }
        label { display: block; margin-top: 14px; color: #2a7ae2; font-weight: 600; }
        input, textarea { width: 100%; padding: 7px 8px; border: 1px solid #cfd8dc; border-radius: 5px; font-size: 1em; background: #f1f5f8; }
        button { background: #2a7ae2; color: #fff; border: none; border-radius: 5px; padding: 8px 18px; font-weight: 600; cursor: pointer; font-size: 1em; margin-top: 18px;}
        button:hover { background: #1558b0; }
        .logo-preview { margin: 12px 0; }
    </style>
</head>
<body>
<div class="form-bloc">
    <h2>Paramètres de l'entité</h2>
    <?php if (isset($_GET['ok'])) echo '<div style="color:green;">Mise à jour effectuée !</div>'; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Nom de l'entité / société</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($entite['nom']) ?>" required>
        <label>Adresse</label>
        <textarea name="adresse" rows="3"><?= htmlspecialchars($entite['adresse']) ?></textarea>
        <label>Téléphone</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($entite['telephone']) ?>">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($entite['email']) ?>">
        <label>Logo (jpg/png/gif, optionnel)</label>
        <input type="file" name="logo" accept="image/*">
        <?php if ($entite['logo']): ?>
            <div class="logo-preview">
                <img src="<?= htmlspecialchars($entite['logo']) ?>" alt="Logo" style="max-width:120px;max-height:70px;">
            </div>
        <?php endif; ?>
        <button type="submit">Enregistrer</button>
    </form>
<div style="margin-top:30px; text-align:center;">
    <a href="../index.php" style="color:#1976d2; font-weight:bold; text-decoration:none;">&#8592; Retour à l'accueil</a>
</div>
</div>
</body>
</html>
