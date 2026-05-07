<?php

if(Session::isLoggedIn()) header('Location: /espace-utilisateur.php') and die();

$error   = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = trim($_POST['nom'] ?? '');
    $prenom     = trim($_POST['prenom'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $gsm        = trim($_POST['gsm'] ?? '');
    $adresse    = trim($_POST['adresse'] ?? '');
    $ville      = trim($_POST['ville'] ?? '');
    $cp         = trim($_POST['code_postal'] ?? '');
    $password   = $_POST['password'] ?? '';

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide.';
    } elseif(!isPasswordStrong($password)) {
        $error = 'Mot de passe trop faible : 10 caractères min, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial.';
    } elseif(empty($nom) || empty($prenom)) {
        $error = 'Nom et prénom obligatoires.';
    } else {
        try {
            $db   = Database::getInstance();
            $stmt = $db->prepare("SELECT utilisateur_id FROM utilisateur WHERE email=:email LIMIT 1");
            $stmt->execute(['email' => $email]);
            if($stmt->fetch()) {
                $error = 'Cet email est déjà utilisé.';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $stmt = $db->prepare("INSERT INTO utilisateur (role_id,email,password_hash,nom,prenom,gsm,adresse,ville,code_postal) VALUES (3,:email,:hash,:nom,:prenom,:gsm,:adresse,:ville,:cp)");
                $stmt->execute(['email'=>$email,'hash'=>$hash,'nom'=>$nom,'prenom'=>$prenom,'gsm'=>$gsm,'adresse'=>$adresse,'ville'=>$ville,'cp'=>$cp]);
                $success = 'Compte créé ! Vous pouvez maintenant vous connecter.';
            }
        } catch(Exception $e) {
            $error = 'Erreur lors de la création du compte.';
        }
    }
}
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription — Vite & Gourmand</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--gold:#C9954A;--gold-light:#E8C17A;--dark:#1A0F00;--cream:#FDF8F0;--warm:#F5EDD8;--text:#3D2B1A;--muted:#8A7460}
body{font-family:'DM Sans',sans-serif;background:var(--dark);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem}
.back{position:absolute;top:2rem;left:2rem;color:rgba(255,255,255,.5);text-decoration:none;font-size:.85rem;display:flex;align-items:center;gap:.5rem;transition:color .2s}
.back:hover{color:var(--gold-light)}
.card{background:var(--cream);border-radius:2rem;padding:3rem;width:100%;max-width:540px;box-shadow:0 30px 80px rgba(0,0,0,.4)}
.card-logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--dark);text-align:center;margin-bottom:.5rem}
.card-logo span{color:var(--gold)}
.card-sub{text-align:center;color:var(--muted);font-size:.9rem;margin-bottom:2.5rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-group{margin-bottom:1.2rem}
.form-label{display:block;font-size:.75rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.5rem}
.form-input{width:100%;padding:.8rem 1.1rem;border:1.5px solid #e8ddd0;border-radius:.9rem;font-family:'DM Sans',sans-serif;font-size:.9rem;color:var(--text);background:#fff;transition:border-color .2s}
.form-input:focus{outline:none;border-color:var(--gold)}
.pwd-hint{font-size:.75rem;color:var(--muted);margin-top:.4rem;line-height:1.5}
.btn-submit{width:100%;background:var(--dark);color:#fff;border:none;padding:1rem;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:500;cursor:pointer;transition:background .2s;margin-top:.5rem}
.btn-submit:hover{background:#3d2200}
.error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:.8rem 1rem;border-radius:.75rem;font-size:.85rem;margin-bottom:1.5rem}
.success{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:.8rem 1rem;border-radius:.75rem;font-size:.85rem;margin-bottom:1.5rem}
.links{text-align:center;margin-top:1.5rem;font-size:.85rem;color:var(--muted)}
.links a{color:var(--gold);text-decoration:none;font-weight:500}
.section-title{font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:var(--gold);font-weight:700;margin:1.5rem 0 1rem;padding-bottom:.5rem;border-bottom:1px solid var(--warm)}
@media(max-width:500px){.form-row{grid-template-columns:1fr}}
</style>
</head>
<body>
<a href="/" class="back"><i class="bi bi-arrow-left"></i> Retour à l'accueil</a>

<div class="card">
  <div class="card-logo">Vite <span>&</span> Gourmand</div>
  <div class="card-sub">Créer votre compte</div>

  <?php if($error): ?>
  <div class="error"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if($success): ?>
  <div class="success"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?>
    <br><a href="/connexion.php" style="color:#16a34a;font-weight:600">Se connecter →</a>
  </div>
  <?php else: ?>

  <form method="POST" action="/inscription" id="form-register">
    <div class="section-title">Informations personnelles</div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Nom</label>
        <input type="text" name="nom" class="form-input" placeholder="Dupont" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom" class="form-input" placeholder="Marie" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-input" placeholder="votre@email.fr" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label class="form-label">Téléphone (GSM)</label>
      <input type="tel" name="gsm" class="form-input" placeholder="06 12 34 56 78" required value="<?= htmlspecialchars($_POST['gsm'] ?? '') ?>">
    </div>

    <div class="section-title">Adresse postale</div>
    <div class="form-group">
      <label class="form-label">Adresse</label>
      <input type="text" name="adresse" class="form-input" placeholder="10 rue des Fleurs" required value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Ville</label>
        <input type="text" name="ville" class="form-input" placeholder="Bordeaux" required value="<?= htmlspecialchars($_POST['ville'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Code postal</label>
        <input type="text" name="code_postal" class="form-input" placeholder="33000" required value="<?= htmlspecialchars($_POST['code_postal'] ?? '') ?>">
      </div>
    </div>

    <div class="section-title">Sécurité</div>
    <div class="form-group">
      <label class="form-label">Mot de passe</label>
      <input type="password" name="password" class="form-input" placeholder="••••••••••" required>
      <div class="pwd-hint">10 caractères min · 1 majuscule · 1 minuscule · 1 chiffre · 1 caractère spécial</div>
    </div>

    <button type="submit" class="btn-submit">Créer mon compte →</button>
  </form>

  <?php endif; ?>

  <div class="links">
    Déjà un compte ? <a href="/connexion.php">Se connecter</a>
  </div>
</div>

<script>
document.getElementById('form-register')?.addEventListener('submit', function(e) {
    const pwd = this.querySelector('[name=password]').value;
    const ok  = pwd.length >= 10 && /[A-Z]/.test(pwd) && /[a-z]/.test(pwd) && /[0-9]/.test(pwd) && /[\W_]/.test(pwd);
    if (!ok) { e.preventDefault(); alert('Mot de passe trop faible.\n\n10 caractères min, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial.'); }
});
</script>
</body>
</html>
