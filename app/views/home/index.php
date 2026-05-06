<?php
// app/views/home/index.php
$pageTitle = 'Accueil';
require_once APP_PATH . '/views/layouts/header.php';
require_once APP_PATH . '/models/AvisModel.php';
$avisModel = new AvisModel();
$avis = $avisModel->getValides();
?>

<!-- HERO -->
<section class="hero" aria-label="Présentation Vite et Gourmand">
    <div class="container">
        <p class="text-warning fw-bold mb-2">Traiteur Bordeaux depuis 25 ans</p>
        <h1>Vite &amp; Gourmand</h1>
        <p>Des menus raffinés pour tous vos événements.<br>Commandez en ligne en quelques clics.</p>
        <a href="/menus" class="btn btn-gold btn-lg me-2">
            <i class="bi bi-card-list" aria-hidden="true"></i> Voir nos menus
        </a>
        <a href="/contact" class="btn btn-outline-light btn-lg">
            <i class="bi bi-envelope" aria-hidden="true"></i> Nous contacter
        </a>
    </div>
</section>

<!-- PRÉSENTATION -->
<section class="py-5 bg-vg-light" aria-labelledby="about-title">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <h2 id="about-title" class="text-vg fw-bold mb-3">Notre histoire</h2>
                <p>Fondée en 1999 par <strong>Julie</strong> et <strong>José</strong>, notre entreprise familiale
                   s'est bâtie sur une passion commune : offrir une cuisine authentique et généreuse pour
                   tous vos moments de partage.</p>
                <p>De Noël à Pâques, des repas d'entreprise aux anniversaires, nous mettons notre
                   savoir-faire au service de vos événements avec des produits frais et locaux.</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle-fill text-gold me-2" aria-hidden="true"></i>25 ans d'expérience</li>
                    <li><i class="bi bi-check-circle-fill text-gold me-2" aria-hidden="true"></i>Produits frais et locaux</li>
                    <li><i class="bi bi-check-circle-fill text-gold me-2" aria-hidden="true"></i>Livraison à domicile</li>
                    <li><i class="bi bi-check-circle-fill text-gold me-2" aria-hidden="true"></i>Menus adaptés aux régimes spéciaux</li>
                </ul>
            </div>
            <div class="col-md-6 text-center">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">25</div>
                            <div class="text-muted">Années d'expérience</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">500+</div>
                            <div class="text-muted">Clients satisfaits</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">2</div>
                            <div class="text-muted">Personnes passionnées</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">4+</div>
                            <div class="text-muted">Thèmes de menus</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NOTRE ÉQUIPE / PROFESSIONNALISME -->
<section class="py-5" aria-labelledby="team-title">
    <div class="container">
        <h2 id="team-title" class="text-center text-vg fw-bold mb-5">Une équipe passionnée</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 text-center">
                <div class="rounded-circle bg-vg d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:100px;height:100px">
                    <i class="bi bi-person-fill text-white" style="font-size:3rem" aria-hidden="true"></i>
                </div>
                <h3 class="h5">Julie</h3>
                <p class="text-muted">Chef cuisinière, créatrice des menus et responsable de la qualité gustative.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="rounded-circle bg-gold d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:100px;height:100px;background:var(--vg-gold)!important">
                    <i class="bi bi-person-fill text-white" style="font-size:3rem" aria-hidden="true"></i>
                </div>
                <h3 class="h5">José</h3>
                <p class="text-muted">Responsable logistique et livraison, garant du respect des délais et de la fraîcheur.</p>
            </div>
        </div>
    </div>
</section>

<!-- AVIS CLIENTS -->
<?php if (!empty($avis)): ?>
<section class="py-5 bg-vg-light" aria-labelledby="avis-title">
    <div class="container">
        <h2 id="avis-title" class="text-center text-vg fw-bold mb-5">
            <i class="bi bi-star-fill text-gold" aria-hidden="true"></i> Avis clients
        </h2>
        <div class="row g-4">
            <?php foreach ($avis as $a): ?>
                <div class="col-md-4">
                    <article class="avis-card h-100">
                        <div class="stars mb-2" aria-label="Note : <?= $a['note'] ?> sur 5">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi <?= $i <= $a['note'] ? 'bi-star-fill' : 'bi-star' ?>" aria-hidden="true"></i>
                            <?php endfor; ?>
                        </div>
                        <blockquote class="mb-2 fst-italic">"<?= e($a['commentaire']) ?>"</blockquote>
                        <footer class="text-muted small">
                            — <?= e($a['prenom'] . ' ' . substr($a['nom'], 0, 1) . '.') ?>
                        </footer>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="py-5 text-center bg-vg text-white" aria-label="Appel à l'action">
    <div class="container">
        <h2 class="fw-bold mb-3">Prêt à commander ?</h2>
        <p class="mb-4">Découvrez nos menus et passez commande en quelques clics.</p>
        <a href="/menus" class="btn btn-gold btn-lg">
            <i class="bi bi-arrow-right" aria-hidden="true"></i> Découvrir nos menus
        </a>
    </div>
</section>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
