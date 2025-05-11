<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>À propos de Gestion SAV - eddu22570</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; }
        .container { background: #fff; max-width: 650px; margin: 40px auto; padding: 32px; border-radius: 10px; box-shadow: 0 2px 12px #0001; }
        h2 { color: #1976d2; margin-top: 0; }
        .section { margin-bottom: 18px; }
        .label { font-weight: bold; color: #1976d2; }
        ul { margin-top: 8px; }
        .footer { margin-top: 30px; text-align: center; font-size: 0.95em; color: #888; }
        a { color: #1976d2; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .license-block { background: #f4f7fa; border-left: 4px solid #1976d2; padding: 12px 18px; margin: 18px 0; font-size: 0.98em; }
    </style>
</head>
<body>
<div class="container">
    <h2>À propos du logiciel de suivi SAV</h2>
    <div class="section">
        <span class="label">Nom du logiciel :</span> Gestion SAV
    </div>
    <div class="section">
        <span class="label">Version :</span> 1.0.0
    </div>
    <div class="section">
        <span class="label">Développé par :</span> eddu22570
    </div>
    <div class="section">
        <span class="label">Code source :</span>
        <a href="https://github.com/eddu22570/gestion-sav" target="_blank">Voir sur GitHub</a>
    </div>
    <div class="section">
        <span class="label">Description :</span>
        <p>
            Ce logiciel a été conçu pour faciliter la gestion des dossiers de Service Après-Vente (SAV) au sein d'un parc informatique :<br>
            suivi des dossiers, génération de bordereaux, gestion des pièces envoyées, et bien plus.
        </p>
    </div>
    <div class="section">
        <span class="label">Fonctionnalités principales :</span>
        <ul>
            <li>Création et suivi des dossiers SAV</li>
            <li>Gestion des clients, produits et fournisseurs</li>
            <li>Ajout et suivi des pièces envoyées</li>
            <li>Génération automatique de bordereaux d’envoi</li>
            <li>Impression et exportation des documents</li>
            <li>Gestion des statuts et commentaires</li>
            <li>Codes-barres pour numéro de série et RMA</li>
        </ul>
    </div>
    <div class="section">
        <span class="label">Licence :</span>
        <div class="license-block">
            Ce logiciel est distribué sous licence <b>Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International (CC BY-NC-SA 4.0)</b>.<br>
            <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.fr" target="_blank">Voir la licence complète</a><br><br>
            <b>Résumé :</b> Vous pouvez utiliser, copier, modifier et partager ce logiciel à condition de :<br>
            - Citer l’auteur (<b>eddu22570</b>)<br>
            - Ne pas l’utiliser ou le modifier à des fins commerciales<br>
            - Partager toute version modifiée sous la même licence
        </div>
    </div>
    <div class="section">
        <span class="label">Contact :</span>
        <br>
        Github : <a href="https://github.com/eddu22570">eddu22570</a>
    </div>
    <div class="footer">
        &copy; <?= date('Y') ?> eddu22570 - Projet open source sur GitHub.<br>
        Licence CC BY-NC-SA 4.0.
    </div>
</div>
<footer>
<a href="index.php">Retour à l'accueil</a></footer>
</body>
</html>
