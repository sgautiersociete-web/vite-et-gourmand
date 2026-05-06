<?php
// app/controllers/AuthController.php
require_once APP_PATH . '/models/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function loginForm(): void
    {
        if (Session::isLoggedIn()) redirect('/');
        view('auth/login', ['csrf' => Session::generateCsrf(), 'error' => Session::getFlash('error')]);
    }

    public function login(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) {
            Session::flash('error', 'Token invalide.');
            redirect('/connexion');
        }

        $email    = post('email');
        $password = post('password');

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash']) || !$user['actif']) {
            Session::flash('error', 'Email ou mot de passe incorrect.');
            redirect('/connexion');
        }

        session_regenerate_id(true);
        Session::set('user', [
            'id'     => $user['utilisateur_id'],
            'email'  => $user['email'],
            'nom'    => $user['nom'],
            'prenom' => $user['prenom'],
            'role'   => $user['role_libelle'],
        ]);

        $redirect = match ($user['role_libelle']) {
            'administrateur' => '/espace-admin',
            'employe'        => '/espace-employe',
            default          => '/espace-utilisateur',
        };
        redirect($redirect);
    }

    public function registerForm(): void
    {
        if (Session::isLoggedIn()) redirect('/');
        view('auth/register', ['csrf' => Session::generateCsrf(), 'error' => Session::getFlash('error')]);
    }

    public function register(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) {
            Session::flash('error', 'Token invalide.');
            redirect('/inscription');
        }

        $data = [
            'email'       => post('email'),
            'password'    => post('password'),
            'nom'         => post('nom'),
            'prenom'      => post('prenom'),
            'gsm'         => post('gsm'),
            'adresse'     => post('adresse'),
            'ville'       => post('ville'),
            'code_postal' => post('code_postal'),
        ];

        // Validations
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Email invalide.');
            redirect('/inscription');
        }
        if (!isPasswordStrong($data['password'])) {
            Session::flash('error', 'Mot de passe trop faible (10 caractères min, 1 maj, 1 min, 1 chiffre, 1 spécial).');
            redirect('/inscription');
        }
        if ($this->userModel->findByEmail($data['email'])) {
            Session::flash('error', 'Cet email est déjà utilisé.');
            redirect('/inscription');
        }

        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $this->userModel->create($data);

        // Email de bienvenue
        $html = "<h2>Bienvenue sur Vite & Gourmand !</h2>
                 <p>Bonjour {$data['prenom']},</p>
                 <p>Votre compte a été créé avec succès. Vous pouvez dès maintenant commander vos menus en ligne !</p>
                 <p>L'équipe Vite & Gourmand</p>";
        sendMail($data['email'], 'Bienvenue sur Vite & Gourmand !', $html);

        Session::flash('success', 'Compte créé ! Vous pouvez vous connecter.');
        redirect('/connexion');
    }

    public function logout(): void
    {
        Session::destroy();
        redirect('/');
    }

    public function forgotForm(): void
    {
        view('auth/forgot', ['csrf' => Session::generateCsrf(), 'info' => Session::getFlash('info')]);
    }

    public function forgotSend(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) {
            redirect('/mot-de-passe-oublie');
        }
        $email = post('email');
        $user  = $this->userModel->findByEmail($email);

        if ($user) {
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600);
            $this->userModel->setResetToken($user['utilisateur_id'], $token, $expires);

            $link = ($_ENV['APP_URL'] ?? 'http://localhost:8000') . '/reinitialiser?token=' . $token;
            $html = "<p>Cliquez sur ce lien pour réinitialiser votre mot de passe (valable 1h) :</p>
                     <a href='{$link}'>{$link}</a>";
            sendMail($email, 'Réinitialisation de votre mot de passe', $html);
        }

        // Message identique qu'un compte existe ou non (sécurité)
        Session::flash('info', 'Si cet email existe, vous recevrez un lien de réinitialisation.');
        redirect('/mot-de-passe-oublie');
    }

    public function resetForm(): void
    {
        $token = get('token');
        $user  = $this->userModel->findByResetToken($token);
        if (!$user) {
            Session::flash('error', 'Lien invalide ou expiré.');
            redirect('/connexion');
        }
        view('auth/reset', ['csrf' => Session::generateCsrf(), 'token' => $token]);
    }

    public function resetPassword(): void
    {
        if (!Session::verifyCsrf(post('csrf_token'))) {
            redirect('/connexion');
        }
        $token    = post('token');
        $password = post('password');
        $user     = $this->userModel->findByResetToken($token);

        if (!$user) {
            Session::flash('error', 'Lien invalide.');
            redirect('/connexion');
        }
        if (!isPasswordStrong($password)) {
            Session::flash('error', 'Mot de passe trop faible.');
            redirect('/reinitialiser?token=' . $token);
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->userModel->updatePassword($user['utilisateur_id'], $hash);
        Session::flash('success', 'Mot de passe mis à jour. Vous pouvez vous connecter.');
        redirect('/connexion');
    }
}
