# gestion-SAV

**Gestion-SAV** est une application web open source permettant de gérer efficacement le Service Après-Vente (SAV) dans une entreprise ou un atelier.

![Capture d'écran 2025-05-11 220139](https://github.com/user-attachments/assets/14b4b7ad-3ad6-4305-86d1-b11cba42d334)

## 🚀 Fonctionnalités principales

- Création, modification et suivi des dossiers SAV
- Gestion des clients, produits et fournisseurs
- Ajout et suivi des pièces envoyées
- Génération automatique de bordereaux d’envoi (avec codes-barres pour numéro de série et RMA)
- Impression et exportation des documents
- Gestion des statuts et commentaires pour chaque dossier
- Interface simple et responsive

## 📦 Installation sur serveur

1. **Cloner le dépôt**

git clone https://github.com/eddu22570/gestion-sav.git
cd gestion-sav

2. **Configurer la base de données**

- Ouvrez le fichier [`includes/init_db.php`](includes/init_db.php) et exécutez-le une fois (via navigateur ou ligne de commande) pour initialiser la base de données et les tables nécessaires.

3. **Configurer l’application**

- Les identifiants par défaut sont `admin` / `admin`.  
  Pensez à les modifier ou à créer un autre compte administrateur puis à supprimer le compte admin natif.

4. **Déployer sur un serveur web**

- Placez le dossier sur un serveur web supportant PHP (7.4+ recommandé).
- Assurez-vous que le dossier `cache/` (si utilisé) est accessible en écriture.

5. **Accéder à l’application**

- Ouvrez votre navigateur à l’adresse correspondante (ex: `http://localhost/gestion-sav/`).

## 🔒 Prérequis

- Serveur web (Apache, Nginx…)
- PHP 7.4 ou supérieur
- MySQL ou MariaDB
- Extension PHP PDO activée

## 📝 Exemple de configuration (`config.php`)

<?php define('DB_HOST', 'localhost'); define('DB_NAME', 'gestion_sav'); define('DB_USER', 'utilisateur'); define('DB_PASS', 'motdepasse'); ?>

## 📚 Documentation

- [À propos du logiciel](a_propos.php)
- [Structure de la base de données](includes/init_db.php)

## 🛡️ Licence

Ce projet est distribué sous licence [Creative Commons BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/deed.fr).

> **Vous pouvez utiliser, copier, modifier et partager ce logiciel à condition de :**
> - Citer l’auteur (**eddu22570**)
> - Ne pas l’utiliser ou le modifier à des fins commerciales
> - Partager toute version modifiée sous la même licence

## 🤝 Contribuer

Les contributions sont les bienvenues !  
Pour proposer une amélioration, signaler un bug ou poser une question :

- Ouvre une [issue](https://github.com/eddu22570/gestion-sav/issues)
- Ou propose une [pull request](https://github.com/eddu22570/gestion-sav/pulls)

## 📧 Contact

- Auteur : **eddu22570**
- Profil GitHub : [eddu22570](https://github.com/eddu22570)

---

&copy; [eddu22570](https://github.com/eddu22570) - Projet open source sous licence CC BY-NC-SA 4.0
