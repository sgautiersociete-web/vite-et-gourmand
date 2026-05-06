<?php
// app/controllers/EmployeController.php
require_once APP_PATH . '/models/CommandeModel.php';
require_once APP_PATH . '/models/MenuModel.php';
require_once APP_PATH . '/models/AvisModel.php';

class EmployeController
{
    private CommandeModel $commandeModel;
    private MenuModel     $menuModel;
    private AvisModel     $avisModel;

    public function __construct()
    {
        Session::requireRole('employe', 'administrateur');
        $this->commandeModel = new CommandeModel();
        $this->menuModel     = new MenuModel();
        $this->avisModel     = new AvisModel();
    }

    public function dashboard(): void
    {
        $filters  = ['statut' => get('statut'), 'email' => get('email')];
        $commandes = $this->commandeModel->getAll($filters);
        $avisEnAttente = $this->avisModel->getEnAttente();
        $menus    = $this->menuModel->getAll(false);
        view('employe/dashboard', compact('commandes', 'avisEnAttente', 'menus', 'filters'));
    }

    public function updateCommande(): void
    {
        Session::requireRole('employe', 'administrateur');
        if (!Session::verifyCsrf(post('csrf_token'))) redirect('/espace-employe');

        $id     = (int)post('commande_id');
        $statut = post('statut');
        $motif  = post('motif') ?: null;
        $mode   = post('mode_contact') ?: null;

        // Validation : ne peut pas annuler sans motif + mode contact
        if ($statut === 'annulee' && (!$motif || !$mode)) {
            Session::flash('error', 'Motif et mode de contact requis pour annuler.');
            redirect('/espace-employe');
        }

        $this->commandeModel->updateStatut($id, $statut, $motif, $mode);
        $commande = $this->commandeModel->getById($id);

        // Notifications mail selon statut
        if ($commande) {
            $this->notifyUser($commande, $statut);
        }

        Session::flash('success', 'Commande mise à jour.');
        redirect('/espace-employe');
    }

    private function notifyUser(array $commande, string $statut): void
    {
        $labels = [
            'accepte'          => 'acceptée',
            'en_preparation'   => 'en cours de préparation',
            'en_livraison'     => 'en cours de livraison',
            'livre'            => 'livrée',
            'attente_materiel' => 'en attente de retour de matériel',
            'terminee'         => 'terminée',
            'annulee'          => 'annulée',
        ];

        $label = $labels[$statut] ?? $statut;
        $html  = "<p>Bonjour {$commande['prenom_client']},</p>
                  <p>Votre commande <strong>#{$commande['numero_commande']}</strong> est maintenant : <strong>{$label}</strong>.</p>";

        if ($statut === 'attente_materiel') {
            $html .= "<p><strong>⚠️ Attention :</strong> Vous avez emprunté du matériel. Si celui-ci n'est pas restitué dans les 10 jours ouvrés, des frais de 600€ seront appliqués (conformément aux CGV). Veuillez nous contacter pour organiser la restitution.</p>";
        }

        if ($statut === 'terminee') {
            $link = ($_ENV['APP_URL'] ?? '') . '/espace-utilisateur';
            $html .= "<p>Votre commande est terminée ! Connectez-vous pour donner votre avis : <a href='{$link}'>{$link}</a></p>";
        }

        $html .= "<p>L'équipe Vite & Gourmand</p>";
        sendMail($commande['email_client'], "Mise à jour de votre commande – {$label}", $html);
    }

    public function moderateAvis(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) redirect('/espace-employe');
        $this->avisModel->moderate((int)post('avis_id'), post('action') === 'valider' ? 'valide' : 'refuse');
        Session::flash('success', 'Avis modéré.');
        redirect('/espace-employe');
    }

    public function saveMenu(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) redirect('/espace-employe');

        $data = [
            'menu_id'           => post('menu_id') ? (int)post('menu_id') : null,
            'theme_id'          => (int)post('theme_id'),
            'regime_id'         => (int)post('regime_id'),
            'titre'             => post('titre'),
            'description'       => post('description'),
            'nb_personnes_min'  => (int)post('nb_personnes_min'),
            'prix'              => (float)post('prix'),
            'conditions'        => post('conditions'),
            'quantite_restante' => (int)post('quantite_restante'),
            'actif'             => 1,
        ];

        if (empty($data['menu_id'])) unset($data['menu_id']);
        $this->menuModel->save($data);
        Session::flash('success', 'Menu enregistré.');
        redirect('/espace-employe');
    }
}
