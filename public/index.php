<?php
declare(strict_types=1);

$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Fichiers statiques
$staticFile = __DIR__ . $uri;
if ($uri !== '' && file_exists($staticFile) && !is_dir($staticFile)) {
    $ext = pathinfo($staticFile, PATHINFO_EXTENSION);
    $mimes = ['css'=>'text/css','js'=>'application/javascript','png'=>'image/png','jpg'=>'image/jpeg','ico'=>'image/x-icon','svg'=>'image/svg+xml','woff2'=>'font/woff2'];
    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
        readfile($staticFile);
        exit;
    }
}

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();

$routes = [
    ''                     => 'home',
    '/menus'               => 'menus',
    '/connexion'           => 'connexion',
    '/inscription'         => 'inscription',
    '/deconnexion'         => 'deconnexion',
    '/contact'             => 'contact',
    '/mentions-legales'    => 'mentions-legales',
    '/cgv'                 => 'cgv',
    '/espace-utilisateur'  => 'espace-utilisateur',
    '/espace-employe'      => 'espace-employe',
    '/espace-admin'        => 'espace-admin',
    '/annuler-commande'    => 'annuler-commande',
    '/soumettre-avis'      => 'soumettre-avis',
    '/modifier-profil'     => 'modifier-profil',
    '/update-commande'     => 'update-commande',
    '/moderer-avis'        => 'moderer-avis',
    '/save-menu'           => 'save-menu',
    '/delete-menu'         => 'delete-menu',
    '/create-employe'      => 'create-employe',
    '/toggle-employe'      => 'toggle-employe',
    '/mot-de-passe-oublie' => 'mot-de-passe-oublie',
    '/reset-pwd'           => 'reset-pwd',
];

$page = $routes[$uri] ?? null;

if ($page !== null) {
    $file = __DIR__ . '/' . $page . '.php';
    if (file_exists($file)) {
        require $file;
        exit;
    }
    http_response_code(500);
    echo "Fichier manquant : $page.php";
    exit;
}

http_response_code(404);
echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>404</title></head><body style="font-family:sans-serif;text-align:center;padding:5rem;background:#FDF8F0"><h1 style="font-family:serif;color:#1A0F00">404</h1><p style="color:#8A7460">Page non trouvée</p><a href="/" style="color:#C9954A">← Retour</a></body></html>';
