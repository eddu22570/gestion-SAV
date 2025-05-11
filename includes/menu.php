<ul>
    <li><a href="../index.php">Accueil</a></li>
    <li><a href="sav_liste.php">Liste des dossiers SAV</a></li>
    <!-- Lien dynamique pour la gestion des pièces d'un dossier spécifique -->
    <?php if (isset($dossier['id'])): ?>
        <li><a href="sav_pieces.php?id=<?= $dossier['id'] ?>">Gérer les pièces envoyées</a></li>
    <?php endif; ?>
    <li><a href="logout.php">Déconnexion</a></li>
</ul>
