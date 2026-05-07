<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database';
require_once APP_PATH . '/config/Session';
require_once APP_PATH . '/helpers/functions';
Session::start();
if(!Session::isLoggedIn() || Session::user()['role'] !== 'administrateur') { header('Location: /connexion'); die(); }
$db  = Database::getInstance();
$id  = (int)($_POST['employe_id'] ?? 0);
$act = (int)($_POST['actif'] ?? 0);
$db->prepare("UPDATE utilisateur SET actif=:a WHERE utilisateur_id=:id")->execute(['a'=>$act,'id'=>$id]);
Session::flash('success','Compte mis à jour.');
header('Location: /espace-admin?tab=employes'); die();
