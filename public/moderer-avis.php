<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();
if(!Session::isLoggedIn() || !in_array(Session::user()['role'],['employe','administrateur'])) { header('Location: /connexion.php'); die(); }
$db     = Database::getInstance();
$id     = (int)($_POST['avis_id'] ?? 0);
$action = $_POST['action'] === 'valider' ? 'valide' : 'refuse';
$db->prepare("UPDATE avis SET statut=:s WHERE avis_id=:id")->execute(['s'=>$action,'id'=>$id]);
Session::flash('success','Avis modéré.');
header('Location: /espace-employe.php?tab=avis'); die();
