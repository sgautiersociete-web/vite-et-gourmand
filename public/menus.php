<?php
declare(strict_types=1);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();
try { $db = Database::getInstance(); } catch (Exception $e) { die('Erreur BD'); }

$themes  = $db->query("SELECT * FROM theme ORDER BY libelle")->fetchAll();
$regimes = $db->query("SELECT * FROM regime ORDER BY libelle")->fetchAll();
$menus   = $db->query("SELECT m.*, t.libelle AS theme, r.libelle AS regime FROM menu m JOIN theme t ON t.theme_id=m.theme_id JOIN regime r ON r.regime_id=m.regime_id WHERE m.actif=1 ORDER BY m.titre")->fetchAll();
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nos Menus — Vite & Gourmand</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--gold:#C9954A;--gold-light:#E8C17A;--dark:#1A0F00;--cream:#FDF8F0;--warm:#F5EDD8;--text:#3D2B1A;--muted:#8A7460}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--text)}
h1,h2,h3{font-family:'Playfair Display',serif}
nav{background:var(--dark);padding:1.2rem 2rem;display:flex;justify-content:space-between;align-items:center}
.nav-logo{font-family:'Playfair Display',serif;font-size:1.3rem;color:#fff;text-decoration:none}
.nav-logo span{color:var(--gold-light)}
.nav-links{display:flex;gap:2rem;align-items:center}
.nav-links a{color:rgba(255,255,255,.8);text-decoration:none;font-size:.85rem;letter-spacing:.08em;text-transform:uppercase;transition:color .2s}
.nav-links a:hover,.nav-links a.active{color:var(--gold-light)}
.btn-nav{background:var(--gold);color:var(--dark)!important;padding:.4rem 1.2rem;border-radius:2rem;font-weight:600!important}
.page-header{background:var(--dark);padding:4rem 2rem;text-align:center}
.page-header h1{color:#fff;font-size:clamp(2rem,5vw,3.5rem);margin-bottom:.5rem}
.page-header p{color:rgba(255,255,255,.5);font-size:1rem}
.container{max-width:1200px;margin:0 auto;padding:0 2rem}
.main-layout{display:grid;grid-template-columns:280px 1fr;gap:3rem;padding:3rem 2rem;max-width:1200px;margin:0 auto}
/* FILTRES */
.filters-panel{background:#fff;border-radius:1.5rem;padding:2rem;height:fit-content;position:sticky;top:2rem;box-shadow:0 4px 20px rgba(58,43,26,.08)}
.filters-title{font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--dark);margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid var(--warm)}
.filter-group{margin-bottom:1.5rem}
.filter-label{font-size:.75rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.6rem;display:block}
.filter-input{width:100%;padding:.6rem 1rem;border:1.5px solid #e8ddd0;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.9rem;color:var(--text);background:var(--cream);transition:border-color .2s}
.filter-input:focus{outline:none;border-color:var(--gold)}
.price-range{display:grid;grid-template-columns:1fr 1fr;gap:.5rem}
.btn-filter{width:100%;background:var(--dark);color:#fff;border:none;padding:.8rem;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:500;cursor:pointer;transition:background .2s;margin-bottom:.5rem}
.btn-filter:hover{background:#3d2200}
.btn-reset{width:100%;background:transparent;color:var(--muted);border:1.5px solid #e8ddd0;padding:.7rem;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.85rem;cursor:pointer;transition:all .2s}
.btn-reset:hover{border-color:var(--gold);color:var(--gold)}
/* GRILLE MENUS */
.menus-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem}
.menus-count{font-size:.9rem;color:var(--muted)}
.menus-count strong{color:var(--dark);font-size:1.1rem}
.menus-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem}
.menu-card{background:#fff;border-radius:1.5rem;overflow:hidden;transition:transform .3s,box-shadow .3s;border:1px solid rgba(201,149,74,.1)}
.menu-card:hover{transform:translateY(-6px);box-shadow:0 16px 40px rgba(58,43,26,.12)}
.menu-card-img{height:180px;display:flex;align-items:center;justify-content:center;font-size:3.5rem;background:linear-gradient(135deg,var(--dark) 0%,#3d2200 100%);position:relative}
.menu-stock{position:absolute;top:.75rem;right:.75rem;font-size:.7rem;padding:.3rem .7rem;border-radius:2rem;font-weight:600}
.stock-ok{background:rgba(40,167,69,.15);color:#28a745}
.stock-low{background:rgba(255,193,7,.15);color:#d4a00a}
.stock-out{background:rgba(220,53,69,.15);color:#dc3545}
.menu-card-body{padding:1.5rem}
.menu-badges{display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.75rem}
.badge-theme{background:var(--warm);color:var(--gold);font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;padding:.25rem .7rem;border-radius:2rem;font-weight:600}
.badge-regime{background:rgba(26,15,0,.07);color:var(--muted);font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;padding:.25rem .7rem;border-radius:2rem}
.menu-title{font-family:'Playfair Display',serif;font-size:1.15rem;color:var(--dark);margin-bottom:.5rem;line-height:1.3}
.menu-desc{color:var(--muted);font-size:.85rem;line-height:1.6;margin-bottom:1.2rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.menu-footer{display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--warm)}
.menu-price-block .price{font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--gold);font-weight:700;line-height:1}
.menu-price-block .persons{font-size:.75rem;color:var(--muted);margin-top:.1rem}
.btn-detail{background:var(--dark);color:#fff;padding:.5rem 1.2rem;border-radius:2rem;text-decoration:none;font-size:.8rem;font-weight:500;transition:background .2s;white-space:nowrap}
.btn-detail:hover{background:var(--gold);color:var(--dark)}
.no-results{text-align:center;padding:4rem 2rem;color:var(--muted)}
.no-results-icon{font-size:3rem;margin-bottom:1rem;opacity:.3}
footer{background:var(--dark);color:rgba(255,255,255,.4);padding:2.5rem 0;text-align:center;margin-top:5rem}
footer a{color:rgba(201,149,74,.6);text-decoration:none}
footer a:hover{color:var(--gold-light)}
@media(max-width:900px){.main-layout{grid-template-columns:1fr}.filters-panel{position:static}}
@media(max-width:600px){.menus-grid{grid-template-columns:1fr}.nav-links{display:none}}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-logo">Vite <span>&</span> Gourmand</a>
  <div class="nav-links">
    <a href="/">Accueil</a>
    <a href="/menus.php" class="active">Menus</a>
    <a href="/contact.php">Contact</a>
    <?php if(Session::isLoggedIn()): ?>
      <a href="/espace-utilisateur.php">Mon espace</a>
      <a href="/deconnexion.php">Déconnexion</a>
    <?php else: ?>
      <a href="/connexion.php">Connexion</a>
      <a href="/menus.php" class="btn-nav">Commander</a>
    <?php endif; ?>
  </div>
</nav>

<div class="page-header">
  <h1>Nos Menus</h1>
  <p>Découvrez toute notre offre pour vos événements</p>
</div>

<div class="main-layout">
  <!-- FILTRES -->
  <aside>
    <div class="filters-panel">
      <div class="filters-title">🔍 Filtrer</div>
      <form id="form-filtres">
        <div class="filter-group">
          <label class="filter-label">Fourchette de prix (€/pers)</label>
          <div class="price-range">
            <input type="number" class="filter-input" id="prix_min" placeholder="Min" min="0">
            <input type="number" class="filter-input" id="prix_max" placeholder="Max" min="0">
          </div>
        </div>
        <div class="filter-group">
          <label class="filter-label">Thème</label>
          <select class="filter-input" id="theme_id">
            <option value="">Tous les thèmes</option>
            <?php foreach($themes as $t): ?>
            <option value="<?= $t['theme_id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label">Régime alimentaire</label>
          <select class="filter-input" id="regime_id">
            <option value="">Tous les régimes</option>
            <?php foreach($regimes as $r): ?>
            <option value="<?= $r['regime_id'] ?>"><?= htmlspecialchars($r['libelle']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label">Nb personnes minimum</label>
          <input type="number" class="filter-input" id="nb_min" placeholder="Ex : 4" min="1">
        </div>
        <button type="submit" class="btn-filter">Rechercher</button>
        <button type="button" id="btn-reset" class="btn-reset">Réinitialiser</button>
      </form>
    </div>
  </aside>

  <!-- MENUS -->
  <div>
    <div class="menus-header">
      <div class="menus-count"><strong id="count"><?= count($menus) ?></strong> menu(s) disponible(s)</div>
    </div>
    <div class="menus-grid" id="menus-grid">
      <?php foreach($menus as $m): ?>
      <?php
        $emojis = ['Noël'=>'🎄','Pâques'=>'🐣','classique'=>'🍽️','événement'=>'🥂','anniversaire'=>'🎂'];
        $emoji  = $emojis[$m['theme']] ?? '🍽️';
        $stock  = $m['quantite_restante'];
        $stockClass = $stock > 5 ? 'stock-ok' : ($stock > 0 ? 'stock-low' : 'stock-out');
        $stockLabel = $stock > 5 ? $stock.' dispo' : ($stock > 0 ? 'Plus que '.$stock : 'Complet');
      ?>
      <div class="menu-card"
           data-prix="<?= $m['prix'] ?>"
           data-theme="<?= $m['theme_id'] ?>"
           data-regime="<?= $m['regime_id'] ?>"
           data-nb="<?= $m['nb_personnes_min'] ?>">
        <div class="menu-card-img">
          <?= $emoji ?>
          <span class="menu-stock <?= $stockClass ?>"><?= $stockLabel ?></span>
        </div>
        <div class="menu-card-body">
          <div class="menu-badges">
            <span class="badge-theme"><?= htmlspecialchars($m['theme']) ?></span>
            <span class="badge-regime"><?= htmlspecialchars($m['regime']) ?></span>
          </div>
          <div class="menu-title"><?= htmlspecialchars($m['titre']) ?></div>
          <div class="menu-desc"><?= htmlspecialchars($m['description']) ?></div>
          <div class="menu-footer">
            <div class="menu-price-block">
              <div class="price"><?= number_format($m['prix'],2) ?> €</div>
              <div class="persons"><i class="bi bi-people"></i> min <?= $m['nb_personnes_min'] ?> pers.</div>
            </div>
            <a href="/menu-detail.php?id=<?= $m['menu_id'] ?>" class="btn-detail">Voir le détail →</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div id="no-results" class="no-results" style="display:none">
      <div class="no-results-icon">🔍</div>
      <p>Aucun menu ne correspond à vos critères.</p>
    </div>
  </div>
</div>

<footer>
  <p style="font-family:'Playfair Display',serif;color:rgba(255,255,255,.2);margin-bottom:.5rem">Vite & Gourmand</p>
  <p><a href="/mentions-legales.php">Mentions légales</a> · <a href="/cgv.php">CGV</a> · <a href="/contact.php">Contact</a></p>
  <p style="margin-top:.5rem;font-size:.8rem">© <?= date('Y') ?> Vite & Gourmand</p>
</footer>

<script>
const cards = [...document.querySelectorAll('.menu-card')];
const grid  = document.getElementById('menus-grid');
const noRes = document.getElementById('no-results');
const count = document.getElementById('count');

function filterMenus() {
  const pMin = parseFloat(document.getElementById('prix_min').value) || 0;
  const pMax = parseFloat(document.getElementById('prix_max').value) || Infinity;
  const theme = document.getElementById('theme_id').value;
  const regime = document.getElementById('regime_id').value;
  const nb = parseInt(document.getElementById('nb_min').value) || 0;

  let visible = 0;
  cards.forEach(card => {
    const prix   = parseFloat(card.dataset.prix);
    const show   = prix >= pMin && prix <= pMax
                && (!theme  || card.dataset.theme  === theme)
                && (!regime || card.dataset.regime === regime)
                && (nb === 0 || parseInt(card.dataset.nb) <= nb);
    card.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  count.textContent = visible;
  noRes.style.display = visible === 0 ? 'block' : 'none';
}

document.getElementById('form-filtres').addEventListener('submit', e => { e.preventDefault(); filterMenus(); });
document.getElementById('btn-reset').addEventListener('click', () => {
  document.getElementById('form-filtres').reset();
  filterMenus();
});
</script>
</body>
</html>
