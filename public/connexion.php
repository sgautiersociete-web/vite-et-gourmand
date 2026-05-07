<?php
declare(strict_types=1);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();

if(Session::isLoggedIn()) header('Location: /espace-utilisateur.php') and die();

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT u.*, r.libelle AS role_libelle FROM utilisateur u JOIN role r ON r.role_id=u.role_id WHERE u.email=:email AND u.actif=1 LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            Session::set('user', [
                'id'     => $user['utilisateur_id'],
                'email'  => $user['email'],
                'nom'    => $user['nom'],
                'prenom' => $user['prenom'],
                'role'   => $user['role_libelle'],
            ]);
            $redirect = match($user['role_libelle']) {
                'administrateur' => '/espace-admin.php',
                'employe'        => '/espace-employe.php',
                default          => '/espace-utilisateur.php',
            };
            header('Location: '.$redirect);
            die();
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    } catch(Exception $e) {
        $error = 'Erreur de connexion.';
    }
}
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — Vite & Gourmand</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--gold:#C9954A;--gold-light:#E8C17A;--dark:#1A0F00;--cream:#FDF8F0;--warm:#F5EDD8;--text:#3D2B1A;--muted:#8A7460}
body{font-family:'DM Sans',sans-serif;background:var(--dark);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem}
.back{position:absolute;top:2rem;left:2rem;color:rgba(255,255,255,.5);text-decoration:none;font-size:.85rem;display:flex;align-items:center;gap:.5rem;transition:color .2s}
.back:hover{color:var(--gold-light)}
.card{background:var(--cream);border-radius:2rem;padding:3rem;width:100%;max-width:440px;box-shadow:0 30px 80px rgba(0,0,0,.4)}
.card-logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--dark);text-align:center;margin-bottom:.5rem}
.card-logo span{color:var(--gold)}
.card-sub{text-align:center;color:var(--muted);font-size:.9rem;margin-bottom:2.5rem}
.form-group{margin-bottom:1.5rem}
.form-label{display:block;font-size:.75rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.5rem}
.form-input{width:100%;padding:.85rem 1.2rem;border:1.5px solid #e8ddd0;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:.95rem;color:var(--text);background:#fff;transition:border-color .2s}
.form-input:focus{outline:none;border-color:var(--gold)}
.btn-submit{width:100%;background:var(--dark);color:#fff;border:none;padding:1rem;border-radius:1rem;font-family:'DM Sans',sans-seri
cd ~/Documents/GitHub/vite-et-gourmand
cat > public/connexion.php << 'PHPEOF'
<?php
declare(strict_types=1);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();

if(Session::isLoggedIn()) header('Location: /espace-utilisateur.php') and die();

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT u.*, r.libelle AS role_libelle FROM utilisateur u JOIN role r ON r.role_id=u.role_id WHERE u.email=:email AND u.actif=1 LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            Session::set('user', [
                'id'     => $user['utilisateur_id'],
                'email'  => $user['email'],
                'nom'    => $user['nom'],
                'prenom' => $user['prenom'],
                'role'   => $user['role_libelle'],
            ]);
            $redirect = match($user['role_libelle']) {
                'administrateur' => '/espace-admin.php',
                'employe'        => '/espace-employe.php',
                default          => '/espace-utilisateur.php',
            };
            header('Location: '.$redirect);
            die();
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    } catch(Exception $e) {
        $error = 'Erreur de connexion.';
    }
}
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — Vite & Gourmand</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--gold:#C9954A;--gold-light:#E8C17A;--dark:#1A0F00;--cream:#FDF8F0;--warm:#F5EDD8;--text:#3D2B1A;--muted:#8A7460}
body{font-family:'DM Sans',sans-serif;background:var(--dark);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem}
.back{position:absolute;top:2rem;left:2rem;color:rgba(255,255,255,.5);text-decoration:none;font-size:.85rem;display:flex;align-items:center;gap:.5rem;transition:color .2s}
.back:hover{color:var(--gold-light)}
.card{background:var(--cream);border-radius:2rem;padding:3rem;width:100%;max-width:440px;box-shadow:0 30px 80px rgba(0,0,0,.4)}
.card-logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--dark);text-align:center;margin-bottom:.5rem}
.card-logo span{color:var(--gold)}
.card-sub{text-align:center;color:var(--muted);font-size:.9rem;margin-bottom:2.5rem}
.form-group{margin-bottom:1.5rem}
.form-label{display:block;font-size:.75rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.5rem}
.form-input{width:100%;padding:.85rem 1.2rem;border:1.5px solid #e8ddd0;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:.95rem;color:var(--text);background:#fff;transition:border-color .2s}
.form-input:focus{outline:none;border-color:var(--gold)}
.btn-submit{width:100%;background:var(--dark);color:#fff;border:none;padding:1rem;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:500;cursor:pointer;transition:background .2s;margin-top:.5rem}
.btn-submit:hover{background:#3d2200}
.error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:.8rem 1rem;border-radius:.75rem;font-size:.85rem;margin-bottom:1.5rem}
.links{text-align:center;margin-top:1.5rem;font-size:.85rem;color:var(--muted)}
.links a{color:var(--gold);text-decoration:none;font-weight:500}
.links a:hover{text-decoration:underline}
.divider{text-align:center;color:var(--muted);font-size:.8rem;margin:1rem 0;position:relative}
.divider::before,.divider::after{content:'';position:absolute;top:50%;width:40%;height:1px;background:#e8ddd0}
.divider::before{left:0}.divider::after{right:0}
</style>
</head>
<body>
<a href="/" class="back"><i class="bi bi-arrow-left"></i> Retour à l'accueil</a>

<div class="card">
  <div class="card-logo">Vite <span>&</span> Gourmand</div>
  <div class="card-sub">Connectez-vous à votre espace</div>

  <?php if($error): ?>
  <div class="error"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="/connexion.php">
    <div class="form-group">
      <label class="form-label">Adresse email</label>
      <input type="email" name="email" class="form-input" placeholder="votre@email.fr" required
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label class="form-label">Mot de passe</label>
      <input type="password" name="password" class="form-input" placeholder="••••••••••" required>
    </div>
    <button type="submit" class="btn-submit">Se connecter →</button>
  </form>

  <div class="divider">ou</div>

  <div class="links">
    <a href="/mot-de-passe-oublie.php">Mot de passe oublié ?</a>
    <br><br>
    Pas encore de compte ? <a href="/inscription.php">Créer un compte</a>
  </div>
</div>
</body>
</html>
