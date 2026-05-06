// public/js/app.js - Vite & Gourmand

// ── Calcul prix commande en temps réel ──────────────────────────────
(function() {
    const nbInput = document.getElementById('nb_personnes');
    if (!nbInput) return;

    const prixBase  = parseFloat(document.getElementById('prix_base')?.value  || '0');
    const nbMin     = parseInt(document.getElementById('nb_min')?.value        || '0');
    const villeInput = document.getElementById('ville_livraison');

    function updatePrix() {
        const nb          = parseInt(nbInput.value) || nbMin;
        const ville       = (villeInput?.value || '').trim().toLowerCase();
        const prixMenu    = prixBase * nb;
        const livraison   = ville === 'bordeaux' ? 0 : 5;
        const reduction   = nb >= (nbMin + 5) ? prixMenu * 0.1 : 0;
        const total       = prixMenu + livraison - reduction;

        const el = (id) => document.getElementById(id);
        if (el('display_prix_menu'))    el('display_prix_menu').textContent    = prixMenu.toFixed(2) + ' €';
        if (el('display_livraison'))    el('display_livraison').textContent    = livraison.toFixed(2) + ' €';
        if (el('display_reduction'))    el('display_reduction').textContent    = '-' + reduction.toFixed(2) + ' €';
        if (el('display_total'))        el('display_total').textContent        = total.toFixed(2) + ' €';
        if (el('display_reduction_row')) {
            el('display_reduction_row').style.display = reduction > 0 ? '' : 'none';
        }
    }

    nbInput.addEventListener('input', updatePrix);
    villeInput?.addEventListener('input', updatePrix);
    updatePrix();
})();

// ── Validation formulaire inscription ───────────────────────────────
(function() {
    const form = document.getElementById('form-register');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        const pwd   = form.querySelector('[name=password]').value;
        const rules = [
            { re: /.{10,}/,  msg: 'Au moins 10 caractères' },
            { re: /[A-Z]/,   msg: 'Au moins une majuscule' },
            { re: /[a-z]/,   msg: 'Au moins une minuscule' },
            { re: /[0-9]/,   msg: 'Au moins un chiffre' },
            { re: /[\W_]/,   msg: 'Au moins un caractère spécial' },
        ];
        const failed = rules.filter(r => !r.re.test(pwd));
        if (failed.length) {
            e.preventDefault();
            alert('Mot de passe invalide :\n' + failed.map(r => '• ' + r.msg).join('\n'));
        }
    });
})();

// ── Confirmation annulation commande ───────────────────────────────
document.querySelectorAll('.btn-cancel-order').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
            e.preventDefault();
        }
    });
});

// ── Auto-dismiss alerts after 5s ────────────────────────────────────
setTimeout(() => {
    document.querySelectorAll('.alert:not(.alert-danger)').forEach(el => {
        const bsAlert = bootstrap.Alert.getInstance(el) || new bootstrap.Alert(el);
        bsAlert.close();
    });
}, 5000);
