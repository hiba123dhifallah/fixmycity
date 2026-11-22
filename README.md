# Gestion des utilisateurs

## 1. Présentation du projet

Ce projet consiste à développer un module de gestion des utilisateurs avec PHP et MySQL en utilisant l'architecture MVC (Model-View-Controller).

Il permet de gérer les comptes utilisateurs, l'authentification et les rôles.

## 2. Fonctionnalités principales

- Inscription d'un utilisateur.
- Connexion et déconnexion.
- Affichage du profil utilisateur.
- Ajout d'un utilisateur.
- Affichage de la liste des utilisateurs.
- Modification des informations d'un utilisateur.
- Suppression d'un utilisateur.
- Gestion des rôles : User et Admin.
- Contrôle d'accès selon le rôle.

## 3. Technologies utilisées

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP
- phpMyAdmin
- Visual Studio Code
- Git et GitHub

## 4. Architecture du projet

```text
user/
│
├── Controller/
│   └── UserController.php
│
├── Model/
│   └── User.php
│
├── View/
│   ├── BackOffice/
│   │   ├── add.php
│   │   ├── update.php
│   │   ├── delete.php
│   │   ├── show.php
│   │   └── verification.php
│   │
│   └── FrontOffice/
│       ├── login.php
│       ├── register.php
│       └── profile.php
│
├── config/
│   └── config.php
│
├── database/
│   └── gestion_users.sql
│
├── index.php
└── README.md
```

## 5. Installation et configuration

### Prérequis

- Installer XAMPP.
- Démarrer Apache et MySQL.
- Installer Visual Studio Code.

### Étapes d'installation

1. Copier le dossier `user` dans le répertoire `htdocs` de XAMPP.
2. Ouvrir phpMyAdmin.
3. Créer la base de données `gestion_users`.
4. Importer le fichier `database/gestion_users.sql`.
5. Vérifier les paramètres de connexion dans `config/config.php`.

### Configuration de la base de données

Les paramètres de connexion sont définis dans le fichier `config/config.php`.

Vérifier les informations suivantes :

- Hôte : `localhost`
- Nom de la base : `gestion_users`
- Utilisateur : `root`
- Mot de passe : vide par défaut sur XAMPP.

## 6. Exécution du projet

1. Démarrer Apache et MySQL depuis XAMPP.
2. Ouvrir le navigateur.
3. Accéder à l'adresse suivante :

`http://localhost/user/`

## 7. Sécurité

Le projet intègre plusieurs mécanismes de sécurité :

- Utilisation de `password_hash()` pour sécuriser les mots de passe.
- Utilisation de `password_verify()` pour vérifier les mots de passe.
- Protection contre les injections SQL grâce aux requêtes préparées.
- Protection CSRF avec des jetons de vérification.
- Protection XSS avec `htmlspecialchars()`.
- Validation des données saisies.
- Gestion sécurisée des sessions.
- Contrôle d'accès selon les rôles.

## 8. Architecture MVC

Le projet respecte le modèle MVC :

- **Model :** gère les données et les interactions avec la base de données.
- **View :** contient les interfaces affichées à l'utilisateur.
- **Controller :** gère les actions et fait le lien entre le Model et la View.

## 9. Gestion du projet avec GitHub

Le développement est organisé avec Git et GitHub.

Chaque membre travaille sur une branche dédiée à sa tâche. Les modifications sont ensuite intégrées dans la branche principale après vérification.

## 10. Conclusion

Ce projet permet de mettre en pratique les concepts PHP, MySQL et MVC à travers la réalisation d'un module complet de gestion des utilisateurs, tout en respectant les principes de validation et de sécurité.