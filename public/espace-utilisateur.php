<?php
if(!Session::isLoggedIn()) { header('Location: /connexion'); die(); }
$user = Session::user();
try { $db = Database::getInstance(); } catch(Exception $e) { die('Erreur BD'); }

// Récupération commandes
$stmt = $db->prepare("SELECT c.*, m.titre AS menu_titre FROM commande c JOIN menu m ON m.menu_id=c.menu_id WHERE c.utilisateur_id=:id ORDER BY c.created_at DESC");
$stmt->execute(['id' => $user['id']]);
$commandes = $stmt->fetchAll();

// Récupération infos utilisateur
$stmt = $db->prepare("SELECT * FROM utilisateur WHERE utilisateur_id=:id");
$stmt->execute(['id' => $user['id']]);
$userInfo = $stmt->fetch();

$success = $_GET['success'] ?? '';
$tab = $_GET['tab'] ?? 'commandes';
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mon Espace — Vite & Gourmand</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
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
.nav-links a:hover{color:var(--gold-light)}
.btn-nav{background:var(--gold);color:var(--dark)!important;padding:.4rem 1.2rem;border-radius:2rem;font-weight:600!important}
.page-header{background:var(--dark);padding:3rem 2rem}
.page-header-inner{max-width:1100px;margin:0 auto;display:flex;justify-content:space-between;align-items:center}
.page-header h1{color:#fff;font-size:2rem;margin-bottom:.3rem}
.page-header p{color:rgba(255,255,255,.5);font-size:.9rem}
.welcome-badge{background:rgba(201,149,74,.2);color:var(--gold-light);padding:.5rem 1.2rem;border-radius:2rem;font-size:.85rem;border:1px solid rgba(201,149,74,.3)}
.container{max-width:1100px;margin:0 auto;padding:2rem}
.tabs{display:flex;gap:.5rem;margin-bottom:2rem;background:#fff;padding:.5rem;border-radius:1rem;width:fit-content;box-shadow:0 2px 10px rgba(58,43,26,.08)}
.tab{padding:.6rem 1.5rem;border-radius:.75rem;font-size:.85rem;font-weight:500;cursor:pointer;text-decoration:none;color:var(--muted);transition:all .2s}
.tab.active,.tab:hover{background:var(--dark);color:#fff}
.tab-content{display:none}
.tab-content.active{display:block}
.success-msg{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:1rem 1.5rem;border-radius:1rem;margin-bottom:2rem;font-size:.9rem}
/* COMMANDES */
.commande-card{background:#fff;border-radius:1.5rem;padding:2rem;margin-bottom:1.5rem;border:1px solid rgba(201,149,74,.1);box-shadow:0 2px 10px rgba(58,43,26,.05)}
.commande-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem}
.commande-num{font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--dark)}
.commande-date{font-size:.8rem;color:var(--muted);margin-top:.2rem}
.statut{display:inline-block;padding:.35rem .9rem;border-radius:2rem;font-size:.75rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase}
.statut-en_attente{background:#fef9c3;color:#a16207}
.statut-accepte{background:#dbeafe;color:#1d4ed8}
.statut-en_preparation{background:#f3e8ff;color:#7c3aed}
.statut-en_livraison{background:#fed7aa;color:#c2410c}
.statut-livre{background:#d1fae5;color:#065f46}
.statut-terminee{background:#dcfce7;color:#15803d}
.statut-annulee{background:#fee2e2;color:#b91c1c}
.statut-attente_materiel{background:#fef3c7;color:#92400e}
.commande-info{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;background:var(--warm);border-radius:1rem;padding:1rem 1.5rem;margin-bottom:1.5rem}
.info-item label{font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:.2rem}
.info-item span{font-size:.9rem;color:var(--dark);font-weight:500}
.commande-actions{display:flex;gap:.75rem;flex-wrap:wrap}
.btn-action{padding:.5rem 1.2rem;border-radius:.75rem;font-size:.8rem;font-weight:500;cursor:pointer;text-decoration:none;border:none;font-family:'DM Sans',sans-serif;transition:all .2s}
.btn-cancel{background:#fee2e2;color:#b91c1c}
.btn-cancel:hover{background:#fecaca}
.btn-avis{background:var(--warm);color:var(--gold)}
.btn-avis:hover{background:var(--gold);color:#fff}
.btn-suivi{background:var(--dark);color:#fff}
.btn-suivi:hover{background:#3d2200}
/* TIMELINE */
.timeline{padding:1rem 0}
.timeline-item{display:flex;gap:1rem;margin-bottom:1rem}
.timeline-dot{width:12px;height:12px;border-radius:50%;background:var(--gold);margin-top:.35rem;flex-shrink:0}
.timeline-content label{font-size:.75rem;color:var(--muted)}
.timeline-content span{display:block;font-size:.9rem;color:var(--dark);font-weight:500}
/* AVIS FORM */
.avis-form{background:var(--warm);border-radius:1rem;padding:1.5rem;margin-top:1rem}
.stars-input{display:flex;gap:.5rem;margin:.5rem 0 1rem;flex-direction:row-reverse;justify-content:flex-end}
.stars-input input{display:none}
.stars-input label{font-size:1.8rem;color:#d1d5db;cursor:pointer;transition:color .2s}
.stars-input input:checked ~ label,.stars-input label:hover,.stars-input label:hover ~ label{color:var(--gold-light)}
/* PROFIL */
.profil-form{background:#fff;border-radius:1.5rem;padding:2rem;box-shadow:0 2px 10px rgba(58,43,26,.05)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-group{margin-bottom:1.2rem}
.form-label{display:block;font-size:.75rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.5rem}
.form-input{width:100%;padding:.8rem 1.1rem;border:1.5px solid #e8ddd0;border-radius:.9rem;font-family:'DM Sans',sans-serif;font-size:.9rem;color:var(--text);background:var(--cream);transition:border-color .2s}
.form-input:focus{outline:none;border-color:var(--gold)}
.btn-submit{background:var(--dark);color:#fff;border:none;padding:.8rem 2rem;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:500;cursor:pointer;transition:background .2s}
.btn-submit:hover{background:#3d2200}
.empty{text-align:center;padding:4rem;color:var(--muted)}
.empty-icon{font-size:3rem;opacity:.3;margin-bottom:1rem}
footer{background:var(--dark);color:rgba(255,255,255,.4);padding:2rem;text-align:center;margin-top:3rem;font-size:.85rem}
footer a{color:rgba(201,149,74,.6);text-decoration:none}
@media(max-width:768px){.commande-info{grid-template-columns:1fr 1fr}.form-row{grid-template-columns:1fr}.nav-links{display:none}}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-logo">Vite <span>&</span> Gourmand</a>
  <div class="nav-links">
    <a href="/">Accueil</a>
    <a href="/menus">Menus</a>
    <a href="/espace-utilisateur" class="active">Mon espace</a>
    <a href="/deconnexion" style="color:rgba(255,100,100,.7)">Déconnexion</a>
  </div>
</nav>

<div class="page-header">
  <div class="page-header-inner">
    <div>
      <h1>Bonjour, <?= htmlspecialchars($user['prenom']) ?> 👋</h1>
      <p>Bienvenue dans votre espace personnel</p>
    </div>
    <div class="welcome-badge"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($user['email']) ?></div>
  </div>
</div>

<div class="container">
  <?php if($success): ?>
  <div class="success-msg"><i class="bi bi-check-circle"></i> Commande passée avec succès !</div>
  <?php endif; ?>

  <div class="tabs">
    <a href="?tab=commandes" class="tab <?= $tab==='commandes'?'active':'' ?>"><i class="bi bi-bag"></i> Mes commandes</a>
    <a href="?tab=profil" class="tab <?= $tab==='profil'?'active':'' ?>"><i class="bi bi-person"></i> Mon profil</a>
  </div>

  <!-- COMMANDES -->
  <div class="tab-content <?= $tab==='commandes'?'active':'' ?>">
    <?php if(empty($commandes)): ?>
    <div class="empty">
      <div class="empty-icon">🛒</div>
      <p>Vous n'avez pas encore de commande.</p>
      <a href="/menus" style="color:var(--gold);font-weight:600">Découvrir nos menus →</a>
    </div>
    <?php else: ?>
    <?php foreach($commandes as $c): ?>
    <div class="commande-card">
      <div class="commande-header">
        <div>
          <div class="commande-num"><?= htmlspecialchars($c['menu_titre']) ?></div>
          <div class="commande-date">Commande #<?= htmlspecialchars($c['numero_commande']) ?> · <?= date('d/m/Y', strtotime($c['created_at'])) ?></div>
        </div>
        <span class="statut statut-<?= $c['statut'] ?>"><?= str_replace('_',' ',$c['statut']) ?></span>
      </div>

      <div class="commande-info">
        <div class="info-item"><label>Date prestation</label><span><?= date('d/m/Y', strtotime($c['date_prestation'])) ?></span></div>
        <div class="info-item"><label>Heure livraison</label><span><?= htmlspecialchars($c['heure_livraison']) ?></span></div>
        <div class="info-item"><label>Nb personnes</label><span><?= $c['nb_personnes'] ?> pers.</span></div>
        <div class="info-item"><label>Livraison</label><span><?= htmlspecialchars($c['adresse_livraison'].', '.$c['ville_livraison']) ?></span></div>
        <div class="info-item"><label>Prix total</label><span style="color:var(--gold);font-weight:700"><?= number_format($c['prix_total'],2) ?> €</span></div>
        <?php if($c['reduction'] > 0): ?>
        <div class="info-item"><label>Réduction</label><span style="color:#16a34a">-<?= number_format($c['reduction'],2) ?> €</span></div>
        <?php endif; ?>
      </div>

      <?php
      // Historique si accepté
      if(in_array($c['statut'],['accepte','en_preparation','en_livraison','livre','attente_materiel','terminee'])):
        $hStmt = $db->prepare("SELECT * FROM commande_historique WHERE commande_id=:id ORDER BY created_at ASC");
        $hStmt->execute(['id'=>$c['commande_id']]);
        $historique = $hStmt->fetchAll();
      ?>
      <div style="margin-bottom:1rem">
        <div style="font-size:.75rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:.75rem">Suivi de commande</div>
        <div class="timeline">
          <?php foreach($historique as $h): ?>
          <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <span><?= str_replace('_',' ',htmlspecialchars($h['statut'])) ?></span>
              <label><?= date('d/m/Y H:i', strtotime($h['created_at'])) ?></label>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="commande-actions">
        <?php if($c['statut'] === 'en_attente'): ?>
        <form method="POST" action="/annuler-commande" onsubmit="return confirm('Annuler cette commande ?')">
          <input type="hidden" name="commande_id" value="<?= $c['commande_id'] ?>">
          <button type="submit" class="btn-action btn-cancel"><i class="bi bi-x-circle"></i> Annuler</button>
        </form>
        <?php endif; ?>

        <?php if($c['statut'] === 'terminee'): ?>
        <?php
        $avisStmt = $db->prepare("SELECT avis_id FROM avis WHERE commande_id=:id LIMIT 1");
        $avisStmt->execute(['id'=>$c['commande_id']]);
        $hasAvis = $avisStmt->fetch();
        ?>
        <?php if(!$hasAvis): ?>
        <div style="width:100%">
          <div class="avis-form">
            <div style="font-size:.85rem;font-weight:600;color:var(--dark);margin-bottom:.5rem">Donner votre avis</div>
            <form method="POST" action="/soumettre-avis">
              <input type="hidden" name="commande_id" value="<?= $c['commande_id'] ?>">
              <div class="stars-input">
                <?php for($i=5;$i>=1;$i--): ?>
                <input type="radio" name="note" id="star<?= $c['commande_id'].'_'.$i ?>" value="<?= $i ?>" required>
                <label for="star<?= $c['commande_id'].'_'.$i ?>">★</label>
                <?php endfor; ?>
              </div>
              <textarea name="commentaire" placeholder="Votre commentaire..." required style="width:100%;padding:.75rem;border:1.5px solid #e8ddd0;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.85rem;resize:vertical;min-height:80px;margin-bottom:.75rem"></textarea>
              <button type="submit" class="btn-action btn-suivi">Envoyer mon avis</button>
            </form>
          </div>
        </div>
        <?php else: ?>
        <span style="font-size:.85rem;color:#16a34a"><i class="bi bi-check-circle"></i> Avis déposé — en attente de validation</span>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- PROFIL -->
  <div class="tab-content <?= $tab==='profil'?'active':'' ?>">
    <div class="profil-form">
      <h2 style="font-size:1.3rem;margin-bottom:1.5rem">Mes informations</h2>
      <form method="POST" action="/modifier-profil">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-input" value="<?= htmlspecialchars($userInfo['nom']) ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-input" value="<?= htmlspecialchars($userInfo['prenom']) ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" class="form-input" value="<?= htmlspecialchars($userInfo['email']) ?>" disabled style="opacity:.6">
        </div>
        <div class="form-group">
          <label class="form-label">GSM</label>
          <input type="tel" name="gsm" class="form-input" value="<?= htmlspecialchars($userInfo['gsm']) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Adresse</label>
          <input type="text" name="adresse" class="form-input" value="<?= htmlspecialchars($userInfo['adresse']) ?>">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Ville</label>
            <input type="text" name="ville" class="form-input" value="<?= htmlspecialchars($userInfo['ville']) ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Code postal</label>
            <input type="text" name="code_postal" class="form-input" value="<?= htmlspecialchars($userInfo['code_postal']) ?>">
          </div>
        </div>
        <button type="submit" class="btn-submit">Enregistrer les modifications</button>
      </form>
    </div>
  </div>
</div>

<footer>
  <p>© <?= date('Y') ?> Vite & Gourmand · <a href="/mentions-legales">Mentions légales</a> · <a href="/cgv">CGV</a></p>
</footer>
</body>
</html>
