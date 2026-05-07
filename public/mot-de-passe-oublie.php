<?php
$info = '';
if($_SERVER['REQUEST_METHOD']==='POST') {
    $email = trim($_POST['email'] ?? '');
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $info = 'Si cet email existe, vous recevrez un lien de réinitialisation sous peu.';
    } else {
        $info = 'Email invalide.';
    }
}
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mot de passe oublié — Vite & Gourmand</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--gold:#C9954A;--dark:#1A0F00;--cream:#FDF8F0;--text:#3D2B1A;--muted:#8A7460}
body{font-family:'DM Sans',sans-serif;background:var(--dark);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
.back{position:absolute;top:2rem;left:2rem;color:rgba(255,255,255,.5);text-decoration:none;font-size:.85rem}
.card{background:var(--cream);border-radius:2rem;padding:3rem;width:100%;max-width:440px}
.card-logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--dark);text-align:center;margin-bottom:.5rem}
.card-logo span{color:var(--gold)}
.card-sub{text-align:center;color:var(--muted);font-size:.9rem;margin-bottom:2rem}
.form-group{margin-bottom:1.2rem}
.form-label{display:block;font-size:.75rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.5rem}
.form-input{width:100%;padding:.85rem 1.2rem;border:1.5px solid #e8ddd0;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:.95rem;color:var(--text);background:#fff}
.form-input:focus{outline:none;border-color:var(--gold)}
.btn{width:100%;background:var(--dark);color:#fff;border:none;padding:1rem;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:500;cursor:pointer;margin-top:.5rem}
.info{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:.8rem;border-radius:.75rem;margin-bottom:1rem;font-size:.85rem}
.links{text-align:center;margin-top:1.5rem;font-size:.85rem;color:var(--muted)}
.links a{color:var(--gold);text-decoration:none}
</style>
</head>
<body>
<a href="/connexion" class="back">← Retour</a>
<div class="card">
  <div class="card-logo">Vite <span>&</span> Gourmand</div>
  <div class="card-sub">Réinitialiser votre mot de passe</div>
  <?php if($info): ?><div class="info"><?= htmlspecialchars($info) ?></div><?php endif; ?>
  <form method="POST" action="/mot-de-passe-oublie">
    <div class="form-group">
      <label class="form-label">Votre email</label>
      <input type="email" name="email" class="form-input" placeholder="votre@email.fr" required>
    </div>
    <button type="submit" class="btn">Envoyer le lien →</button>
  </form>
  <div class="links"><a href="/connexion">← Retour à la connexion</a></div>
</div>
</body>
</html>
