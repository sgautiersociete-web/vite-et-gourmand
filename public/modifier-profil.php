<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();
if(!Session::isLoggedIn()) { header('Location: /connexion.php'); die(); }
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
header('Location: /espace-utilisateur.php?tab=profil&success=1');
die();
