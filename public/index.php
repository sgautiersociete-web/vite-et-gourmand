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

try {
    $db = Database::getInstance();
    $dbOk = true;
} catch (Exception $e) {
    $dbOk = false;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --vg-purple: #4F2D7F; --vg-gold: #E6A817; }
        .bg-vg { background-color: var(--vg-purple); }
        .btn-gold { background-color: var(--vg-gold); color: #fff; border: none; }
        .hero { background: linear-gradient(135deg, #4F2D7F, #7b5ea7); color: #fff; padding: 5rem 0; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-vg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">🍽️ Vite &amp; Gourmand</a>
        <div>
            <a href="/connexion" class="btn btn-outline-light btn-sm me-2">Connexion</a>
            <a href="/menus" class="btn btn-gold btn-sm">Nos Menus</a>
        </div>
    </div>
</nav>

<?php if ($dbOk): ?>
<div class="alert alert-success m-0 rounded-0 text-center">
    ✅ Base de données connectée avec succès !
</div>
<?php else: ?>
<div class="alert alert-danger m-0 rounded-0 text-center">
    ❌ Erreur de connexion à la base de données
</div>
<?php endif; ?>

<section class="hero text-center">
    <div class="container">
        <p class="text-warning fw-bold">Traiteur Bordeaux depuis 25 ans</p>
        <h1 class="display-4 fw-bold">Vite &amp; Gourmand</h1>
        <p class="lead">Des menus raffinés pour tous vos événements</p>
        <a href="/menus" class="btn btn-gold btn-lg me-2">Voir nos menus</a>
        <a href="/contact" class="btn btn-outline-light btn-lg">Contact</a>
    </div>
</section>

<section class="py-5 text-center">
    <div class="container">
        <h2 style="color:#4F2D7F">Notre histoire</h2>
        <p class="lead">Fondée en 1999 par Julie et José, Vite &amp; Gourmand propose des prestations de restauration pour tous vos événements.</p>
        <div class="row g-3 justify-content-center mt-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="display-4 fw-bold" style="color:#4F2D7F">25</div>
                    <div class="text-muted">Années d'expérience</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="display-4 fw-bold" style="color:#4F2D7F">500+</div>
                    <div class="text-muted">Clients satisfaits</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="display-4 fw-bold" style="color:#4F2D7F">4+</div>
                    <div class="text-muted">Thèmes de menus</div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-dark text-white text-center py-3">
    <p class="mb-0 small">© <?= date('Y') ?> Vite &amp; Gourmand — Traiteur Bordeaux</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
