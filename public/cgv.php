<?php
// Page CGV - Vite & Gourmand
// Développeur : Stéphane Gautier - TP DWWM Studi
$page_title = 'Conditions Générales de Vente';
require '_header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Conditions Générales de Vente</h1>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h5 class="section-title">1. Objet</h5>
                    <p>Les présentes CGV régissent les relations entre Vite & Gourmand et ses clients
                    pour toute commande de prestation de traiteur via l'application web.</p>

                    <h5 class="section-title mt-4">2. Commandes</h5>
                    <p>Toute commande doit être passée selon les délais indiqués sur chaque menu.
                    Une commande est confirmée après validation par notre équipe.
                    Un email de confirmation est envoyé automatiquement.</p>

                    <h5 class="section-title mt-4">3. Prix et paiement</h5>
                    <p>Les prix sont indiqués par personne, hors frais de livraison.
                    Une réduction de <strong>10%</strong> est appliquée pour toute commande
                    dépassant de 5 personnes ou plus le minimum du menu.</p>
                    <p>Frais de livraison : <strong>gratuit à Bordeaux</strong>,
                    sinon 5€ + 0,59€/km parcouru.</p>

                    <h5 class="section-title mt-4">4. Annulation</h5>
                    <p>Une commande peut être annulée tant qu'elle n'a pas été acceptée par notre équipe.
                    Après acceptation, toute annulation nécessite un contact préalable.</p>

                    <h5 class="section-title mt-4">5. Matériel prêté</h5>
                    <p>En cas de prêt de matériel, celui-ci doit être restitué dans un délai de
                    <strong>10 jours ouvrés</strong>. En cas de non-restitution, des frais de
                    <strong>600€</strong> seront facturés.</p>

                    <h5 class="section-title mt-4">6. Allergènes</h5>
                    <p>Les allergènes sont indiqués sur chaque plat. Il appartient au client de
                    vérifier la composition des menus avant commande.</p>

                    <h5 class="section-title mt-4">7. Litiges</h5>
                    <p>En cas de litige, une solution amiable sera recherchée en priorité.
                    À défaut, le tribunal compétent sera celui de Bordeaux.</p>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require '_footer.php'; ?>
