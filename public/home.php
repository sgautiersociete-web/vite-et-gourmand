<?php
// Développeur : Stéphane Gautier
// Formation : TP DWWM - Studi - Mai 2026
// Page d'accueil - Vite & Gourmand

try { $db = Database::getInstance(); $dbOk = true; } catch (Exception $e) { $dbOk = false; }
$avis = [];
if ($dbOk) {
    try {
        $stmt = $db->query("SELECT a.note, a.commentaire, u.nom, u.prenom 
                            FROM avis a 
                            JOIN utilisateur u ON u.utilisateur_id = a.utilisateur_id 
                            WHERE a.statut = 'valide' 
                            LIMIT 6");
        $avis = $stmt->fetchAll();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand - Traiteur Bordeaux</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --couleur-principale: #4a2c0a;
            --couleur-accent: #c9954a;
            --couleur-fond: #fdf8f0;
        }

        body {
            background-color: var(--couleur-fond);
            font-family: 'Segoe UI', sans-serif;
        }

        /* navbar */
        .navbar-custom {
            background-color: var(--couleur-principale);
        }

        /* section hero */
        .hero {
            background-color: var(--couleur-principale);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.1rem;
            opacity: 0.8;
            margin-bottom: 2rem;
        }

        .btn-accent {
            background-color: var(--couleur-accent);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-accent:hover {
            background-color: #b8843a;
            color: white;
        }

        /* stats */
        .stats-section {
            background-color: var(--couleur-accent);
            padding: 40px 0;
            color: white;
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .stat-label {
            font-size: 0.85rem;
            opacity: 0.85;
        }

        /* sections */
        .section-title {
            color: var(--couleur-principale);
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        /* carte menu preview */
        .menu-preview-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background: white;
            height: 100%;
            transition: box-shadow 0.2s;
        }

        .menu-preview-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .menu-emoji {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .menu-prix {
            color: var(--couleur-accent);
            font-size: 1.3rem;
            font-weight: 700;
        }

        /* avis */
        .avis-card {
            background: white;
            border: 1px solid #e8ddd0;
            border-radius: 8px;
            padding: 20px;
            height: 100%;
        }

        .etoiles {
            color: var(--couleur-accent);
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        /* footer */
        footer {
            background-color: var(--couleur-principale);
            color: rgba(255,255,255,0.7);
            padding: 30px 0;
        }

        footer a {
            color: var(--couleur-accent);
            text-decoration: none;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">🍽️ Vite & Gourmand</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="/">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="/menus">Nos Menus</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                <?php if(Session::isLoggedIn()): ?>
                    <li class="nav-item"><a class="nav-link" href="/espace-utilisateur">Mon espace</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="/deconnexion">Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/connexion">Connexion</a></li>
                    <li class="nav-item">
                        <a class="btn btn-warning btn-sm ms-2 mt-1" href="/menus">Commander</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <p class="text-warning mb-2"><small>Traiteur Bordeaux depuis 1999</small></p>
        <h1>Vite & Gourmand</h1>
        <p>Des menus raffinés pour tous vos événements,<br>préparés avec passion par Julie & José.</p>
        <a href="/menus" class="btn-accent me-2">Voir nos menus</a>
        <a href="/contact" class="btn btn-outline-light">Nous contacter</a>
    </div>
</section>

<!-- Stats -->
<div class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="stat-number">25</div>
                <div class="stat-label">Années d'expérience</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">500+</div>
                <div class="stat-label">Clients satisfaits</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">4</div>
                <div class="stat-label">Thèmes de menus</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">2</div>
                <div class="stat-label">Passionnés</div>
            </div>
        </div>
    </div>
</div>

<!-- Présentation -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <h2 class="section-title">Notre histoire</h2>
                <p class="text-muted">
                    Fondée en 1999 par <strong>Julie</strong> et <strong>José</strong>, 
                    notre entreprise familiale propose des prestations de restauration 
                    pour tous vos événements avec des produits frais et locaux.
                </p>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>Produits frais et locaux</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>Livraison à domicile</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>Menus végétariens disponibles</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>Allergènes indiqués sur chaque plat</li>
                </ul>
                <a href="/menus" class="btn-accent">Voir nos menus →</a>
            </div>
            <div class="col-md-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 border rounded bg-white">
                            <div class="h3 fw-bold" style="color:var(--couleur-accent)">Julie</div>
                            <small class="text-muted">Chef cuisinière</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 border rounded bg-white">
                            <div class="h3 fw-bold" style="color:var(--couleur-accent)">José</div>
                            <small class="text-muted">Logistique & livraison</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Aperçu menus -->
<section class="py-5" style="background:#f8f4ee">
    <div class="container">
        <h2 class="section-title text-center mb-4">Nos menus phares</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="menu-preview-card">
                    <div class="menu-emoji">🎄</div>
                    <h5>Menu de Noël Traditionnel</h5>
                    <p class="text-muted small">Foie gras, dinde aux marrons, bûche maison</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <div class="menu-prix">89 €/pers.</div>
                            <small class="text-muted">min 4 personnes</small>
                        </div>
                        <a href="/menus" class="btn btn-sm btn-outline-secondary">Voir →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="menu-preview-card">
                    <div class="menu-emoji">🍽️</div>
                    <h5>Menu Classique Gastronomique</h5>
                    <p class="text-muted small">Velouté, risotto champignons, mousse chocolat</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <div class="menu-prix">45 €/pers.</div>
                            <small class="text-muted">min 2 personnes</small>
                        </div>
                        <a href="/menus" class="btn btn-sm btn-outline-secondary">Voir →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="menu-preview-card">
                    <div class="menu-emoji">🐣</div>
                    <h5>Menu Pâques Ensoleillé</h5>
                    <p class="text-muted small">Agneau de lait, légumes de saison, tarte fraises</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <div class="menu-prix">72 €/pers.</div>
                            <small class="text-muted">min 4 personnes</small>
                        </div>
                        <a href="/menus" class="btn btn-sm btn-outline-secondary">Voir →</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="/menus" class="btn-accent">Voir tous nos menus</a>
        </div>
    </div>
</section>

<!-- Avis clients -->
<?php if(!empty($avis)): ?>
<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-4">Avis de nos clients</h2>
        <div class="row g-3">
            <?php foreach($avis as $a): ?>
            <div class="col-md-4">
                <div class="avis-card">
                    <div class="etoiles">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $a['note'] ? '★' : '☆' ?>
                        <?php endfor; ?>
                    </div>
                    <p class="text-muted fst-italic small">"<?= htmlspecialchars($a['commentaire']) ?>"</p>
                    <small class="fw-bold">
                        — <?= htmlspecialchars($a['prenom'] . ' ' . substr($a['nom'], 0, 1) . '.') ?>
                    </small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="py-5 text-center" style="background-color:var(--couleur-principale);color:white">
    <div class="container">
        <h2 class="mb-3">Prêt à commander ?</h2>
        <p class="mb-4 opacity-75">Commandez en ligne en quelques clics.</p>
        <a href="/menus" class="btn-accent">Découvrir nos menus →</a>
    </div>
</section>

<!-- Footer -->
<footer class="text-center">
    <div class="container">
        <p class="mb-1">© <?= date('Y') ?> Vite & Gourmand — Traiteur Bordeaux</p>
        <p class="mb-2">
            <a href="/mentions-legales">Mentions légales</a> · 
            <a href="/cgv">CGV</a> · 
            <a href="/contact">Contact</a>
        </p>
        <small style="color:rgba(201,149,74,0.6)">
            Conçu avec ❤️ par <strong style="color:rgba(201,149,74,0.8)">Stéphane Gautier</strong>
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
