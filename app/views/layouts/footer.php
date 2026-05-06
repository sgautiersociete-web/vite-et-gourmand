</main>

<!-- FOOTER -->
<footer class="bg-dark text-white pt-5 pb-3 mt-5" role="contentinfo">
    <div class="container">
        <div class="row g-4">
            <!-- Horaires -->
            <div class="col-md-4">
                <h3 class="h5 text-warning mb-3">
                    <i class="bi bi-clock" aria-hidden="true"></i> Horaires
                </h3>
                <?php
                require_once APP_PATH . '/models/HoraireModel.php';
                $horaireModel = new HoraireModel();
                $horaires     = $horaireModel->getAll();
                $jours = ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                foreach ($horaires as $h): ?>
                    <div class="d-flex justify-content-between small border-bottom border-secondary py-1">
                        <span><?= e($jours[$h['jour']]) ?></span>
                        <span class="<?= $h['ferme'] ? 'text-danger' : 'text-success' ?>">
                            <?= $h['ferme'] ? 'Fermé' : e($h['heure_ouverture']) . ' – ' . e($h['heure_fermeture']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Contact -->
            <div class="col-md-4">
                <h3 class="h5 text-warning mb-3">
                    <i class="bi bi-geo-alt" aria-hidden="true"></i> Contact
                </h3>
                <address class="small">
                    <strong>Vite &amp; Gourmand</strong><br>
                    Bordeaux, France<br>
                    <a href="mailto:contact@viteetgourmand.fr" class="text-white">
                        contact@viteetgourmand.fr
                    </a><br>
                    <a href="tel:+33556000000" class="text-white">05 56 00 00 00</a>
                </address>
            </div>

            <!-- Liens légaux -->
            <div class="col-md-4">
                <h3 class="h5 text-warning mb-3">
                    <i class="bi bi-info-circle" aria-hidden="true"></i> Informations
                </h3>
                <ul class="list-unstyled small">
                    <li><a href="/mentions-legales" class="text-white-50 text-decoration-none hover-white">Mentions légales</a></li>
                    <li><a href="/cgv" class="text-white-50 text-decoration-none hover-white">Conditions Générales de Vente</a></li>
                    <li><a href="/contact" class="text-white-50 text-decoration-none hover-white">Nous contacter</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="text-center text-white-50 small mb-0">
            &copy; <?= date('Y') ?> Vite &amp; Gourmand – Traiteur Bordeaux depuis 1999
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/app.js"></script>
</body>
</html>
