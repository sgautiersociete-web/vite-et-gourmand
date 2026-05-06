<?php
declare(strict_types=1);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();
try { $db = Database::getInstance(); $dbOk = true; } catch (Exception $e) { $dbOk = false; }
$avis = [];
if ($dbOk) { try { $stmt = $db->query("SELECT a.note, a.commentaire, u.nom, u.prenom FROM avis a JOIN utilisateur u ON u.utilisateur_id = a.utilisateur_id WHERE a.statut = 'valide' LIMIT 6"); $avis = $stmt->fetchAll(); } catch (Exception $e) {} }
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vite & Gourmand — Traiteur Bordeaux</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--gold:#C9954A;--gold-light:#E8C17A;--dark:#1A0F00;--cream:#FDF8F0;--warm:#F5EDD8;--text:#3D2B1A;--muted:#8A7460}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--text);overflow-x:hidden}
h1,h2,h3,.serif{font-family:'Playfair Display',serif}
nav{position:fixed;top:0;width:100%;z-index:100;padding:1.2rem 2rem;display:flex;justify-content:space-between;align-items:center;transition:all .4s;background:transparent}
nav.scrolled{background:var(--dark);box-shadow:0 4px 30px rgba(0,0,0,.3)}
.nav-logo{font-family:'Playfair Display',serif;font-size:1.4rem;color:#fff;text-decoration:none;letter-spacing:.05em}
.nav-logo span{color:var(--gold-light)}
.nav-links{display:flex;gap:2rem;align-items:center}
.nav-links a{color:rgba(255,255,255,.85);text-decoration:none;font-size:.9rem;letter-spacing:.08em;text-transform:uppercase;font-weight:500;transition:color .2s}
.nav-links a:hover{color:var(--gold-light)}
.btn-nav{background:var(--gold);color:var(--dark)!important;padding:.5rem 1.4rem;border-radius:2rem;font-weight:600!important}
.hero{min-height:100vh;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;background:var(--dark)}
.hero-bg{position:absolute;inset:0;background:radial-gradient(ellipse at 30% 50%,rgba(201,149,74,.15),transparent 60%),radial-gradient(ellipse at 70% 20%,rgba(201,149,74,.08),transparent 50%);z-index:1}
.hero-pattern{position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(201,149,74,.03) 0,rgba(201,149,74,.03) 1px,transparent 0,transparent 50%);background-size:30px 30px;z-index:1}
.hero-content{position:relative;z-index:2;text-align:center;padding:2rem;max-width:800px}
.hero-tag{display:inline-block;border:1px solid rgba(201,149,74,.5);color:var(--gold-light);font-size:.75rem;letter-spacing:.2em;text-transform:uppercase;padding:.4rem 1.2rem;border-radius:2rem;margin-bottom:2rem;animation:fadeUp .8s .2s both}
.hero h1{font-size:clamp(3rem,8vw,6rem);color:#fff;line-height:1;margin-bottom:1.5rem;animation:fadeUp .8s .4s both}
.hero h1 em{color:var(--gold-light);font-style:normal;display:block}
.hero-sub{color:rgba(255,255,255,.6);font-size:1.1rem;max-width:500px;margin:0 auto 3rem;line-height:1.7;animation:fadeUp .8s .6s both}
.hero-btns{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;animation:fadeUp .8s .8s both}
.btn-gold{background:var(--gold);color:var(--dark);padding:.9rem 2.5rem;border-radius:3rem;text-decoration:none;font-weight:600;transition:all .3s}
.btn-gold:hover{background:var(--gold-light);transform:translateY(-2px)}
.btn-ghost{border:1px solid rgba(255,255,255,.3);color:#fff;padding:.9rem 2.5rem;border-radius:3rem;text-decoration:none;transition:all .3s}
.btn-ghost:hover{border-color:var(--gold-light);color:var(--gold-light)}
.stats{background:var(--gold);padding:3rem 0}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);max-width:900px;margin:0 auto;text-align:center;padding:0 2rem}
.stat-num{font-family:'Playfair Display',serif;font-size:2.8rem;font-weight:900;color:var(--dark)}
.stat-label{font-size:.75rem;letter-spacing:.12em;text-transform:uppercase;opacity:.7;color:var(--dark)}
.section{padding:7rem 0}
.container{max-width:1100px;margin:0 auto;padding:0 2rem}
.section-tag{font-size:.75rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);font-weight:600;margin-bottom:1rem}
.section-title{font-size:clamp(2rem,4vw,3rem);color:var(--dark);line-height:1.1;margin-bottom:1.5rem}
.section-body{color:var(--muted);font-size:1.05rem;line-height:1.8;max-width:520px}
.about{background:var(--cream)}
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:6rem;align-items:center}
.about-card{background:var(--dark);border-radius:1.5rem;padding:3rem;color:#fff;position:relative;overflow:hidden}
.about-card::before{content:'';position:absolute;top:-30px;right:-30px;width:150px;height:150px;border-radius:50%;background:rgba(201,149,74,.15)}
.feature{display:flex;align-items:center;gap:1rem;padding:1rem;background:rgba(255,255,255,.05);border-radius:.75rem;border-left:3px solid var(--gold);margin-bottom:.75rem}
.feature-icon{color:var(--gold-light);font-size:1.2rem}
.feature-text{font-size:.9rem;color:rgba(255,255,255,.8)}
.menus-section{background:var(--warm)}
.menus-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3rem}
.menu-card{background:#fff;border-radius:1.5rem;overflow:hidden;transition:transform .3s,box-shadow .3s}
.menu-card:hover{transform:translateY(-8px);box-shadow:0 20px 50px rgba(58,43,26,.15)}
.menu-card-img{height:180px;display:flex;align-items:center;justify-content:center;font-size:4rem;background:linear-gradient(135deg,var(--dark),#3d2200)}
.menu-card-body{padding:1.5rem}
.menu-badge{display:inline-block;background:var(--warm);color:var(--gold);font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;padding:.3rem .8rem;border-radius:2rem;margin-bottom:.75rem;font-weight:600}
.menu-title{font-family:'Playfair Display',serif;font-size:1.15rem;color:var(--dark);margin-bottom:.5rem}
.menu-desc{color:var(--muted);font-size:.85rem;line-height:1.6;margin-bottom:1rem}
.menu-footer{display:flex;justify-content:space-between;align-items:center}
.menu-price{font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--gold);font-weight:700}
.menu-persons{font-size:.78rem;color:var(--muted)}
.btn-sm-dark{background:var(--dark);color:#fff;padding:.45rem 1.1rem;border-radius:2rem;text-decoration:none;font-size:.8rem;transition:background .2s}
.btn-sm-dark:hover{background:var(--gold);color:var(--dark)}
.avis-section{background:var(--dark);padding:7rem 0}
.avis-section .section-title{color:#fff}
.avis-section .section-tag{color:var(--gold-light)}
.avis-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3rem}
.avis-card{background:rgba(255,255,255,.05);border:1px solid rgba(201,149,74,.2);border-radius:1.5rem;padding:2rem;transition:border-color .3s}
.avis-card:hover{border-color:var(--gold)}
.stars{color:var(--gold-light);font-size:1.1rem;margin-bottom:1rem}
.avis-text{color:rgba(255,255,255,.7);font-size:.9rem;line-height:1.7;font-style:italic;margin-bottom:1.5rem}
.avis-author{color:rgba(255,255,255,.35);font-size:.8rem;letter-spacing:.1em;text-transform:uppercase}
.cta{background:var(--gold);padding:6rem 0;text-align:center}
.cta h2{font-size:clamp(2rem,4vw,3rem);color:var(--dark);margin-bottom:1rem}
.cta p{color:rgba(26,15,0,.6);font-size:1.05rem;margin-bottom:2.5rem}
footer{background:#0D0700;color:rgba(255,255,255,.4);padding:3rem 0;text-align:center}
footer a{color:rgba(201,149,74,.6);text-decoration:none}
footer a:hover{color:var(--gold-light)}
@keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
.reveal{opacity:0;transform:translateY(40px);transition:opacity .8s,transform .8s}
.reveal.visible{opacity:1;transform:none}
@media(max-width:768px){.about-grid,.menus-grid,.avis-grid{grid-template-columns:1fr}.stats-grid{grid-template-columns:repeat(2,1fr)}.nav-links{display:none}}
</style>
</head>
<body>
<nav id="navbar">
  <a href="/" class="nav-logo">Vite <span>&</span> Gourmand</a>
  <div class="nav-links">
    <a href="/">Accueil</a><a href="/menus">Menus</a><a href="/contact">Contact</a>
    <?php if(Session::isLoggedIn()): ?><a href="/espace-utilisateur">Mon espace</a><a href="/deconnexion">Déconnexion</a>
    <?php else: ?><a href="/connexion">Connexion</a><a href="/menus" class="btn-nav">Commander</a><?php endif; ?>
  </div>
</nav>
<section class="hero">
  <div class="hero-bg"></div><div class="hero-pattern"></div>
  <div class="hero-content">
    <div class="hero-tag">✦ Traiteur Bordeaux depuis 1999 ✦</div>
    <h1>L'art de<em>bien recevoir</em></h1>
    <p class="hero-sub">Des menus raffinés pour vos événements, préparés avec passion par Julie & José depuis 25 ans.</p>
    <div class="hero-btns">
      <a href="/menus" class="btn-gold">Découvrir nos menus</a>
      <a href="/contact" class="btn-ghost">Nous contacter</a>
    </div>
  </div>
</section>
<div class="stats">
  <div class="stats-grid">
    <div><div class="stat-num">25</div><div class="stat-label">Années d'expérience</div></div>
    <div><div class="stat-num">500+</div><div class="stat-label">Clients satisfaits</div></div>
    <div><div class="stat-num">4</div><div class="stat-label">Thèmes de menus</div></div>
    <div><div class="stat-num">2</div><div class="stat-label">Passionnés</div></div>
  </div>
</div>
<section class="section about reveal">
  <div class="container">
    <div class="about-grid">
      <div>
        <div class="section-tag">✦ Notre histoire</div>
        <h2 class="section-title">Une cuisine du cœur, depuis 1999</h2>
        <p class="section-body">Fondée par Julie et José, notre maison de traiteur s'est construite sur une promesse simple : offrir le meilleur de la gastronomie bordelaise pour chacun de vos moments de partage.</p>
        <p class="section-body" style="margin-top:1rem">Noël, Pâques, anniversaires ou événements professionnels — nous mettons notre savoir-faire au service de vos convives.</p>
        <a href="/menus" class="btn-gold" style="display:inline-block;margin-top:2rem">Voir nos menus →</a>
      </div>
      <div>
        <div class="about-card">
          <h3 class="serif" style="font-size:1.4rem;margin-bottom:1.5rem;color:var(--gold-light)">Ce qui nous distingue</h3>
          <div class="feature"><div class="feature-icon"><i class="bi bi-check-circle-fill"></i></div><div class="feature-text">Produits frais et locaux sélectionnés chaque semaine</div></div>
          <div class="feature"><div class="feature-icon"><i class="bi bi-truck"></i></div><div class="feature-text">Livraison à domicile dans tout Bordeaux et sa région</div></div>
          <div class="feature"><div class="feature-icon"><i class="bi bi-heart-fill"></i></div><div class="feature-text">Menus adaptés aux régimes spéciaux (végétarien, vegan…)</div></div>
          <div class="feature"><div class="feature-icon"><i class="bi bi-shield-check"></i></div><div class="feature-text">Respect des normes HACCP et traçabilité garantie</div></div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section menus-section reveal">
  <div class="container">
    <div class="section-tag">✦ Nos créations</div>
    <h2 class="section-title">Des menus pour chaque occasion</h2>
    <div class="menus-grid">
      <div class="menu-card"><div class="menu-card-img">🎄</div><div class="menu-card-body"><div class="menu-badge">Noël</div><div class="menu-title">Menu de Noël Traditionnel</div><div class="menu-desc">Foie gras mi-cuit, dinde farcie aux marrons, bûche maison chocolat-praliné.</div><div class="menu-footer"><div><div class="menu-price">89 €</div><div class="menu-persons">min 4 personnes</div></div><a href="/menus" class="btn-sm-dark">Voir →</a></div></div></div>
      <div class="menu-card"><div class="menu-card-img">🍽️</div><div class="menu-card-body"><div class="menu-badge">Classique</div><div class="menu-title">Menu Gastronomique</div><div class="menu-desc">Velouté de potimarron, risotto aux champignons des bois, mousse au chocolat.</div><div class="menu-footer"><div><div class="menu-price">45 €</div><div class="menu-persons">min 2 personnes</div></div><a href="/menus" class="btn-sm-dark">Voir →</a></div></div></div>
      <div class="menu-card"><div class="menu-card-img">🐣</div><div class="menu-card-body"><div class="menu-badge">Pâques</div><div class="menu-title">Menu Pâques Ensoleillé</div><div class="menu-desc">Salade de chèvre chaud, gigot d'agneau de lait, tarte aux fraises maison.</div><div class="menu-footer"><div><div class="menu-price">72 €</div><div class="menu-persons">min 4 personnes</div></div><a href="/menus" class="btn-sm-dark">Voir →</a></div></div></div>
    </div>
    <div style="text-align:center;margin-top:3rem"><a href="/menus" class="btn-gold">Voir tous nos menus</a></div>
  </div>
</section>
<?php if(!empty($avis)): ?>
<section class="avis-section reveal">
  <div class="container">
    <div class="section-tag">✦ Ce qu'ils disent</div>
    <h2 class="section-title">Nos clients témoignent</h2>
    <div class="avis-grid">
      <?php foreach($avis as $a): ?>
      <div class="avis-card">
        <div class="stars"><?php for($i=1;$i<=5;$i++) echo $i<=$a['note']?'★':'☆'; ?></div>
        <div class="avis-text">"<?= htmlspecialchars($a['commentaire']) ?>"</div>
        <div class="avis-author">— <?= htmlspecialchars($a['prenom'].' '.substr($a['nom'],0,1).'.') ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<section class="cta reveal">
  <div class="container">
    <h2>Prêt à régaler vos convives ?</h2>
    <p>Commandez en ligne en quelques clics, nous nous occupons du reste.</p>
    <a href="/menus" class="btn-gold" style="font-size:1.05rem;padding:1rem 3rem">Passer commande →</a>
  </div>
</section>
<footer>
  <div class="container">
    <p style="font-family:'Playfair Display',serif;font-size:1.2rem;color:rgba(255,255,255,.25);margin-bottom:1rem">Vite & Gourmand</p>
    <p><a href="/mentions-legales">Mentions légales</a> &nbsp;·&nbsp; <a href="/cgv">CGV</a> &nbsp;·&nbsp; <a href="/contact">Contact</a></p>
    <p style="margin-top:1rem;font-size:.8rem">© <?= date('Y') ?> Vite & Gourmand — Traiteur Bordeaux</p>
  </div>
</footer>
<script>
window.addEventListener('scroll',()=>document.getElementById('navbar').classList.toggle('scrolled',window.scrollY>50));
const obs=new IntersectionObserver(e=>e.forEach(el=>{if(el.isIntersecting)el.target.classList.add('visible')}),{threshold:.1});
document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
</script>
</body>
</html>
