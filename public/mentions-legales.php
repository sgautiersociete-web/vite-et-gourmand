<?php
// Page mentions légales - Vite & Gourmand
// Développeur : Stéphane Gautier - TP DWWM Studi
$page_title = 'Mentions légales';
require '_header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Mentions légales</h1>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h5 class="section-title">Éditeur du site</h5>
                    <p><strong>Vite & Gourmand</strong> — Entreprise individuelle<br>
                    Représentants : Julie et José<br>
                    Bordeaux (33000), France<br>
                    Email : contact@viteetgourmand.fr</p>

                    <h5 class="section-title mt-4">Hébergement</h5>
                    <p>Railway — San Francisco, CA, États-Unis<br>
                    https://railway.app</p>

                    <h5 class="section-title mt-4">Propriété intellectuelle</h5>
                    <p>L'ensemble du contenu de ce site est la propriété exclusive de Vite & Gourmand.
                    Toute reproduction sans autorisation est interdite.</p>

                    <h5 class="section-title mt-4">Données personnelles (RGPD)</h5>
                    <p>Les données collectées (nom, prénom, email, adresse, GSM) sont utilisées
                    uniquement pour la gestion des commandes. Conformément au RGPD, vous disposez
                    d'un droit d'accès, de rectification et de suppression.
                    Contact : contact@viteetgourmand.fr</p>

                    <h5 class="section-title mt-4">Cookies</h5>
                    <p>Ce site utilise uniquement des cookies de session nécessaires au fonctionnement.
                    Aucun cookie publicitaire ou de tracking n'est utilisé.</p>

                    <hr class="my-4">

                    <!-- Section développeur importante pour l'examen -->
                    <div class="p-3 rounded" style="background:#4a2c0a;color:white">
                        <h5 style="color:var(--couleur-accent)">👨‍💻 Développement & Réalisation</h5>
                        <p class="mb-1">
                            <strong>Stéphane Gautier</strong> — Développeur Web & Web Mobile<br>
                            Formation : <strong>Titre Professionnel DWWM</strong> — Studi — 2026
                        </p>
                        <p class="mb-1 small" style="color:rgba(255,255,255,0.7)">
                            Stack : PHP 8.2 (MVC maison) · MySQL 8 · Bootstrap 5 · JS ES6+ · Railway
                        </p>
                        <p class="mb-0 small">
                            <a href="https://github.com/sgautiersociete-web/vite-et-gourmand"
                               target="_blank" style="color:var(--couleur-accent)">
                               → github.com/sgautiersociete-web/vite-et-gourmand
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require '_footer.php'; ?>
