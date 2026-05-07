<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();
if(!Session::isLoggedIn() || !in_array(Session::user()['role'],['employe','administrateur'])) { header('Location: /connexion.php'); die(); }
$db     = Database::getInstance();
$id     = (int)($_POST['commande_id'] ?? 0);
$statut = $_POST['statut'] ?? '';
$motif  = trim($_POST['motif'] ?? '');
$mode   = $_POST['mode_contact'] ?? '';
if($statut === 'annulee' && (!$motif || !$mode)) {
    Session::flash('error','Motif et mode de contact requis pour annuler.');
    header('Location: /espace-employe.php?tab=commandes'); die();
}
$db->prepare("UPDATE commande SET statut=:s,motif_annulation=:m,mode_contact=:mc WHERE commande_id=:id")
   ->execute(['s'=>$statut,'m'=>$motif,'mc'=>$mode,'id'=>$id]);
$db->prepare("INSERT INTO commande_historique (commande_id,statut,commentaire) VALUES (:id,:s,:m)")
   ->execute(['id'=>$id,'s'=>$statut,'m'=>$motif]);
Session::flash('success','Commande mise à jour.');
header('Location: /espace-employe.php?tab=commandes'); die();
