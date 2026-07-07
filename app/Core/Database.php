<?php
namespace App\Core;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host = '127.0.0.1';
        $db   = 'beethoven_db'; // اسم القاعدة التي أنشأناها
        $user = 'root';
        $pass = '';

        $this->pdo = new \PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
    }

    public static function getInstance() {
        if (!self::$instance) self::$instance = new Database();
        return self::$instance->pdo;
    }
}