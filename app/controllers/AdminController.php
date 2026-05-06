<?php
// app/controllers/AdminController.php
require_once APP_PATH . '/models/UserModel.php';
require_once APP_PATH . '/models/CommandeModel.php';

class AdminController
{
    private UserModel     $userModel;
    private CommandeModel $commandeModel;

    public function __construct()
    {
        Session::requireRole('administrateur');
        $this->userModel     = new UserModel();
        $this->commandeModel = new CommandeModel();
    }

    public function dashboard(): void
    {
        $employes   = $this->userModel->getAllEmployes();
        $caFilters  = [
            'menu_id'   => get('menu_id')   ?: null,
            'date_from' => get('date_from') ?: null,
            'date_to'   => get('date_to')   ?: null,
        ];
        $ca         = $this->commandeModel->caByMenu($caFilters);
        $statsMenu  = $this->commandeModel->countByMenu(); // pour graphique

        // Récupération stats MongoDB (simplifié - en production via extension MongoDB)
        $statsJson  = json_encode($statsMenu);

        view('admin/dashboard', compact('employes', 'ca', 'statsJson', 'caFilters', 'statsMenu'));
    }

    public function saveEmploye(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) redirect('/espace-admin');

        $email    = post('email');
        $password = post('password');
        $nom      = post('nom');
        $prenom   = post('prenom');
        $action   = post('action');

        if ($action === 'toggle') {
            $this->userModel->toggleActif((int)post('employe_id'), (bool)(int)post('actif'));
            Session::flash('success', 'Compte mis à jour.');
            redirect('/espace-admin');
        }

        // Création
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Email invalide.');
            redirect('/espace-admin');
        }
        if (!isPasswordStrong($password)) {
            Session::flash('error', 'Mot de passe trop faible.');
            redirect('/espace-admin');
        }

        $data = [
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'nom'           => $nom,
            'prenom'        => $prenom,
            'gsm'           => post('gsm') ?: '0000000000',
            'adresse'       => post('adresse') ?: 'N/A',
            'ville'         => 'Bordeaux',
            'code_postal'   => '33000',
        ];

        $this->userModel->createEmploye($data);

        // Notif mail sans mot de passe
        $html = "<p>Bonjour {$prenom},</p>
                 <p>Un compte employé vient d'être créé pour vous sur l'application Vite & Gourmand.</p>
                 <p>Votre identifiant est : <strong>{$email}</strong></p>
                 <p>Pour obtenir votre mot de passe, veuillez contacter l'administrateur.</p>
                 <p>L'équipe Vite & Gourmand</p>";
        sendMail($email, 'Votre compte Vite & Gourmand a été créé', $html);

        Session::flash('success', 'Compte employé créé. Un email de notification a été envoyé.');
        redirect('/espace-admin');
    }
}
