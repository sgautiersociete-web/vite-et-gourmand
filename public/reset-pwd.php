<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/config/Database';

$hash = password_hash('password', PASSWORD_BCRYPT, ['cost' => 10]);
echo "Hash généré : " . $hash . "<br>";

$db = Database::getInstance();
$stmt = $db->prepare("UPDATE utilisateur SET password_hash = :hash WHERE email IN ('admin@viteetgourmand.fr', 'employe1@viteetgourmand.fr', 'client@test.fr')");
$stmt->execute(['hash' => $hash]);
echo "Mots de passe mis à jour ! Lignes affectées : " . $stmt->rowCount();
echo "<br><a href='/connexion'>Se connecter</a>";
 
