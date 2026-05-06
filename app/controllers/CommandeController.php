<?php
// app/controllers/CommandeController.php
require_once APP_PATH . '/models/MenuModel.php';
require_once APP_PATH . '/models/CommandeModel.php';

class CommandeController
{
    private MenuModel     $menuModel;
    private CommandeModel $commandeModel;

    public function __construct()
    {
        Session::requireRole('utilisateur', 'employe', 'administrateur');
        $this->menuModel     = new MenuModel();
        $this->commandeModel = new CommandeModel();
    }

    public function form(): void
    {
        $menus  = $this->menuModel->getAll();
        $menuId = (int)get('menu_id');
        $menu   = $menuId ? $this->menuModel->getById($menuId) : null;
        $user   = Session::user();
        view('commande/form', compact('menus', 'menu', 'user'));
    }

    public function store(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) {
            redirect('/commande');
        }

        $user   = Session::user();
        $menuId = (int)post('menu_id');
        $menu   = $this->menuModel->getById($menuId);

        if (!$menu) {
            Session::flash('error', 'Menu introuvable.');
            redirect('/commande');
        }

        $nbPersonnes = (int)post('nb_personnes');
        if ($nbPersonnes < $menu['nb_personnes_min']) {
            Session::flash('error', 'Nombre de personnes insuffisant (minimum : ' . $menu['nb_personnes_min'] . ').');
            redirect('/commande?menu_id=' . $menuId);
        }

        $ville         = post('ville_livraison');
        $prixMenu      = $menu['prix'] * $nbPersonnes;
        $prixLivraison = calculPrixLivraison($ville);
        $reduction     = calculReduction($prixMenu, $nbPersonnes, $menu['nb_personnes_min']);
        $prixTotal     = $prixMenu + $prixLivraison - $reduction;

        $data = [
            'utilisateur_id'    => $user['id'],
            'menu_id'           => $menuId,
            'numero_commande'   => genNumeroCommande(),
            'nom_client'        => post('nom_client'),
            'prenom_client'     => post('prenom_client'),
            'email_client'      => post('email_client'),
            'gsm_client'        => post('gsm_client'),
            'adresse_livraison' => post('adresse_livraison'),
            'ville_livraison'   => $ville,
            'date_prestation'   => post('date_prestation'),
            'heure_livraison'   => post('heure_livraison'),
            'nb_personnes'      => $nbPersonnes,
            'prix_menu'         => $prixMenu,
            'prix_livraison'    => $prixLivraison,
            'reduction'         => $reduction,
            'prix_total'        => $prixTotal,
        ];

        $commandeId = $this->commandeModel->create($data);

        // Email confirmation
        $html = "
        <h2>Confirmation de commande #{$data['numero_commande']}</h2>
        <p>Bonjour {$data['prenom_client']},</p>
        <p>Votre commande a bien été enregistrée.</p>
        <table border='1' cellpadding='8' style='border-collapse:collapse'>
          <tr><td><strong>Menu</strong></td><td>{$menu['titre']}</td></tr>
          <tr><td><strong>Date prestation</strong></td><td>{$data['date_prestation']}</td></tr>
          <tr><td><strong>Heure livraison</strong></td><td>{$data['heure_livraison']}</td></tr>
          <tr><td><strong>Nb personnes</strong></td><td>{$nbPersonnes}</td></tr>
          <tr><td><strong>Prix menu</strong></td><td>" . number_format($prixMenu, 2) . " €</td></tr>
          <tr><td><strong>Livraison</strong></td><td>" . number_format($prixLivraison, 2) . " €</td></tr>
          <tr><td><strong>Réduction</strong></td><td>-" . number_format($reduction, 2) . " €</td></tr>
          <tr><td><strong>TOTAL</strong></td><td><strong>" . number_format($prixTotal, 2) . " €</strong></td></tr>
        </table>
        <p>Merci de votre confiance !<br>L'équipe Vite & Gourmand</p>";

        sendMail($data['email_client'], 'Confirmation de votre commande Vite & Gourmand', $html);

        redirect('/espace-utilisateur?commande=' . $commandeId . '&success=1');
    }
}
