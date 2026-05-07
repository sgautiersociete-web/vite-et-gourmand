<?php
// Page mentions légales - Vite & Gourmand
// Développeur : Stéphane Gautier - Formation TP DWWM - Studi
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
.back{color:rgba(255,255,255,.6);text-decoration:none;font-size:.85rem;transition:color .2s}
.back:hover{color:var(--gold)}
.content{max-width:800px;margin:4rem auto;padding:0 2rem}
h1{font-family:'Playfair Display',serif;font-size:2.5rem;margin-bottom:2rem;color:var(--dark)}
h2{font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--dark);margin:2rem 0 .75rem;padding-bottom:.5rem;border-bottom:1px solid #e8ddd0}
p{color:var(--muted);line-height:1.8;margin-bottom:1rem;font-size:.95rem}
.dev-card{background:var(--dark);color:#fff;border-radius:1.5rem;padding:2rem;margin:3rem 0;border-left:4px solid var(--gold)}
.dev-card h2{color:var(--gold);border-bottom-color:rgba(201,149,74,.3)}
.dev-card p{color:rgba(255,255,255,.7)}
.dev-card strong{color:#fff}
footer{background:var(--dark);color:rgba(255,255,255,.4);padding:2rem;text-align:center;font-size:.82rem;margin-top:5rem}
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
    <p>
        <strong>Vite & Gourmand</strong> — Entreprise individuelle<br>
        Représentants légaux : Julie et José<br>
        Siège social : Bordeaux (33000), France<br>
        Email : contact@viteetgourmand.fr<br>
        Téléphone : 05 56 00 00 00
    </p>

    <h2>Hébergement</h2>
    <p>
        Ce site est hébergé par <strong>Railway</strong><br>
        San Francisco, CA, États-Unis<br>
        https://railway.app
    </p>

    <h2>Propriété intellectuelle</h2>
    <p>
        L'ensemble du contenu de ce site (textes, images, logos, code source) est la propriété 
        exclusive de Vite & Gourmand. Toute reproduction, même partielle, sans autorisation 
        écrite préalable est strictement interdite.
    </p>

    <h2>Données personnelles (RGPD)</h2>
    <p>
        Les données collectées lors de votre inscription (nom, prénom, email, adresse, GSM) 
        sont utilisées uniquement dans le cadre de la gestion de vos commandes et ne sont 
        jamais transmises à des tiers.
    </p>
    <p>
        Conformément au Règlement Général sur la Protection des Données (RGPD - Règlement UE 2016/679), 
        vous disposez d'un droit d'accès, de rectification, de suppression et de portabilité 
        de vos données personnelles.
    </p>
    <p>
        Pour exercer ces droits, contactez-nous à : 
        <a href="mailto:contact@viteetgourmand.fr" style="color:var(--gold)">contact@viteetgourmand.fr</a>
    </p>

    <h2>Cookies</h2>
    <p>
        Ce site utilise uniquement des cookies de session strictement nécessaires au 
        fonctionnement de l'application (maintien de la connexion utilisateur). 
        Aucun cookie publicitaire ou de tracking n'est utilisé.
    </p>

    <h2>Responsabilité</h2>
    <p>
        Vite & Gourmand s'efforce d'assurer l'exactitude et la mise à jour des informations 
        diffusées sur ce site. Cependant, nous déclinons toute responsabilité quant aux 
        erreurs ou omissions qui pourraient subsister.
    </p>

    <!-- Section développeur - importante pour l'examen -->
    <div class="dev-card">
        <h2>👨‍💻 Développement & Réalisation</h2>
        <p>
            Cette application web a été entièrement conçue, développée et déployée par :<br><br>
            <strong>Stéphane Gautier</strong><br>
            Développeur Web & Web Mobile<br>
            Formation : Titre Professionnel DWWM (Développeur Web et Web Mobile)<br>
            Organisme de formation : <strong>Studi</strong><br><br>
            Stack technique utilisée : <strong>PHP 8.2</strong> (architecture MVC maison), 
            <strong>MySQL 8</strong> (base relationnelle), <strong>HTML5 / CSS3 / JavaScript ES6+</strong>, 
            <strong>Bootstrap Icons</strong>, déployé sur <strong>Railway</strong>.<br><br>
            Dépôt GitHub public : 
            <a href="https://github.com/sgautiersociete-web/vite-et-gourmand" 
               style="color:var(--gold)" target="_blank">
               github.com/sgautiersociete-web/vite-et-gourmand
            </a>
        </p>
    </div>
</div>

<footer>
    <p>© <?= date('Y') ?> Vite & Gourmand</p>
    <p style="margin-top:.5rem">
        <a href="/cgv">CGV</a> · <a href="/contact">Contact</a>
    </p>
    <p style="margin-top:.75rem;color:rgba(201,149,74,.5);font-size:.8rem">
        Conçu avec ❤️ par <strong style="color:rgba(201,149,74,.7)">Stéphane Gautier</strong>
    </p>
</footer>
</body>
</html>
