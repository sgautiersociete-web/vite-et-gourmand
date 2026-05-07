<?php
// Page détail d'un menu - Vite & Gourmand
// Développeur : Stéphane Gautier

$id = (int)($_GET['id'] ?? 0);

if(!$id) {
    header('Location: /menus');
    exit;
}

$db = Database::getInstance();

// récupération du menu
$stmt = $db->prepare("SELECT m.*, t.libelle AS theme, r.libelle AS regime 
                      FROM menu m 
                      JOIN theme t ON t.theme_id = m.theme_id 
                      JOIN regime r ON r.regime_id = m.regime_id 
                      WHERE m.menu_id = :id AND m.actif = 1 
                      LIMIT 1");
$stmt->execute(['id' => $id]);
$menu = $stmt->fetch();

if(!$menu) {
    header('Location: /menus');
    exit;
}

// récupération des plats du menu
$stmt = $db->prepare("SELECT p.*, GROUP_CONCAT(a.libelle ORDER BY a.libelle SEPARATOR ', ') AS allergenes
                      FROM plat p
                      JOIN menu_plat mp ON mp.plat_id = p.plat_id
                      LEFT JOIN plat_allergene pa ON pa.plat_id = p.plat_id
                      LEFT JOIN allergene a ON a.allergene_id = pa.allergene_id
                      WHERE mp.menu_id = :id
                      GROUP BY p.plat_id
                      ORDER BY FIELD(p.type_plat, 'entree', 'plat', 'dessert')");
$stmt->execute(['id' => $id]);
$plats = $stmt->fetchAll();

// grouper les plats par type
$platsGroupes = ['entree' => [], 'plat' => [], 'dessert' => []];
foreach($plats as $plat) {
    $platsGroupes[$plat['type_plat']][] = $plat;
}

$emojisType = ['entree' => '🥗', 'plat' => '🍖', 'dessert' => '🍰'];
$labelsType = ['entree' => 'Entrées', 'plat' => 'Plats', 'dessert' => 'Desserts'];
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($menu['titre']) ?> — Vite & Gourmand</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--gold:#C9954A;--gold-light:#E8C17A;--dark:#1A0F00;--cream:#FDF8F0;--warm:#F5EDD8;--text:#3D2B1A;--muted:#8A7460}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--text)}
h1,h2,h3{font-family:'Playfair Display',serif}
nav{background:var(--dark);padding:1.2rem 2rem;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100}
.nav-logo{font-family:'Playfair Display',serif;font-size:1.3rem;color:#fff;text-decoration:none}
.nav-logo span{color:var(--gold-light)}
.nav-links{display:flex;gap:2rem;align-items:center}
.nav-links a{color:rgba(255,255,255,.8);text-decoration:none;font-size:.85rem;text-transform:uppercase;transition:color .2s}
.nav-links a:hover{color:var(--gold-light)}
.btn-nav{background:var(--gold);color:var(--dark)!important;padding:.4rem 1.2rem;border-radius:2rem;font-weight:600!important}
.hero-menu{background:var(--dark);padding:5rem 2rem;text-align:center;position:relative;overflow:hidden}
.hero-menu::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(201,149,74,.15),transparent 70%)}
.hero-emoji{font-size:5rem;margin-bottom:1rem;display:block}
.hero-badge{display:inline-block;background:rgba(201,149,74,.2);color:var(--gold-light);border:1px solid rgba(201,149,74,.4);padding:.4rem 1.2rem;border-radius:2rem;font-size:.75rem;letter-spacing:.15em;text-transform:uppercase;margin-bottom:1.5rem}
.hero-menu h1{color:#fff;font-size:clamp(2rem,5vw,3.5rem);margin-bottom:1rem}
.hero-menu p{color:rgba(255,255,255,.6);font-size:1.05rem;max-width:600px;margin:0 auto}
.container{max-width:1000px;margin:0 auto;padding:0 2rem}
.content-grid{display:grid;grid-template-columns:2fr 1fr;gap:3rem;padding:3rem 0}

/* infos menu */
.section-label{font-size:.72rem;letter-spacing:.15em;text-transform:uppercase;color:var(--gold);font-weight:600;margin-bottom:1rem}
.conditions-alert{background:#fff8e6;border-left:4px solid var(--gold);padding:1.5rem;border-radius:0 1rem 1rem 0;margin-bottom:2rem}
.conditions-alert h3{font-size:1rem;color:var(--dark);margin-bottom:.5rem}
.conditions-alert p{color:var(--muted);font-size:.9rem;line-height:1.6}

/* plats */
.plats-section{margin-bottom:2rem}
.plat-type-title{display:flex;align-items:center;gap:.75rem;font-size:1.1rem;color:var(--dark);margin-bottom:1rem;padding-bottom:.75rem;border-bottom:2px solid var(--warm)}
.plat-card{background:#fff;border-radius:1rem;padding:1.2rem 1.5rem;margin-bottom:.75rem;border:1px solid rgba(201,149,74,.1);transition:box-shadow .2s}
.plat-card:hover{box-shadow:0 4px 16px rgba(58,43,26,.08)}
.plat-name{font-weight:600;color:var(--dark);margin-bottom:.3rem}
.plat-desc{font-size:.83rem;color:var(--muted);line-height:1.5;margin-bottom:.5rem}
.allergenes{display:flex;flex-wrap:wrap;gap:.3rem}
.allergene-badge{background:#fef3c7;color:#92400e;font-size:.68rem;padding:.2rem .6rem;border-radius:2rem;font-weight:500}

/* sidebar commande */
.sidebar{position:sticky;top:6rem;height:fit-content}
.price-card{background:#fff;border-radius:1.5rem;padding:2rem;box-shadow:0 4px 20px rgba(58,43,26,.08);margin-bottom:1.5rem}
.price-card h3{font-size:1rem;color:var(--dark);margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--warm)}
.price-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem;font-size:.9rem}
.price-row .label{color:var(--muted)}
.price-row .value{color:var(--dark);font-weight:500}
.price-total{display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:2px solid var(--warm);margin-top:.5rem}
.price-total .label{color:var(--dark);font-weight:600}
.price-total .value{font-family:'Playfair Display',serif;font-size:1.8rem;color:var(--gold);font-weight:700}
.stock-info{text-align:center;padding:.75rem;border-radius:.75rem;font-size:.85rem;font-weight:500;margin-bottom:1.5rem}
.stock-ok{background:#dcfce7;color:#15803d}
.stock-low{background:#fef9c3;color:#a16207}
.stock-out{background:#fee2e2;color:#b91c1c}
.btn-commander{display:block;width:100%;background:var(--gold);color:var(--dark);border:none;padding:1.1rem;border-radius:1rem;font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;cursor:pointer;text-decoration:none;text-align:center;transition:all .3s;margin-bottom:.75rem}
.btn-commander:hover{background:var(--gold-light);transform:translateY(-2px);box-shadow:0 8px 24px rgba(201,149,74,.3)}
.btn-retour{display:block;text-align:center;color:var(--muted);text-decoration:none;font-size:.85rem;transition:color .2s}
.btn-retour:hover{color:var(--gold)}

/* infos badges */
.info-badges{display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:2rem}
.info-badge{display:flex;align-items:center;gap:.4rem;background:#fff;border:1px solid rgba(201,149,74,.2);padding:.5rem 1rem;border-radius:2rem;font-size:.82rem;color:var(--text)}
.info-badge i{color:var(--gold)}

footer{background:var(--dark);color:rgba(255,255,255,.4);padding:2rem;text-align:center;font-size:.82rem;margin-top:5rem}
footer a{color:rgba(201,149,74,.6);text-decoration:none}
@media(max-width:768px){.content-grid{grid-template-columns:1fr}.sidebar{position:static}.nav-links{display:none}}
</style>
</head>
<body>

<nav>
    <a href="/" class="nav-logo">Vite <span>&</span> Gourmand</a>
    <div class="nav-links">
        <a href="/">Accueil</a>
        <a href="/menus">Menus</a>
        <a href="/contact">Contact</a>
        <?php if(Session::isLoggedIn()): ?>
            <a href="/espace-utilisateur">Mon espace</a>
            <a href="/deconnexion" style="color:rgba(255,100,100,.7)">Déconnexion</a>
        <?php else: ?>
            <a href="/connexion">Connexion</a>
            <a href="/menus" class="btn-nav">Commander</a>
        <?php endif; ?>
    </div>
</nav>

<?php
$emojis = ['Noël'=>'🎄','Pâques'=>'🐣','classique'=>'🍽️','événement'=>'🥂','anniversaire'=>'🎂'];
$emoji  = $emojis[$menu['theme']] ?? '🍽️';
$stock  = (int)$menu['quantite_restante'];
?>

<div class="hero-menu">
    <span class="hero-emoji"><?= $emoji ?></span>
    <div class="hero-badge"><?= htmlspecialchars($menu['theme']) ?> · <?= htmlspecialchars($menu['regime']) ?></div>
    <h1><?= htmlspecialchars($menu['titre']) ?></h1>
    <p><?= htmlspecialchars($menu['description']) ?></p>
</div>

<div class="container">
    <div class="content-grid">

        <!-- contenu principal -->
        <div>
            <div class="info-badges">
                <span class="info-badge"><i class="bi bi-people"></i> Min <?= $menu['nb_personnes_min'] ?> personnes</span>
                <span class="info-badge"><i class="bi bi-tag"></i> <?= number_format($menu['prix'], 2, ',', ' ') ?> € / pers.</span>
                <span class="info-badge"><i class="bi bi-flower1"></i> <?= htmlspecialchars($menu['regime']) ?></span>
                <span class="info-badge"><i class="bi bi-calendar-event"></i> <?= htmlspecialchars($menu['theme']) ?></span>
            </div>

            <?php if($menu['conditions']): ?>
            <div class="conditions-alert">
                <h3>⚠️ Conditions importantes</h3>
                <p><?= htmlspecialchars($menu['conditions']) ?></p>
            </div>
            <?php endif; ?>

            <!-- plats par type -->
            <?php foreach($platsGroupes as $type => $platsType): ?>
            <?php if(!empty($platsType)): ?>
            <div class="plats-section">
                <div class="plat-type-title">
                    <span><?= $emojisType[$type] ?></span>
                    <span><?= $labelsType[$type] ?></span>
                </div>
                <?php foreach($platsType as $plat): ?>
                <div class="plat-card">
                    <div class="plat-name"><?= htmlspecialchars($plat['nom']) ?></div>
                    <?php if($plat['description']): ?>
                    <div class="plat-desc"><?= htmlspecialchars($plat['description']) ?></div>
                    <?php endif; ?>
                    <?php if($plat['allergenes']): ?>
                    <div class="allergenes">
                        <?php foreach(explode(', ', $plat['allergenes']) as $al): ?>
                        <span class="allergene-badge">⚠️ <?= htmlspecialchars(trim($al)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>

            <?php if(empty($plats)): ?>
            <p style="color:var(--muted);font-style:italic">Les détails des plats seront bientôt disponibles.</p>
            <?php endif; ?>
        </div>

        <!-- sidebar commande -->
        <div class="sidebar">
            <div class="price-card">
                <h3>💶 Tarif</h3>
                <div class="price-row">
                    <span class="label">Prix par personne</span>
                    <span class="value"><?= number_format($menu['prix'], 2, ',', ' ') ?> €</span>
                </div>
                <div class="price-row">
                    <span class="label">Minimum</span>
                    <span class="value"><?= $menu['nb_personnes_min'] ?> personnes</span>
                </div>
                <div class="price-total">
                    <span class="label">Total min.</span>
                    <span class="value"><?= number_format($menu['prix'] * $menu['nb_personnes_min'], 2, ',', ' ') ?> €</span>
                </div>
            </div>

            <?php if($stock > 5): ?>
                <div class="stock-info stock-ok"><i class="bi bi-check-circle"></i> <?= $stock ?> commandes disponibles</div>
            <?php elseif($stock > 0): ?>
                <div class="stock-info stock-low"><i class="bi bi-exclamation-circle"></i> Plus que <?= $stock ?> disponible(s) !</div>
            <?php else: ?>
                <div class="stock-info stock-out"><i class="bi bi-x-circle"></i> Complet</div>
            <?php endif; ?>

            <?php if($stock > 0): ?>
                <?php if(Session::isLoggedIn()): ?>
                    <a href="/commande?menu_id=<?= $menu['menu_id'] ?>" class="btn-commander">
                        Commander ce menu →
                    </a>
                <?php else: ?>
                    <a href="/connexion" class="btn-commander">
                        Se connecter pour commander →
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <a href="/menus" class="btn-retour">← Retour aux menus</a>
        </div>

    </div>
</div>

<footer>
    <p>© <?= date('Y') ?> Vite & Gourmand</p>
    <p style="margin-top:.5rem"><a href="/mentions-legales">Mentions légales</a> · <a href="/cgv">CGV</a></p>
    <p style="margin-top:.75rem;color:rgba(201,149,74,.5);font-size:.8rem">
        Conçu avec ❤️ par <strong style="color:rgba(201,149,74,.7)">Stéphane Gautier</strong>
    </p>
</footer>

</body>
</html>
