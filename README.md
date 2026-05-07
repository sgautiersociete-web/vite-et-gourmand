Application web de commande en ligne pour le traiteur bordelais **Vite & Gourmand**.
Développée par **Stéphane Gautier** dans le cadre du TP Développeur Web et Web Mobile (Studi).

---

## 🌐 Liens

| | |
|---|---|
| **Application en production** | https://vite-et-gourmand-production-983f.up.railway.app |
| **Dépôt GitHub** | https://github.com/sgautiersociete-web/vite-et-gourmand |
| **Gestion de projet** | [Tableau Trello](https://trello.com) |

### Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Administrateur | admin@viteetgourmand.fr | password |
| Employé | employe1@viteetgourmand.fr | password |
| Utilisateur | client@test.fr | password |

---

## 📋 Présentation du projet

Vite & Gourmand est une entreprise de traiteur familiale fondée en 1999 à Bordeaux par Julie et José. L'application web permet de :

- Consulter le catalogue de menus avec filtres dynamiques
- Passer des commandes en ligne avec calcul automatique du prix
- Gérer les commandes depuis un espace employé
- Administrer l'application depuis un espace admin avec statistiques

---

## 🛠️ Stack technique

| Couche | Technologie | Justification |
|--------|------------|---------------|
| Front-end | HTML5, CSS3, Bootstrap 5 | Responsive, accessibilité, rapidité |
| Front-end | JavaScript ES6 | Filtres dynamiques, validation formulaires |
| Back-end | PHP 8.2 (MVC natif) | Maîtrise des fondamentaux, sans framework |
| Base de données | MySQL 8 | Relationnel, contraintes intégrité |
| Déploiement | Railway | PaaS moderne, SSL auto, CD depuis GitHub |
| Versioning | Git + GitHub | Branches main/develop/feature |

---

## 🚀 Installation locale

### Prérequis

- PHP 8.2+
- MySQL 8.0+
- Git

### Étapes

**1. Cloner le dépôt**
```bash
git clone https://github.com/sgautiersociete-web/vite-et-gourmand.git
cd vite-et-gourmand
```

**2. Créer la base de données**
```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/fixtures.sql
```

**3. Configurer l'environnement**
```bash
cp .env.example .env
# Éditer .env avec vos identifiants MySQL
```

**4. Lancer le serveur**
```bash
php -S localhost:8000 public/index.php
```

**5. Accéder à l'application**
http://localhost:8000

---

## 📁 Structure du projet
vite-et-gourmand/
├── public/                 ← Point d'entrée web
│   ├── index.php           ← Routeur central (front controller)
│   ├── home.php            ← Page d'accueil
│   ├── menus.php           ← Catalogue menus avec filtres
│   ├── menu-detail.php     ← Détail d'un menu
│   ├── connexion.php       ← Authentification
│   ├── inscription.php     ← Création de compte
│   ├── espace-utilisateur.php  ← Espace client
│   ├── espace-employe.php  ← Espace employé
│   ├── espace-admin.php    ← Espace administrateur
│   ├── contact.php         ← Formulaire de contact
│   ├── css/                ← Feuilles de style
│   └── js/                 ← Scripts JavaScript
├── app/
│   ├── config/
│   │   ├── Database.php    ← Connexion PDO MySQL (Singleton)
│   │   └── Session.php     ← Gestion sessions sécurisées
│   └── helpers/
│       └── functions.php   ← Fonctions utilitaires
├── database/
│   ├── schema.sql          ← Création des tables MySQL
│   └── fixtures.sql        ← Données de test
├── .env.example            ← Template variables d'environnement
├── .gitignore
└── README.md

---

## 🗄️ Base de données

Le schéma MySQL comprend les tables suivantes :

- `role` — Rôles utilisateurs (administrateur, employé, utilisateur)
- `utilisateur` — Comptes utilisateurs
- `menu` — Catalogue des menus
- `theme` — Thèmes des menus (Noël, Pâques, classique...)
- `regime` — Régimes alimentaires (classique, végétarien, vegan...)
- `plat` — Plats proposés dans les menus
- `allergene` — Allergènes (14 allergènes réglementaires EU)
- `plat_allergene` — Relation plats/allergènes
- `menu_plat` — Relation menus/plats
- `horaire` — Horaires d'ouverture
- `commande` — Commandes clients
- `commande_historique` — Suivi des statuts de commande
- `avis` — Avis clients

---

## 🔐 Sécurité

- **Injections SQL** : Requêtes préparées PDO sur toutes les requêtes
- **XSS** : Echappement systématique avec `htmlspecialchars()`
- **Mots de passe** : Hachage bcrypt avec `password_hash()` (coût 12)
- **Sessions** : `session_regenerate_id()` à la connexion, cookies httpOnly
- **Contrôle d'accès** : Vérification du rôle sur chaque page sensible
- **HTTPS** : SSL automatique en production (Railway)
- **Variables sensibles** : Fichier `.env` exclu du dépôt Git

---

## 🌿 Gestion Git
main              ← Production (stable)
└── develop       ← Intégration / tests
├── feature/auth
├── feature/menus
├── feature/commande
├── feature/espace-employe
└── feature/espace-admin

**Convention des commits :**
- `feat:` Nouvelle fonctionnalité
- `fix:` Correction de bug
- `refactor:` Refactoring
- `style:` Modifications CSS/UI
- `docs:` Documentation
- `db:` Modifications base de données

---

## ✅ Fonctionnalités développées

- [x] Page d'accueil avec avis clients validés
- [x] Catalogue menus avec filtres dynamiques (JS côté client)
- [x] Page détail menu (plats, allergènes, conditions)
- [x] Inscription avec validation mot de passe fort
- [x] Connexion / Déconnexion / Mot de passe oublié
- [x] Commande en ligne multi-étapes
- [x] Calcul automatique prix (réduction 10% si +5 pers., frais livraison)
- [x] Espace utilisateur (historique, suivi, annulation, avis)
- [x] Espace employé (commandes, menus, modération avis)
- [x] Espace admin (statistiques, graphiques, gestion employés)
- [x] Contact / Mentions légales / CGV
- [x] Déploiement en production

---

## 👨‍💻 Développeur

**Stéphane Gautier**
Formation : Titre Professionnel Développeur Web et Web Mobile (TP DWWM)
Organisme : Studi — Mai 2026

---

*© 2026 Vite & Gourmand — Conçu avec ❤️ par Stéphane Gautier*
