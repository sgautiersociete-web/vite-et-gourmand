<?php
try {
    $host = 'trolley.proxy.rlwy.net';
    $port = '32472';
    $name = 'railway';
    $user = 'root';
    $pass = 'SWVSFKQvzCfgPMHxcEbLpCGDlLUCguoV';
    $dsn  = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    $pdo  = new PDO($dsn, $user, $pass);
    echo '<p style="color:green">✅ Connecté !</p>';
} catch (Exception $e) {
    echo '<p style="color:red">❌ Erreur : ' . $e->getMessage() . '</p>';
}
