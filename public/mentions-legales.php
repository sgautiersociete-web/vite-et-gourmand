<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Session.php';
Session::start();
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mentions légales — Vite & Gourmand</title>
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
  <h1>Mentions légales</h1>
  <h2>Éditeur du site</h2>
  <p>Vite & Gourmand — Entreprise individuelle<br>Représentants légaux : Julie et José<br>Siège social : Bordeaux (33000), France<br>Email : contact@viteetgourmand.fr<br>Téléphone : 05 56 00 00 00</p>
  <h2>Hébergement</h2>
  <p>Le site est hébergé par Railway (San Francisco, CA, États-Unis) — https://railway.app</p>
  <h2>Propriété intellectuelle</h2>
  <p>L'ensemble du contenu de ce site (textes, images, logos) est la propriété exclusive de Vite & Gourmand. Toute reproduction sans autorisation est interdite.</p>
  <h2>Données personnelles (RGPD)</h2>
  <p>Les données collectées lors de votre inscription (nom, prénom, email, adresse, GSM) sont utilisées uniquement dans le cadre de la gestion de vos commandes. Conformément au RGPD, vous disposez d'un droit d'accès, de rectification et de suppression de vos données. Pour exercer ce droit, contactez-nous à contact@viteetgourmand.fr.</p>
  <h2>Cookies</h2>
  <p>Ce site utilise uniquement des cookies de session nécessaires au fonctionnement de l'application. Aucun cookie publicitaire n'est utilisé.</p>
</div>
<footer>
  © <?= date('Y') ?> Vite & Gourmand · <a href="/cgv.php">CGV</a> · <a href="/contact.php">Contact</a>
</footer>
</body>
</html>
