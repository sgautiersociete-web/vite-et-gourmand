<?php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');

require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';

Session::start();

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
            <a href="/connexion" class="btn btn-outline-light btn-sm me-2">
                <i class="bi bi-lock"></i> Connexion
            </a>
            <a href="/menus" class="btn btn-gold btn-sm">
                <i class="bi bi-card-list"></i> Nos Menus
            </a>
        </div>
    </div>
</nav>

<section class="hero text-center">
    <div class="container">
        <p class="text-warning fw-bold">Traiteur Bordeaux depuis 25 ans</p>
        <h1 class="display-4 fw-bold">Vite &amp; Gourmand</h1>
        <p class="lead">Des menus raffinés pour tous vos événements</p>
        <a href="/menus" class="btn btn-gold btn-lg me-2">
            <i class="bi bi-card-list"></i> Voir nos menus
        </a>
        <a href="/contact" class="btn btn-outline-light btn-lg">
            <i class="bi bi-envelope"></i> Contact
        </a>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 style="color:#4F2D7F" class="fw-bold mb-4">Notre histoire</h2>
        <p class="lead">Fondée en 1999 par Julie et José, Vite &amp; Gourmand propose des prestations de restauration pour tous vos événements avec des produits frais et locaux.</p>
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

<?php
// Récupération des avis validés
if ($dbOk) {
    try {
        $stmt = $db->query("SELECT a.note, a.commentaire, u.nom, u.prenom FROM avis a JOIN utilisateur u ON u.utilisateur_id = a.utilisateur_id WHERE a.statut = 'valide' LIMIT 6");
        $avis = $stmt->fetchAll();
    } catch (Exception $e) {
        $avis = [];
    }
    if (!empty($avis)): ?>
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-4" style="color:#4F2D7F">⭐ Avis clients</h2>
        <div class="row g-3">
            <?php foreach ($avis as $a): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="text-warning mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="bi <?= $i <= $a['note'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="fst-italic">"<?= htmlspecialchars($a['commentaire']) ?>"</p>
                    <small class="text-muted">— <?= htmlspecialchars($a['prenom'] . ' ' . substr($a['nom'], 0, 1) . '.') ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif;
} ?>

<section class="py-4 text-center text-white" style="background:#4F2D7F">
    <div class="container">
        <h2 class="fw-bold mb-3">Prêt à commander ?</h2>
        <a href="/menus" class="btn btn-gold btn-lg">
            <i class="bi bi-arrow-right"></i> Découvrir nos menus
        </a>
    </div>
</section>

<footer class="bg-dark text-white text-center py-3">
    <div class="container">
        <small>
            © <?= date('Y') ?> Vite &amp; Gourmand — 
            <a href="/mentions-legales" class="text-white-50">Mentions légales</a> | 
            <a href="/cgv" class="text-white-50">CGV</a>
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
