<?php
// Mot de passe oublié - Vite & Gourmand
// Développeur : Stéphane Gautier
$page_title = 'Mot de passe oublié';
$info = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // TODO: envoyer un vrai email avec token de réinitialisation
        $info = 'Si cet email existe, vous recevrez un lien de réinitialisation.';
    } else {
        $info = 'Email invalide.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Vite & Gourmand</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body style="background:#4a2c0a;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem">
<div style="position:absolute;top:1.5rem;left:1.5rem">
    <a href="/connexion" style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.9rem">← Retour</a>
</div>
<div class="card shadow" style="width:100%;max-width:420px;border-radius:12px">
    <div class="card-body p-4">
        <h4 class="text-center fw-bold mb-1" style="color:var(--couleur-principale)">🍽️ Vite & Gourmand</h4>
        <p class="text-center text-muted small mb-4">Réinitialiser votre mot de passe</p>
        <?php if($info): ?>
        <div class="alert-custom-success mb-3"><?= htmlspecialchars($info) ?></div>
        <?php endif; ?>
        <form method="POST" action="/mot-de-passe-oublie">
            <div class="mb-3">
                <label class="form-label-custom">Votre email</label>
                <input type="email" name="email" class="form-control" placeholder="votre@email.fr" required>
            </div>
            <button type="submit" class="btn w-100" style="background:var(--couleur-principale);color:white">
                Envoyer le lien →
            </button>
        </form>
        <div class="text-center mt-3 small">
            <a href="/connexion" style="color:var(--couleur-accent)">← Retour à la connexion</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
