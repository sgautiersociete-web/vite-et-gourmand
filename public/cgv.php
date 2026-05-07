<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Session.php';
Session::start();
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CGV — Vite & Gourmand</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--gold:#C9954A;--dark:#1A0F00;--cream:#FDF8F0;--text:#3D2B1A;--muted:#8A7460}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--text)}
nav{background:var(--dark);padding:1.2rem 2rem;display:flex;justify-content:space-between;align-items:center}
.nav-logo{font-family:'Playfair Display',serif;font-size:1.3rem;color:#fff;text-decoration:none}
.nav-logo span{color:var(--gold)}
.back{color:rgba(255,255,255,.6);text-decoration:none;font-size:.85rem}
.content{max-width:800px;margin:4rem auto;padding:0 2rem}
h1{font-family:'Playfair Display',serif;font-size:2.5rem;margin-bottom:2rem;color:var(--dark)}
h2{font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--dark);margin:2rem 0 .75rem}
p{color:var(--muted);line-height:1.8;margin-bottom:1rem;font-size:.95rem}
footer{background:var(--dark);color:rgba(255,255,255,.4);padding:1.5rem;text-align:center;font-size:.82rem;margin-top:5rem}
footer a{color:rgba(201,149,74,.6);text-decoration:none}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-logo">Vite <span>&</span> Gourmand</a>
  <a href="/" class="back">← Retour à l'accueil</a>
</nav>
<div class="content">
  <h1>Conditions Générales de Vente</h1>
  <h2>1. Objet</h2>
  <p>Les présentes CGV régissent les relations contractuelles entre Vite & Gourmand et ses clients dans le cadre de la commande de prestations de traiteur.</p>
  <h2>2. Commandes</h2>
  <p>Toute commande doit être passée au minimum selon les délais indiqués sur chaque menu. Une commande est confirmée après validation par notre équipe. Le client reçoit un email de confirmation.</p>
  <h2>3. Prix et paiement</h2>
  <p>Les prix sont indiqués par personne, hors frais de livraison. Une réduction de 10% est appliquée pour toute commande dépassant de 5 personnes ou plus le minimum du menu. Les frais de livraison hors Bordeaux sont de 5€ + 0,59€/km.</p>
  <h2>4. Livraison</h2>
  <p>La livraison est assurée par l'équipe Vite & Gourmand. En cas de livraison à Bordeaux, aucun frais supplémentaire n'est appliqué. Pour toute livraison hors Bordeaux, des frais kilométriques s'appliquent.</p>
  <h2>5. Annulation</h2>
  <p>Une commande peut être annulée par le client tant qu'elle n'a pas été acceptée par notre équipe. Après acceptation, toute annulation nécessite un contact préalable avec notre équipe.</p>
  <h2>6. Matériel prêté</h2>
  <p>En cas de prêt de matériel, celui-ci doit être restitué dans un délai de 10 jours ouvrés. En cas de non-restitution dans ce délai, des frais de 600€ seront facturés au client.</p>
  <h2>7. Responsabilité</h2>
  <p>Vite & Gourmand s'engage à respecter les normes d'hygiène alimentaire (HACCP) et la traçabilité des produits. Les allergènes sont indiqués sur chaque plat.</p>
  <h2>8. Litiges</h2>
  <p>En cas de litige, une solution amiable sera recherchée en priorité. À défaut, le tribunal compétent sera celui de Bordeaux.</p>
</div>
<footer>
  © <?= date('Y') ?> Vite & Gourmand · <a href="/mentions-legales.php">Mentions légales</a> · <a href="/contact.php">Contact</a>
</footer>
</body>
</html>
