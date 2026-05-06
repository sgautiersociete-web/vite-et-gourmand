<?php
// app/controllers/UserController.php
require_once APP_PATH . '/models/CommandeModel.php';
require_once APP_PATH . '/models/AvisModel.php';

class UserController
{
    private CommandeModel $commandeModel;
    private AvisModel     $avisModel;

    public function __construct()
    {
        Session::requireRole('utilisateur', 'employe', 'administrateur');
        $this->commandeModel = new CommandeModel();
        $this->avisModel     = new AvisModel();
    }

    public function dashboard(): void
    {
        $user      = Session::user();
        $commandes = $this->commandeModel->getByUser($user['id']);
        view('user/dashboard', compact('commandes', 'user'));
    }

    public function cancelOrder(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) redirect('/espace-utilisateur');
        $user = Session::user();
        $ok   = $this->commandeModel->cancel((int)post('commande_id'), $user['id']);
        Session::flash($ok ? 'success' : 'error', $ok ? 'Commande annulée.' : 'Impossible d\'annuler cette commande.');
        redirect('/espace-utilisateur');
    }

    public function submitAvis(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) redirect('/espace-utilisateur');
        $user = Session::user();
        $note = max(1, min(5, (int)post('note')));
        $this->avisModel->create((int)post('commande_id'), $user['id'], $note, post('commentaire'));
        Session::flash('success', 'Avis soumis ! Il sera visible après validation par notre équipe.');
        redirect('/espace-utilisateur');
    }
}
