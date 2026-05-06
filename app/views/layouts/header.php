<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Vite & Gourmand') ?> – Traiteur Bordeaux</title>
    <meta name="description" content="Vite & Gourmand, traiteur bordelais depuis 25 ans. Commandez vos menus de fête en ligne.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<!-- SKIP NAV (accessibilité RGAA) -->
<a href="#main-content" class="skip-link">Aller au contenu principal</a>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-vg sticky-top" role="navigation" aria-label="Navigation principale">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <span aria-hidden="true">🍽️</span> Vite &amp; Gourmand
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"
                aria-controls="navMain" aria-expanded="false" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/" aria-label="Retour à l'accueil">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/menus">Nos Menus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
                <?php if (Session::isLoggedIn()): ?>
                    <?php $user = Session::user(); ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                            <?= e($user['prenom']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($user['role'] === 'administrateur'): ?>
                                <li><a class="dropdown-item" href="/espace-admin">Espace Admin</a></li>
                            <?php elseif ($user['role'] === 'employe'): ?>
                                <li><a class="dropdown-item" href="/espace-employe">Espace Employé</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="/espace-utilisateur">Mon Espace</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/deconnexion">
                                <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Déconnexion
                            </a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light px-3 ms-2" href="/connexion">
                            <i class="bi bi-lock" aria-hidden="true"></i> Connexion
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash messages -->
<?php
$success = Session::getFlash('success');
$error   = Session::getFlash('error');
$info    = Session::getFlash('info');
?>
<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show m-0 rounded-0" role="alert">
        <div class="container"><?= e($success) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show m-0 rounded-0" role="alert">
        <div class="container"><?= e($error) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endif; ?>
<?php if ($info): ?>
    <div class="alert alert-info alert-dismissible fade show m-0 rounded-0" role="alert">
        <div class="container"><?= e($info) ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endif; ?>

<main id="main-content">
