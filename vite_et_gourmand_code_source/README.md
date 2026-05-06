# Vite & Gourmand – Application Web Traiteur

Application web de commande de menus pour le traiteur bordelais **Vite & Gourmand**.

## Stack technique

| Couche | Technologie |
|--------|------------|
| Front-end | HTML5, CSS3, Bootstrap 5.3, JavaScript ES6+ |
| Back-end | PHP 8.2 (architecture MVC maison) |
| BD Relationnelle | MySQL 8.0 |
| BD NoSQL | MongoDB 6.0 |

---

## Prérequis

- PHP ≥ 8.2
- MySQL ≥ 8.0 ou MariaDB ≥ 10.6
- MongoDB ≥ 6.0 (Community)
- Composer
- Serveur web (Apache / Nginx) ou serveur PHP intégré

---

## Installation locale (étape par étape)

### 1. Cloner le dépôt

```bash
git clone https://github.com/[VOTRE_USERNAME]/vite-et-gourmand.git
cd vite-et-gourmand
```

### 2. Configurer l'environnement

```bash
cp .env.example .env
```

Éditer `.env` :

```env
APP_URL=http://localhost:8000

DB_HOST=localhost
DB_PORT=3306
DB_NAME=vite_gourmand
DB_USER=root
DB_PASS=votre_mot_de_passe

MONGO_URI=mongodb://localhost:27017
MONGO_DB=vite_gourmand

MAIL_FROM=noreply@viteetgourmand.fr
```

### 3. Créer et alimenter la base MySQL

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/fixtures.sql
```

> ⚠️ Les mots de passe des comptes de test dans `fixtures.sql` sont des **placeholders**.
> Générez-les avec : `password_hash('VotreMotDePasse!1', PASSWORD_BCRYPT, ['cost' => 12])`

### 4. Configurer MongoDB

```bash
mongosh < database/mongo_setup.js
```

### 5. Installer les dépendances PHP (optionnel - PHPMailer)

```bash
composer install
```

### 6. Lancer le serveur de développement

```bash
php -S localhost:8000 -t public/
```

Accéder à : **http://localhost:8000**

---

## Structure du projet

```
vite-et-gourmand/
├── public/             ← Racine web (index.php, css/, js/, images/)
├── app/
│   ├── config/         ← Database.php, Session.php
│   ├── controllers/    ← AuthController, MenuController, etc.
│   ├── models/         ← UserModel, MenuModel, CommandeModel, etc.
│   ├── views/          ← Templates PHP
│   └── helpers/        ← functions.php
├── database/
│   ├── schema.sql      ← Création des tables MySQL
│   ├── fixtures.sql    ← Données de test
│   └── mongo_setup.js  ← Initialisation MongoDB
├── .env.example
├── .gitignore
└── README.md
```

---

## Comptes de démonstration (à mettre à jour avec vos fixtures)

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Administrateur | admin@viteetgourmand.fr | [voir .env ou fixtures] |
| Employé | employe1@viteetgourmand.fr | [voir fixtures] |
| Utilisateur | client@test.fr | [voir fixtures] |

---

## Gestion Git

```
main          ← Production
└── develop   ← Intégration / tests
    ├── feature/auth
    ├── feature/menus
    ├── feature/commande
    ├── feature/espace-employe
    └── feature/espace-admin
```

---

## Déploiement production

Voir `docs/deploiement.pdf` pour la procédure complète (Railway / Vercel).

---

## Conformité

- ✅ RGPD : consentement explicite, politique de confidentialité, droit à l'effacement
- ✅ RGAA : navigation clavier, attributs alt, labels formulaires, contrastes AA
- ✅ Sécurité OWASP : injections SQL (PDO), XSS (htmlspecialchars), CSRF tokens, bcrypt

---

© 2024 Vite & Gourmand – Développé par FastDev
