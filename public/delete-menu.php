<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();
if(!Session::isLoggedIn() || !in_array(Session::user()['role'],['employe','administrateur'])) { header('Location: /connexion.php'); die(); }
$db = Database::getInstance();
$id = (int)($_POST['menu_id'] ?? 0);
$db->prepare("UPDATE menu SET actif=0 WHERE menu_id=:id")->execute(['id'=>$id]);
Session::flash('success','Menu supprimé.');
header('Location: /espace-employe.php?tab=menus'); die();
