<?php
if(!Session::isLoggedIn() || Session::user()['role'] !== 'administrateur') {
    header('Location: /connexion.php'); die();
}
$user = Session::user();
$db   = Database::getInstance();

$employes  = $db->query("SELECT u.*,r.libelle AS role_libelle FROM utilisateur u JOIN role r ON r.role_id=u.role_id WHERE r.libelle='employe' ORDER BY u.nom")->fetchAll();
$statsMenu = $db->query("SELECT m.titre, COUNT(c.commande_id) AS nb, SUM(c.prix_total) AS ca FROM commande c JOIN menu m ON m.menu_id=c.menu_id WHERE c.statut NOT IN ('annulee','en_attente') GROUP BY c.menu_id ORDER BY nb DESC")->fetchAll();

$tab     = $_GET['tab'] ?? 'stats';
$success = Session::getFlash('success');
$error   = Session::getFlash('error');
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Espace Admin — Vite & Gourmand</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
.page-header{background:var(--dark);padding:2.5rem 2rem}
.page-header-inner{max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;align-items:center}
.page-header h1{color:#fff;font-size:1.8rem;margin-bottom:.2rem}
.page-header p{color:rgba(255,255,255,.5);font-size:.85rem}
.role-badge{background:rgba(201,149,74,.2);color:var(--gold-light);padding:.4rem 1rem;border-radius:2rem;font-size:.8rem;border:1px solid rgba(201,149,74,.3)}
.container{max-width:1200px;margin:0 auto;padding:2rem}
.tabs{display:flex;gap:.5rem;margin-bottom:2rem;background:#fff;padding:.5rem;border-radius:1rem;width:fit-content;box-shadow:0 2px 10px rgba(58,43,26,.08)}
.tab{padding:.6rem 1.5rem;border-radius:.75rem;font-size:.85rem;font-weight:500;cursor:pointer;text-decoration:none;color:var(--muted);transition:all .2s}
.tab.active,.tab:hover{background:var(--dark);color:#fff}
.tab-content{display:none}
.tab-content.active{display:block}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:1rem 1.5rem;border-radius:1rem;margin-bottom:1.5rem;font-size:.9rem}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:1rem 1.5rem;border-radius:1rem;margin-bottom:1.5rem;font-size:.9rem}
/* STATS */
.stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:2rem}
.stat-card{background:#fff;border-radius:1.5rem;padding:2rem;border-left:4px solid var(--gold);box-shadow:0 2px 10px rgba(58,43,26,.05)}
.stat-num{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:var(--gold)}
.stat-label{font-size:.75rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);margin-top:.3rem}
.chart-card{background:#fff;border-radius:1.5rem;padding:2rem;box-shadow:0 2px 10px rgba(58,43,26,.05);margin-bottom:1.5rem}
.chart-card h3{font-size:1.1rem;margin-bottom:1.5rem;color:var(--dark)}
/* EMPLOYES */
.employe-card{background:#fff;border-radius:1.2rem;padding:1.5rem;margin-bottom:1rem;border:1px solid rgba(201,149,74,.1);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.employe-info h4{font-size:.95rem;font-weight:600;color:var(--dark);margin-bottom:.2rem}
.employe-info p{font-size:.8rem;color:var(--muted)}
.badge-actif{background:#dcfce7;color:#15803d;padding:.3rem .8rem;border-radius:2rem;font-size:.72rem;font-weight:600}
.badge-inactif{background:#fee2e2;color:#b91c1c;padding:.3rem .8rem;border-radius:2rem;font-size:.72rem;font-weight:600}
.btn-toggle{border:none;padding:.45rem 1rem;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.78rem;cursor:pointer;font-weight:600}
.btn-disable{background:#fee2e2;color:#b91c1c}
.btn-enable{background:#dcfce7;color:#15803d}
/* FORM */
.form-card{background:#fff;border-radius:1.5rem;padding:2rem;box-shadow:0 2px 10px rgba(58,43,26,.05);margin-bottom:2rem}
.form-card h3{font-size:1.1rem;margin-bottom:1.5rem;color:var(--dark)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-group{margin-bottom:1rem}
.form-label{display:block;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.4rem}
.form-input{width:100%;padding:.7rem 1rem;border:1.5px solid #e8ddd0;border-radius:.8rem;font-family:'DM Sans',sans-serif;font-size:.88rem;color:var(--text);background:var(--cream)}
.form-input:focus{outline:none;border-color:var(--gold)}
.pwd-hint{font-size:.72rem;color:var(--muted);margin-top:.3rem}
.btn-submit{background:var(--dark);color:#fff;border:none;padding:.75rem 2rem;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:500;cursor:pointer}
.btn-submit:hover{background:#3d2200}
@media(max-width:768px){.stats-grid{grid-template-columns:1fr 1fr}.form-row{grid-template-columns:1fr}.nav-links{display:none}}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-logo">Vite <span>&</span> Gourmand</a>
  <div class="nav-links">
    <a href="/">Accueil</a>
    <a href="/espace-admin.php">Admin</a>
    <a href="/espace-employe.php">Employé</a>
    <a href="/deconnexion.php" style="color:rgba(255,100,100,.7)">Déconnexion</a>
  </div>
</nav>

<div class="page-header">
  <div class="page-header-inner">
    <div><h1>Espace Administrateur</h1><p>Statistiques, employés et gestion complète</p></div>
    <div class="role-badge"><i class="bi bi-shield-check"></i> Administrateur</div>
  </div>
</div>

<div class="container">
  <?php if($success): ?><div class="alert-success"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if($error):   ?><div class="alert-error"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

  <div class="tabs">
    <a href="?tab=stats"    class="tab <?= $tab==='stats'?'active':'' ?>"><i class="bi bi-bar-chart"></i> Statistiques</a>
    <a href="?tab=employes" class="tab <?= $tab==='employes'?'active':'' ?>"><i class="bi bi-people"></i> Employés</a>
    <a href="?tab=commandes" class="tab <?= $tab==='commandes'?'active':'' ?>"><i class="bi bi-bag"></i> Commandes</a>
  </div>

  <!-- STATS -->
  <div class="tab-content <?= $tab==='stats'?'active':'' ?>">
    <?php
    $totalCA  = array_sum(array_column($statsMenu,'ca'));
    $totalCmd = array_sum(array_column($statsMenu,'nb'));
    $nbMenus  = count($statsMenu);
    ?>
    <div class="stats-grid">
      <div class="stat-card"><div class="stat-num"><?= number_format($totalCA,0,'.',' ') ?> €</div><div class="stat-label">Chiffre d'affaires total</div></div>
      <div class="stat-card"><div class="stat-num"><?= $totalCmd ?></div><div class="stat-label">Commandes validées</div></div>
      <div class="stat-card"><div class="stat-num"><?= $nbMenus ?></div><div class="stat-label">Menus actifs</div></div>
    </div>

    <div class="chart-card">
      <h3>📊 Commandes par menu</h3>
      <canvas id="chartCommandes" height="80"></canvas>
    </div>

    <div class="chart-card">
      <h3>💶 Chiffre d'affaires par menu</h3>
      <canvas id="chartCA" height="80"></canvas>
    </div>

    <script>
    const labels = <?= json_encode(array_column($statsMenu,'titre')) ?>;
    const nbData = <?= json_encode(array_column($statsMenu,'nb')) ?>;
    const caData = <?= json_encode(array_column($statsMenu,'ca')) ?>;
    const colors = ['#C9954A','#E8C17A','#1A0F00','#8A7460','#F5EDD8'];

    new Chart(document.getElementById('chartCommandes'), {
      type: 'bar',
      data: { labels, datasets: [{ label: 'Nb commandes', data: nbData, backgroundColor: colors }] },
      options: { responsive: true, plugins: { legend: { display: false } } }
    });
    new Chart(document.getElementById('chartCA'), {
      type: 'doughnut',
      data: { labels, datasets: [{ data: caData, backgroundColor: colors }] },
      options: { responsive: true }
    });
    </script>
  </div>

  <!-- EMPLOYES -->
  <div class="tab-content <?= $tab==='employes'?'active':'' ?>">
    <div class="form-card">
      <h3>➕ Créer un compte employé</h3>
      <form method="POST" action="/create-employe.php">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-input" placeholder="Dupont" required>
          </div>
          <div class="form-group">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-input" placeholder="Marie" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Email (identifiant)</label>
          <input type="email" name="email" class="form-input" placeholder="marie@viteetgourmand.fr" required>
        </div>
        <div class="form-group">
          <label class="form-label">Mot de passe temporaire</label>
          <input type="password" name="password" class="form-input" required>
          <div class="pwd-hint">10 car. min · 1 maj · 1 min · 1 chiffre · 1 spécial — Ne sera pas envoyé par mail</div>
        </div>
        <button type="submit" class="btn-submit">Créer le compte</button>
      </form>
    </div>

    <?php foreach($employes as $e): ?>
    <div class="employe-card">
      <div class="employe-info">
        <h4><?= htmlspecialchars($e['prenom'].' '.$e['nom']) ?></h4>
        <p><?= htmlspecialchars($e['email']) ?> · Créé le <?= date('d/m/Y',strtotime($e['created_at'])) ?></p>
      </div>
      <div style="display:flex;align-items:center;gap:1rem">
        <span class="<?= $e['actif']?'badge-actif':'badge-inactif' ?>"><?= $e['actif']?'Actif':'Inactif' ?></span>
        <form method="POST" action="/toggle-employe.php">
          <input type="hidden" name="employe_id" value="<?= $e['utilisateur_id'] ?>">
          <input type="hidden" name="actif" value="<?= $e['actif']?0:1 ?>">
          <button type="submit" class="btn-toggle <?= $e['actif']?'btn-disable':'btn-enable' ?>">
            <?= $e['actif']?'Désactiver':'Activer' ?>
          </button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- COMMANDES (même vue que employé) -->
  <div class="tab-content <?= $tab==='commandes'?'active':'' ?>">
    <p style="color:var(--muted);margin-bottom:1rem">Accédez à la gestion complète des commandes depuis l'espace employé.</p>
    <a href="/espace-employe.php?tab=commandes" style="background:var(--dark);color:#fff;padding:.75rem 2rem;border-radius:1rem;text-decoration:none;font-size:.9rem;font-weight:500">
      <i class="bi bi-arrow-right"></i> Gérer les commandes
    </a>
  </div>
</div>

<footer style="background:var(--dark);color:rgba(255,255,255,.4);padding:1.5rem;text-align:center;font-size:.82rem;margin-top:3rem">
  © <?= date('Y') ?> Vite & Gourmand
</footer>
</body>
</html>
