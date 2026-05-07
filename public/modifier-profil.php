<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database';
require_once APP_PATH . '/config/Session';
require_once APP_PATH . '/helpers/functions';
Session::start();
if(!Session::isLoggedIn()) { header('Location: /connexion'); die(); }
$user = Session::user();
$db   = Database::getInstance();
$stmt = $db->prepare("UPDATE utilisateur SET nom=:nom,prenom=:prenom,gsm=:gsm,adresse=:adresse,ville=:ville,code_postal=:cp WHERE utilisateur_id=:id");
$stmt->execute([
    'nom'    => trim($_POST['nom'] ?? ''),
    'prenom' => trim($_POST['prenom'] ?? ''),
    'gsm'    => trim($_POST['gsm'] ?? ''),
    'adresse'=> trim($_POST['adresse'] ?? ''),
    'ville'  => trim($_POST['ville'] ?? ''),
    'cp'     => trim($_POST['code_postal'] ?? ''),
    'id'     => $user['id']
]);
header('Location: /espace-utilisateur?tab=profil&success=1');
die();
