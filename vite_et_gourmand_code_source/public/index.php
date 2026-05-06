<?php
// public/index.php - Point d'entrée unique (Front Controller)
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');

require_once ROOT_PATH . '/vendor/autoload.php';
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';

Session::start();

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Routing basique
$routes = [
    'GET'  => [
        '/'                     => ['HomeController',    'index'],
        '/menus'                => ['MenuController',    'index'],
        '/menus/detail'         => ['MenuController',    'detail'],
        '/commande'             => ['CommandeController','form'],
        '/connexion'            => ['AuthController',    'loginForm'],
        '/inscription'          => ['AuthController',    'registerForm'],
        '/deconnexion'          => ['AuthController',    'logout'],
        '/mot-de-passe-oublie'  => ['AuthController',    'forgotForm'],
        '/reinitialiser'        => ['AuthController',    'resetForm'],
        '/espace-utilisateur'   => ['UserController',    'dashboard'],
        '/espace-employe'       => ['EmployeController', 'dashboard'],
        '/espace-admin'         => ['AdminController',   'dashboard'],
        '/contact'              => ['ContactController', 'form'],
        '/mentions-legales'     => ['PageController',    'mentions'],
        '/cgv'                  => ['PageController',    'cgv'],
    ],
    'POST' => [
        '/connexion'            => ['AuthController',    'login'],
        '/inscription'          => ['AuthController',    'register'],
        '/mot-de-passe-oublie'  => ['AuthController',    'forgotSend'],
        '/reinitialiser'        => ['AuthController',    'resetPassword'],
        '/commande'             => ['CommandeController','store'],
        '/contact'              => ['ContactController', 'send'],
        '/espace-utilisateur/annuler'  => ['UserController',    'cancelOrder'],
        '/espace-utilisateur/avis'     => ['UserController',    'submitAvis'],
        '/espace-employe/commande'     => ['EmployeController', 'updateCommande'],
        '/espace-employe/avis'         => ['EmployeController', 'moderateAvis'],
        '/espace-employe/menu'         => ['EmployeController', 'saveMenu'],
        '/espace-admin/employe'        => ['AdminController',   'saveEmploye'],
        '/menus/filter'                => ['MenuController',    'filter'],
    ],
];

$controllerClass = null;
$action          = null;

if (isset($routes[$method][$uri])) {
    [$controllerClass, $action] = $routes[$method][$uri];
} else {
    http_response_code(404);
    include APP_PATH . '/views/errors/404.php';
    exit;
}

$controllerFile = APP_PATH . '/controllers/' . $controllerClass . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(500);
    die('Controller not found: ' . $controllerClass);
}
require_once $controllerFile;
$controller = new $controllerClass();
$controller->$action();
