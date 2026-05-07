<?php
if(!Session::isLoggedIn() || !in_array(Session::user()['role'],['employe','administrateur'])) {
    header('Location: /connexion'); die();
}
$user = Session::user();
$db   = Database::getInstance();
$filterStatut = $_GET['statut'] ?? '';
$filterEmail  = $_GET['email']  ?? '';
$where  = ['1=1']; $params = [];
if($filterStatut) { $where[] = 'c.statut=:statut'; $params['statut'] = $filterStatut; }
if($filterEmail)  { $where[] = 'c.email_client LIKE :email'; $params['email'] = '%'.$filterEmail.'%'; }
$stmt = $db->prepare("SELECT c.*, m.titre AS menu_titre FROM commande c JOIN menu m ON m.menu_id=c.menu_id WHERE ".implode(' AND ',$where)." ORDER BY c.created_at DESC");
$stmt->execute($params);
$commandes = $stmt->fetchAll();
$avis = $db->query("SELECT a.*, u.nom, u.prenom, m.titre AS menu_titre FROM avis a JOIN utilisateur u ON u.utilisateur_id=a.utilisateur_id JOIN commande c ON c.commande_id=a.commande_id JOIN menu m ON m.menu_id=c.menu_id WHERE a.statut='en_attente' ORDER BY a.created_at DESC")->fetchAll();
$menus   = $db->query("SELECT m.*, t.libelle AS theme, r.libelle AS regime FROM menu m JOIN theme t ON t.theme_id=m.theme_id JOIN regime r ON r.regime_id=m.regime_id ORDER BY m.titre")->fetchAll();
$themes  = $db->query("SELECT * FROM theme ORDER BY libelle")->fetchAll();
$regimes = $db->query("SELECT * FROM regime ORDER BY libelle")->fetchAll();
$tab     = $_GET['tab'] ?? 'commandes';
$success = Session::getFlash('success');
$error   = Session::getFlash('error');
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Espace Employé — Vite & Gourmand</title>
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
.nav-links a{color:rgba(255,255,255,.8);text-decoration:none;font-size:.85rem;text-transform:uppercase;transition:color .2s}
.nav-links a:hover{color:var(--gold-light)}
.page-header{background:var(--dark);padding:2.5rem 2rem}
.page-header-inner{max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;align-items:center}
.page-header h1{color:#fff;font-size:1.8rem;margin-bottom:.2rem}
.page-header p{color:rgba(255,255,255,.5);font-size:.85rem}
.role-badge{background:rgba(201,149,74,.2);color:var(--gold-light);padding:.4rem 1rem;border-radius:2rem;font-size:.8rem;border:1px solid rgba(201,149,74,.3)}
.container{max-width:1200px;margin:0 auto;padding:2rem}
.tabs{display:flex;gap:.5rem;margin-bottom:2rem;background:#fff;padding:.5rem;border-radius:1rem;width:fit-content;box-shadow:0 2px 10px rgba(58,43,26,.08)}
.tab{padding:.6rem 1.5rem;border-radius:.75rem;font-size:.85rem;font-weight:500;text-decoration:none;color:var(--muted);transition:all .2s}
.tab.active,.tab:hover{background:var(--dark);color:#fff}
.tab-content{display:none}.tab-content.active{display:block}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:1rem 1.5rem;border-radius:1rem;margin-bottom:1.5rem;font-size:.9rem}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:1rem 1.5rem;border-radius:1rem;margin-bottom:1.5rem;font-size:.9rem}
.filters{background:#fff;border-radius:1.5rem;padding:1.5rem;margin-bottom:2rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;box-shadow:0 2px 10px rgba(58,43,26,.05)}
.filter-group{display:flex;flex-direction:column;gap:.4rem;flex:1;min-width:150px}
.filter-label{font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);font-weight:600}
.filter-input{padding:.6rem 1rem;border:1.5px solid #e8ddd0;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.85rem;color:var(--text);background:var(--cream)}
.btn-filter{background:var(--dark);color:#fff;border:none;padding:.65rem 1.5rem;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.85rem;cursor:pointer}
.cmd-card{background:#fff;border-radius:1.5rem;padding:1.5rem;margin-bottom:1.2rem;border:1px solid rgba(201,149,74,.1);box-shadow:0 2px 8px rgba(58,43,26,.05)}
.cmd-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;flex-wrap:wrap;gap:.5rem}
.cmd-title{font-family:'Playfair Display',serif;font-size:1rem;color:var(--dark)}
.cmd-sub{font-size:.8rem;color:var(--muted);margin-top:.2rem}
.statut{display:inline-block;padding:.3rem .8rem;border-radius:2rem;font-size:.7rem;font-weight:600;text-transform:uppercase}
.statut-en_attente{background:#fef9c3;color:#a16207}.statut-accepte{background:#dbeafe;color:#1d4ed8}
.statut-en_preparation{background:#f3e8ff;color:#7c3aed}.statut-en_livraison{background:#fed7aa;color:#c2410c}
.statut-livre{background:#d1fae5;color:#065f46}.statut-terminee{background:#dcfce7;color:#15803d}
.statut-annulee{background:#fee2e2;color:#b91c1c}.statut-attente_materiel{background:#fef3c7;color:#92400e}
.cmd-info{display:flex;gap:2rem;flex-wrap:wrap;margin-bottom:1rem;padding:.75rem 1rem;background:var(--warm);border-radius:.75rem;font-size:.82rem}
.update-form{display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end;padding-top:1rem;border-top:1px solid var(--warm)}
.select-statut{padding:.55rem 1rem;border:1.5px solid #e8ddd0;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.82rem;background:var(--cream);flex:1;min-width:180px}
.input-motif{padding:.55rem 1rem;border:1.5px solid #e8ddd0;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.82rem;flex:2;min-width:200px}
.select-mode{padding:.55rem 1rem;border:1.5px solid #e8ddd0;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.82rem;background:var(--cream)}
.btn-update{background:var(--dark);color:#fff;border:none;padding:.55rem 1.2rem;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.82rem;cursor:pointer}
.avis-card{background:#fff;border-radius:1.2rem;padding:1.5rem;margin-bottom:1rem;border:1px solid rgba(201,149,74,.1);display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap}
.avis-stars{color:var(--gold-light);font-size:1rem;margin-bottom:.3rem}
.avis-text{font-size:.85rem;color:var(--muted);font-style:italic;margin-bottom:.3rem}
.avis-author{font-size:.75rem;color:var(--dark);font-weight:600}
.avis-actions{display:flex;gap:.5rem;flex-shrink:0}
.btn-valider{background:#dcfce7;color:#15803d;border:none;padding:.45rem 1rem;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.8rem;cursor:pointer;font-weight:600}
.btn-refuser{background:#fee2e2;color:#b91c1c;border:none;padding:.45rem 1rem;border-radius:.75rem;font-family:'DM Sans',sans-serif;font-size:.8rem;cursor:pointer;font-weight:600}
.menu-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.2rem;margin-bottom:2rem}
.menu-item{background:#fff;border-radius:1.2rem;padding:1.5rem;border:1px solid rgba(201,149,74,.1);display:flex;justify-content:space-between;align-items:center;gap:1rem}
.menu-item-title{font-family:'Playfair Display',serif;font-size:1rem;margin-bottom:.2rem}
.menu-item-sub{font-size:.8rem;color:var(--muted)}
.menu-item-price{font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--gold);font-weight:700}
.btn-edit{background:var(--warm);color:var(--gold);border:none;padding:.4rem .9rem;border-radius:.6rem;font-family:'DM Sans',sans-serif;font-size:.78rem;cursor:pointer;font-weight:600}
.btn-delete{background:#fee2e2;color:#b91c1c;border:none;padding:.4rem .9rem;border-radius:.6rem;font-family:'DM Sans',sans-serif;font-size:.78rem;cursor:pointer}
.form-card{background:#fff;border-radius:1.5rem;padding:2rem;box-shadow:0 2px 10px rgba(58,43,26,.05);margin-bottom:2rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-group{margin-bottom:1rem}
.form-label{display:block;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.4rem}
.form-input{width:100%;padding:.7rem 1rem;border:1.5px solid #e8ddd0;border-radius:.8rem;font-family:'DM Sans',sans-serif;font-size:.88rem;color:var(--text);background:var(--cream)}
.form-input:focus{outline:none;border-color:var(--gold)}
.btn-submit{background:var(--dark);color:#fff;border:none;padding:.75rem 2rem;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:500;cursor:pointer}
.empty{text-align:center;padding:3rem;color:var(--muted)}
footer{background:var(--dark);color:rgba(255,255,255,.4);padding:1.5rem;text-align:center;font-size:.82rem;margin-top:3rem}
@media(max-width:768px){.form-row{grid-template-columns:1fr}.nav-links{display:none}}
</style>
</head>
<body>
<nav>
  <a href="/" class="nav-logo">Vite <span>&</span> Gourmand</a>
  <div class="nav-links">
    <a href="/">Accueil</a><a href="/menus">Menus</a>
    <a href="/espace-employe">Espace Employé</a>
    <a href="/deconnexion" style="color:rgba(255,100,100,.7)">Déconnexion</a>
  </div>
</nav>
<div class="page-header">
  <div class="page-header-inner">
    <div><h1>Espace Employé</h1><p>Gestion des commandes, menus et avis</p></div>
    <div class="role-badge"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?></div>
  </div>
</div>
<div class="container">
  <?php if($success): ?><div class="alert-success"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if($error):   ?><div class="alert-error"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>
  <div class="tabs">
    <a href="?tab=commandes" class="tab <?= $tab==='commandes'?'active':'' ?>"><i class="bi bi-bag"></i> Commandes (<?= count($commandes) ?>)</a>
    <a href="?tab=avis"      class="tab <?= $tab==='avis'?'active':'' ?>"><i class="bi bi-star"></i> Avis (<?= count($avis) ?>)</a>
    <a href="?tab=menus"     class="tab <?= $tab==='menus'?'active':'' ?>"><i class="bi bi-card-list"></i> Menus</a>
  </div>
  <div class="tab-content <?= $tab==='commandes'?'active':'' ?>">
    <form method="GET" action="/espace-employe" class="filters">
      <input type="hidden" name="tab" value="commandes">
      <div class="filter-group">
        <label class="filter-label">Statut</label>
        <select name="statut" class="filter-input">
          <option value="">Tous</option>
          <?php foreach(['en_attente','accepte','en_preparation','en_livraison','livre','attente_materiel','terminee','annulee'] as $s): ?>
          <option value="<?= $s ?>" <?= $filterStatut===$s?'selected':'' ?>><?= str_replace('_',' ',$s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-group">
        <label class="filter-label">Email client</label>
        <input type="text" name="email" class="filter-input" placeholder="client@mail.fr" value="<?= htmlspecialchars($filterEmail) ?>">
      </div>
      <button type="submit" class="btn-filter"><i class="bi bi-search"></i> Filtrer</button>
    </form>
    <?php if(empty($commandes)): ?><div class="empty">Aucune commande trouvée.</div>
    <?php else: foreach($commandes as $c): ?>
    <div class="cmd-card">
      <div class="cmd-header">
        <div>
          <div class="cmd-title"><?= htmlspecialchars($c['menu_titre']) ?></div>
          <div class="cmd-sub">#<?= htmlspecialchars($c['numero_commande']) ?> · <?= htmlspecialchars($c['prenom_client'].' '.$c['nom_client']) ?> · <?= htmlspecialchars($c['email_client']) ?></div>
        </div>
        <span class="statut statut-<?= $c['statut'] ?>"><?= str_replace('_',' ',$c['statut']) ?></span>
      </div>
      <div class="cmd-info">
        <span><strong>📅</strong> <?= date('d/m/Y',strtotime($c['date_prestation'])) ?> à <?= htmlspecialchars($c['heure_livraison']) ?></span>
        <span><strong>📍</strong> <?= htmlspecialchars($c['ville_livraison']) ?></span>
        <span><strong>👥</strong> <?= $c['nb_personnes'] ?> pers.</span>
        <span><strong>💶</strong> <?= number_format($c['prix_total'],2) ?> €</span>
        <span><strong>📞</strong> <?= htmlspecialchars($c['gsm_client']) ?></span>
      </div>
      <form method="POST" action="/update-commande" class="update-form">
        <input type="hidden" name="commande_id" value="<?= $c['commande_id'] ?>">
        <select name="statut" class="select-statut">
          <?php foreach(['en_attente','accepte','en_preparation','en_livraison','livre','attente_materiel','terminee','annulee'] as $s): ?>
          <option value="<?= $s ?>" <?= $c['statut']===$s?'selected':'' ?>><?= str_replace('_',' ',$s) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="motif" class="input-motif" placeholder="Motif (obligatoire si annulation)">
        <select name="mode_contact" class="select-mode">
          <option value="">Mode contact</option>
          <option value="gsm">GSM</option>
          <option value="mail">Mail</option>
        </select>
        <button type="submit" class="btn-update"><i class="bi bi-check"></i> Mettre à jour</button>
      </form>
    </div>
    <?php endforeach; endif; ?>
  </div>
  <div class="tab-content <?= $tab==='avis'?'active':'' ?>">
    <?php if(empty($avis)): ?><div class="empty">Aucun avis en attente.</div>
    <?php else: foreach($avis as $a): ?>
    <div class="avis-card">
      <div>
        <div class="avis-stars"><?php for($i=1;$i<=5;$i++) echo $i<=$a['note']?'★':'☆'; ?></div>
        <div class="avis-text">"<?= htmlspecialchars($a['commentaire']) ?>"</div>
        <div class="avis-author"><?= htmlspecialchars($a['prenom'].' '.$a['nom']) ?> · <?= htmlspecialchars($a['menu_titre']) ?></div>
      </div>
      <div class="avis-actions">
        <form method="POST" action="/moderer-avis">
          <input type="hidden" name="avis_id" value="<?= $a['avis_id'] ?>">
          <input type="hidden" name="action" value="valider">
          <button type="submit" class="btn-valider"><i class="bi bi-check"></i> Valider</button>
        </form>
        <form method="POST" action="/moderer-avis">
          <input type="hidden" name="avis_id" value="<?= $a['avis_id'] ?>">
          <input type="hidden" name="action" value="refuser">
          <button type="submit" class="btn-refuser"><i class="bi bi-x"></i> Refuser</button>
        </form>
      </div>
    </div>
    <?php endforeach; endif; ?>
  </div>
  <div class="tab-content <?= $tab==='menus'?'active':'' ?>">
    <div class="form-card">
      <h3 style="font-size:1.1rem;margin-bottom:1.5rem">➕ Ajouter / Modifier un menu</h3>
      <form method="POST" action="/save-menu">
        <input type="hidden" name="menu_id" id="edit_menu_id" value="">
        <div class="form-row">
          <div class="form-group"><label class="form-label">Titre</label><input type="text" name="titre" id="edit_titre" class="form-input" required></div>
          <div class="form-group"><label class="form-label">Prix (€/pers)</label><input type="number" name="prix" id="edit_prix" class="form-input" step="0.01" required></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Thème</label><select name="theme_id" id="edit_theme" class="form-input"><?php foreach($themes as $t): ?><option value="<?= $t['theme_id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option><?php endforeach; ?></select></div>
          <div class="form-group"><label class="form-label">Régime</label><select name="regime_id" id="edit_regime" class="form-input"><?php foreach($regimes as $r): ?><option value="<?= $r['regime_id'] ?>"><?= htmlspecialchars($r['libelle']) ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Nb personnes min</label><input type="number" name="nb_personnes_min" id="edit_nb" class="form-input" value="2" min="1" required></div>
          <div class="form-group"><label class="form-label">Stock</label><input type="number" name="quantite_restante" id="edit_stock" class="form-input" value="10" min="0" required></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" id="edit_desc" class="form-input" rows="3" required></textarea></div>
        <div class="form-group"><label class="form-label">Conditions</label><textarea name="conditions" id="edit_conditions" class="form-input" rows="2"></textarea></div>
        <button type="submit" class="btn-submit">Enregistrer</button>
      </form>
    </div>
    <div class="menu-list">
      <?php foreach($menus as $m): ?>
      <div class="menu-item">
        <div>
          <div class="menu-item-title"><?= htmlspecialchars($m['titre']) ?></div>
          <div class="menu-item-sub"><?= htmlspecialchars($m['theme'].' · '.$m['regime']) ?> · min <?= $m['nb_personnes_min'] ?> pers.</div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.5rem">
          <div class="menu-item-price"><?= number_format($m['prix'],2) ?> €</div>
          <div style="display:flex;gap:.4rem">
            <button class="btn-edit" onclick="editMenu(<?= htmlspecialchars(json_encode($m)) ?>)"><i class="bi bi-pencil"></i></button>
            <form method="POST" action="/delete-menu" onsubmit="return confirm('Supprimer ?')">
              <input type="hidden" name="menu_id" value="<?= $m['menu_id'] ?>">
              <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<footer>© <?= date('Y') ?> Vite & Gourmand</footer>
<script>
function editMenu(m) {
  document.getElementById('edit_menu_id').value=m.menu_id;
  document.getElementById('edit_titre').value=m.titre;
  document.getElementById('edit_prix').value=m.prix;
  document.getElementById('edit_theme').value=m.theme_id;
  document.getElementById('edit_regime').value=m.regime_id;
  document.getElementById('edit_nb').value=m.nb_personnes_min;
  document.getElementById('edit_stock').value=m.quantite_restante;
  document.getElementById('edit_desc').value=m.description;
  document.getElementById('edit_conditions').value=m.conditions||'';
  document.querySelector('.form-card').scrollIntoView({behavior:'smooth'});
}
</script>
</body>
</html>
