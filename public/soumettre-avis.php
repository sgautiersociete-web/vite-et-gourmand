<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();
if(!Session::isLoggedIn()) { header('Location: /connexion.php'); die(); }
$user        = Session::user();
$db          = Database::getInstance();
$commandeId  = (int)($_POST['commande_id'] ?? 0);
$note        = max(1, min(5, (int)($_POST['note'] ?? 1)));
$commentaire = trim($_POST['commentaire'] ?? '');
if($commandeId && $commentaire) {
    $stmt = $db->prepare("INSERT IGNORE INTO avis (commande_id,utilisateur_id,note,commentaire) VALUES (:cid,:uid,:note,:comment)");
    $stmt->execute(['cid'=>$commandeId,'uid'=>$user['id'],'note'=>$note,'comment'=>$commentaire]);
}
header('Location: /espace-utilisateur.php');
die();
