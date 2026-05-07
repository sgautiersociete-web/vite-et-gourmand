<?php
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

error_reporting(E_ALL);
ini_set('display_errors', '1');

$page = $routes[$uri] ?? null;

if($page) {
    $file = __DIR__ . '/' . $page . '.php';
    if(file_exists($file)) {
        require $file;
        exit;
    } else {
        echo "Fichier manquant : " . $file;
        exit;
    }
}

echo "URI: " . $uri . " — Page non trouvée";
