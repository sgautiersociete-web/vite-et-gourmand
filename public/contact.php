<?php
// Développeur : Stéphane Gautier
// Formation : TP DWWM - Studi - Mai 2026
// GitHub : github.com/sgautiersociete-web/vite-et-gourmand
$success = '';
$error   = '';
if($_SERVER['REQUEST_METHOD']==='POST') {
    $titre = trim($_POST['titre'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if(!$titre || !$desc || !filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez remplir tous les champs correctement.';
    } else {
        $html = "<h3>Nouveau message</h3><p><b>Titre :</b> ".htmlspecialchars($titre)."</p><p><b>Email :</b> ".htmlspecialchars($email)."</p><p><b>Message :</b><br>".nl2br(htmlspecialchars($desc))."</p>";
        mail('contact@viteetgourmand.fr','[Contact] '.htmlspecialchars($titre),$html,"Content-type: text/html\r\nFrom: noreply@viteetgourmand.fr");
        $success = 'Message envoyé ! Nous vous répondrons sous 48h.';
    }
}
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact — Vite & Gourmand</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--gold:#C9954A;--dark:#1A0F00;--cream:#FDF8F0;--warm:#F5EDD8;--text:#3D2B1A;--muted:#8A7460}
body{font-family:'DM Sans',sans-serif;background:var(--dark);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
.back{position:absolute;top:2rem;left:2rem;color:rgba(255,255,255,.5);text-decoration:none;font-size:.85rem}
.card{background:var(--cream);border-radius:2rem;padding:3rem;width:100%;max-width:520px}
.card-logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--dark);text-align:center;margin-bottom:.5rem}
.card-logo span{color:var(--gold)}
.card-sub{text-align:center;color:var(--muted);font-size:.9rem;margin-bottom:2rem}
.form-group{margin-bottom:1.2rem}
.form-label{display:block;font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.5rem}
.form-input{width:100%;padding:.8rem 1.1rem;border:1.5px solid #e8ddd0;border-radius:.9rem;font-family:'DM Sans',sans-serif;font-size:.9rem;color:var(--text);background:#fff}
.form-input:focus{outline:none;border-color:var(--gold)}
.btn{width:100%;background:var(--dark);color:#fff;border:none;padding:1rem;border-radius:1rem;font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:500;cursor:pointer;margin-top:.5rem}
.success{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:.8rem;border-radius:.75rem;margin-bottom:1rem;font-size:.85rem}
.error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:.8rem;border-radius:.75rem;margin-bottom:1rem;font-size:.85rem}
</style>
</head>
<body>
<a href="/" class="back">← Retour</a>
<div class="card">
  <div class="card-logo">Vite <span>&</span> Gourmand</div>
  <div class="card-sub">Contactez-nous</div>
  <?php if($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if($error):   ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if(!$success): ?>
  <form method="POST">
    <div class="form-group"><label class="form-label">Sujet</label><input type="text" name="titre" class="form-input" placeholder="Demande de devis..." required></div>
    <div class="form-group"><label class="form-label">Votre email</label><input type="email" name="email" class="form-input" placeholder="vous@email.fr" required></div>
    <div class="form-group"><label class="form-label">Message</label><textarea name="description" class="form-input" rows="5" placeholder="Votre message..." required></textarea></div>
    <button type="submit" class="btn">Envoyer →</button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>
