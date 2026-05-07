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
$id   = (int)($_POST['commande_id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM commande WHERE commande_id=:id AND utilisateur_id=:uid AND statut='en_attente' LIMIT 1");
$stmt->execute(['id'=>$id,'uid'=>$user['id']]);
if($stmt->fetch()) {
    $db->prepare("UPDATE commande SET statut='annulee' WHERE commande_id=:id")->execute(['id'=>$id]);
    $db->prepare("INSERT INTO commande_historique (commande_id,statut,commentaire) VALUES (:id,'annulee','Annulation par le client')")->execute(['id'=>$id]);
}
header('Location: /espace-utilisateur');
die();
