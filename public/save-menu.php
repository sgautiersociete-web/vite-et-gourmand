<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database';
require_once APP_PATH . '/config/Session';
require_once APP_PATH . '/helpers/functions';
Session::start();
if(!Session::isLoggedIn() || !in_array(Session::user()['role'],['employe','administrateur'])) { header('Location: /connexion'); die(); }
$db   = Database::getInstance();
$id   = (int)($_POST['menu_id'] ?? 0);
$data = [
    'theme_id'         => (int)($_POST['theme_id'] ?? 1),
    'regime_id'        => (int)($_POST['regime_id'] ?? 1),
    'titre'            => trim($_POST['titre'] ?? ''),
    'description'      => trim($_POST['description'] ?? ''),
    'nb_personnes_min' => (int)($_POST['nb_personnes_min'] ?? 2),
    'prix'             => (float)($_POST['prix'] ?? 0),
    'conditions'       => trim($_POST['conditions'] ?? ''),
    'quantite_restante'=> (int)($_POST['quantite_restante'] ?? 0),
];
if($id) {
    $data['id'] = $id;
    $db->prepare("UPDATE menu SET theme_id=:theme_id,regime_id=:regime_id,titre=:titre,description=:description,nb_personnes_min=:nb_personnes_min,prix=:prix,conditions=:conditions,quantite_restante=:quantite_restante WHERE menu_id=:id")->execute($data);
} else {
    $db->prepare("INSERT INTO menu (theme_id,regime_id,titre,description,nb_personnes_min,prix,conditions,quantite_restante) VALUES (:theme_id,:regime_id,:titre,:description,:nb_personnes_min,:prix,:conditions,:quantite_restante)")->execute($data);
}
Session::flash('success','Menu enregistré.');
header('Location: /espace-employe?tab=menus'); die();
