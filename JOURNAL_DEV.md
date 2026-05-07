# Journal de developpement - Vite & Gourmand
## Stephane Gautier - TP DWWM Studi

### 06 Avril 2026 - Demarrage du projet
- Lecture et analyse du cahier des charges complet
- Mise en place environnement de travail : XAMPP, VS Code, Git
- Creation du depot GitHub et premiere configuration
- Reflexion sur l architecture MVC et le schema de BDD
- Creation du MCD (Modele Conceptuel de Donnees)

### 07 Avril 2026 - Base de donnees
- Redaction du fichier schema.sql
- Creation tables : utilisateur, role, menu, theme, regime, plat, allergene
- Tests des relations et contraintes de cles etrangeres

### 10 Avril 2026 - Structure MVC et routeur
- Creation du front controller public/index.php
- Mise en place dossiers app/config et app/helpers
- Database.php avec PDO en Singleton
- Session.php pour sessions securisees

### 14 Avril 2026 - Authentification
- Page connexion.php avec verification bcrypt
- Page inscription.php avec validation mot de passe fort
- Gestion des roles (admin, employe, utilisateur)
- Tests securite : injection SQL, XSS

### 17 Avril 2026 - Page accueil
- Structure HTML avec Bootstrap 5
- Section hero, presentation equipe Julie et Jose
- Affichage des avis clients valides depuis BDD

### 21 Avril 2026 - Catalogue menus
- Page menus.php avec affichage depuis BDD
- Filtres dynamiques JavaScript cote client
- Badges theme et regime sur les cartes menus

### 24 Avril 2026 - Page detail menu
- Affichage plats groupes par type (entree, plat, dessert)
- Affichage 14 allergenes reglementaires EU
- Conditions du menu mises en evidence
- Bouton commander avec redirection si non connecte

### 28 Avril 2026 - Espace utilisateur
- Historique des commandes avec detail
- Timeline suivi des statuts de commande
- Formulaire avis (note 1 a 5 + commentaire)
- Modification profil personnel

### 30 Avril 2026 - Espace employe
- Gestion commandes avec filtres par statut et email
- Mise a jour statuts (accepte, preparation, livraison, livre...)
- Moderation des avis clients (valider/refuser)
- CRUD menus et horaires

### 02 Mai 2026 - Espace administrateur
- Creation comptes employes par admin uniquement
- Activation et desactivation des comptes
- Graphiques Chart.js : commandes et CA par menu

### 05 Mai 2026 - Deploiement Railway
- Configuration Railway avec PHP 8.2 et MySQL
- Import base de donnees en production
- Tests fonctionnels complets sur tous les parcours
- Corrections bugs routing et sessions

### 06 Mai 2026 - Finalisation
- Ajout commentaires developpeur dans le code
- Mise a jour README.md complet
- Verification conformite RGPD et mentions legales
- Livraison finale ECF
