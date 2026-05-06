<?php
class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: 'localhost';
            $port = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306';
            $name = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'railway';
            $user = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
            $pass = getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: '';

            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            self::$instance = new PDO($dsn, $user, $pass, $options);
        }
        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}
