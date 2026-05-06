<?php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');

require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';

Session::start();

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Page d'accueil temporaire sans base de données
echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark" style="background:#4F2D7F">
        <div class="container">
            <span class="navbar-brand fw-bold">🍽️ Vite & Gourmand</span>
        </div>
    </nav>
    <div class="container py-5 text-center">
        <h1 style="color:#4F2D7F">Bienvenue chez Vite & Gourmand</h1>
        <p class="lead">Traiteur bordelais depuis 25 ans</p>
        <a href="/menus" class="btn btn-warning">Voir nos menus</a>
    </div>
</body>
</html>';
