<?php
declare(strict_types=1);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

$routes = [
    ''                    => 'home',
    '/menus'              => 'menus',
    '/connexion'          => 'connexion',
    '/inscription'        => 'inscription',
    '/deconnexion'        => 'deconnexion',
    '/contact'            => 'contact',
    '/mentions-legales'   => 'mentions-legales',
    '/cgv'                => 'cgv',
    '/espace-utilisateur' => 'espace-utilisateur',
    '/espace-employe'     => 'espace-employe',
    '/espace-admin'       => 'espace-admin',
    '/annuler-commande'   => 'annuler-commande',
    '/soumettre-avis'     => 'soumettre-avis',
    '/modifier-profil'    => 'modifier-profil',
    '/update-commande'    => 'update-commande',
    '/moderer-avis'       => 'moderer-avis',
    '/save-menu'          => 'save-menu',
    '/delete-menu'        => 'delete-menu',
    '/create-employe'     => 'create-employe',
    '/toggle-employe'     => 'toggle-employe',
];

if(isset($routes[$uri])) {
    $file = __DIR__ . '/' . $routes[$uri] . '.php';
    if(file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo '<h1>404</h1>';
    }
} else {
    // Cherche le fichier directement
    $file = __DIR__ . $uri . '.php';
    if(file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>404</title></head><body style="font-family:sans-serif;text-align:center;padding:5rem"><h1>404</h1><p>Page non trouvée</p><a href="/">Retour à l accueil</a></body></html>';
    }
}
