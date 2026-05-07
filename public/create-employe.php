<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Session.php';
require_once APP_PATH . '/helpers/functions.php';
Session::start();
if(!Session::isLoggedIn() || Session::user()['role'] !== 'administrateur') { header('Location: /connexion.php'); die(); }
$db  = Database::getInstance();
$nom    = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$email  = trim($_POST['email'] ?? '');
$pwd    = $_POST['password'] ?? '';
if(!filter_var($email,FILTER_VALIDATE_EMAIL) || !isPasswordStrong($pwd)) {
    Session::flash('error','Email invalide ou mot de passe trop faible.');
    header('Location: /espace-admin.php?tab=employes'); die();
}
$hash = password_hash($pwd, PASSWORD_BCRYPT, ['cost'=>12]);
$db->prepare("INSERT INTO utilisateur (role_id,email,password_hash,nom,prenom,gsm,adresse,ville,code_postal) VALUES (2,:email,:hash,:nom,:prenom,'0000000000','N/A','Bordeaux','33000')")
   ->execute(['email'=>$email,'hash'=>$hash,'nom'=>$nom,'prenom'=>$prenom]);
Session::flash('success','Compte employé créé. Communiquez le mot de passe en main propre.');
header('Location: /espace-admin.php?tab=employes'); die();
