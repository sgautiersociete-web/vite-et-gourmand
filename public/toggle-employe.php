<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();
if(!Session::isLoggedIn() || Session::user()['role'] !== 'administrateur') { header('Location: /connexion.php'); die(); }
$db  = Database::getInstance();
$id  = (int)($_POST['employe_id'] ?? 0);
$act = (int)($_POST['actif'] ?? 0);
$db->prepare("UPDATE utilisateur SET actif=:a WHERE utilisateur_id=:id")->execute(['a'=>$act,'id'=>$id]);
Session::flash('success','Compte mis à jour.');
header('Location: /espace-admin.php?tab=employes'); die();
