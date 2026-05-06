<?php
// app/views/menus/index.php
$pageTitle = 'Nos Menus';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="bg-vg text-white py-4">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-card-list" aria-hidden="true"></i> Nos Menus</h1>
        <p class="mb-0 opacity-75">Découvrez toute notre offre de menus pour vos événements</p>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        <!-- FILTRES -->
        <aside class="col-md-3" aria-label="Filtres de recherche">
            <div id="filtres-panel">
                <h2 class="h5 text-vg mb-3">
                    <i class="bi bi-funnel" aria-hidden="true"></i> Filtrer les menus
                </h2>
                <form id="form-filtres" aria-label="Formulaire de filtres">
                    <div class="mb-3">
                        <label for="prix_min" class="form-label fw-semibold">Prix minimum (€)</label>
                        <input type="number" class="form-control" id="prix_min" name="prix_min"
                               min="0" step="1" placeholder="0">
                    </div>
                    <div class="mb-3">
                        <label for="prix_max" class="form-label fw-semibold">Prix maximum (€)</label>
                        <input type="number" class="form-control" id="prix_max" name="prix_max"
                               min="0" step="1" placeholder="500">
                    </div>
                    <div class="mb-3">
                        <label for="theme_id" class="form-label fw-semibold">Thème</label>
                        <select class="form-select" id="theme_id" name="theme_id">
                            <option value="">Tous les thèmes</option>
                            <?php foreach ($themes as $t): ?>
                                <option value="<?= $t['theme_id'] ?>"><?= e($t['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="regime_id" class="form-label fw-semibold">Régime</label>
                        <select class="form-select" id="regime_id" name="regime_id">
                            <option value="">Tous les régimes</option>
                            <?php foreach ($regimes as $r): ?>
                                <option value="<?= $r['regime_id'] ?>"><?= e($r['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nb_min" class="form-label fw-semibold">Nb personnes minimum</label>
                        <input type="number" class="form-control" id="nb_min" name="nb_min"
                               min="1" placeholder="Ex : 4">
                    </div>
                    <button type="submit" class="btn btn-vg w-100">
                        <i class="bi bi-search" aria-hidden="true"></i> Rechercher
                    </button>
                    <button type="button" id="btn-reset" class="btn btn-outline-secondary w-100 mt-2">
                        Réinitialiser
                    </button>
                </form>
            </div>
        </aside>

        <!-- LISTE DES MENUS -->
        <section class="col-md-9" aria-label="Liste des menus" aria-live="polite" aria-atomic="true">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 text-vg mb-0">
                    <span id="menus-count"><?= count($menus) ?></span> menu(s) disponible(s)
                </h2>
            </div>

            <div class="row g-3" id="menus-grid">
                <?php if (empty($menus)): ?>
                    <p class="text-muted">Aucun menu disponible pour le moment.</p>
                <?php endif; ?>
                <?php foreach ($menus as $m): ?>
                    <div class="col-md-6 col-lg-4 menu-item">
                        <article class="menu-card card">
                            <img src="/<?= e($m['image'] ?? 'images/placeholder.jpg') ?>"
                                 alt="Illustration du menu <?= e($m['titre']) ?>"
                                 class="card-img-top"
                                 loading="lazy">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge-theme me-1"><?= e($m['theme']) ?></span>
                                    <span class="badge-regime"><?= e($m['regime']) ?></span>
                                </div>
                                <h3 class="card-title h6 fw-bold"><?= e($m['titre']) ?></h3>
                                <p class="card-text small text-muted flex-grow-1">
                                    <?= e(mb_substr($m['description'], 0, 100)) ?>…
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <div class="fw-bold text-vg"><?= number_format($m['prix'], 2) ?> €</div>
                                        <div class="small text-muted">
                                            <i class="bi bi-people" aria-hidden="true"></i>
                                            Min <?= $m['nb_personnes_min'] ?> pers.
                                        </div>
                                    </div>
                                    <?php if ($m['quantite_restante'] > 0): ?>
                                        <span class="badge bg-success">
                                            <?= $m['quantite_restante'] ?> dispo.
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Complet</span>
                                    <?php endif; ?>
                                </div>
                                <a href="/menus/detail?id=<?= $m['menu_id'] ?>"
                                   class="btn btn-vg btn-sm mt-3"
                                   aria-label="Voir le détail du menu <?= e($m['titre']) ?>">
                                    <i class="bi bi-eye" aria-hidden="true"></i> Voir le détail
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Résultat vide après filtre -->
            <div id="no-results" class="text-center py-5 d-none">
                <i class="bi bi-search display-4 text-muted" aria-hidden="true"></i>
                <p class="mt-3 text-muted">Aucun menu ne correspond à vos critères.</p>
            </div>
        </section>
    </div>
</div>

<script>
// Filtres dynamiques AJAX
(function() {
    const form     = document.getElementById('form-filtres');
    const grid     = document.getElementById('menus-grid');
    const count    = document.getElementById('menus-count');
    const noResult = document.getElementById('no-results');

    function renderMenus(menus) {
        grid.innerHTML = '';
        if (!menus.length) {
            noResult.classList.remove('d-none');
            count.textContent = '0';
            return;
        }
        noResult.classList.add('d-none');
        count.textContent = menus.length;
        menus.forEach(m => {
            grid.innerHTML += `
            <div class="col-md-6 col-lg-4 menu-item">
              <article class="menu-card card">
                <div class="card-body d-flex flex-column">
                  <div class="mb-2">
                    <span class="badge-theme me-1">${m.theme}</span>
                    <span class="badge-regime">${m.regime}</span>
                  </div>
                  <h3 class="card-title h6 fw-bold">${m.titre}</h3>
                  <p class="card-text small text-muted flex-grow-1">${m.description.substring(0,100)}…</p>
                  <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                      <div class="fw-bold text-vg">${parseFloat(m.prix).toFixed(2)} €</div>
                      <div class="small text-muted">Min ${m.nb_personnes_min} pers.</div>
                    </div>
                    ${m.quantite_restante > 0
                        ? `<span class="badge bg-success">${m.quantite_restante} dispo.</span>`
                        : `<span class="badge bg-danger">Complet</span>`}
                  </div>
                  <a href="/menus/detail?id=${m.menu_id}" class="btn btn-vg btn-sm mt-3"
                     aria-label="Voir le détail du menu ${m.titre}">
                    <i class="bi bi-eye" aria-hidden="true"></i> Voir le détail
                  </a>
                </div>
              </article>
            </div>`;
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const data = new FormData(form);
        fetch('/menus/filter', { method: 'POST', body: data })
            .then(r => r.json())
            .then(res => { if (res.success) renderMenus(res.menus); });
    });

    document.getElementById('btn-reset').addEventListener('click', function() {
        form.reset();
        form.dispatchEvent(new Event('submit'));
    });
})();
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
