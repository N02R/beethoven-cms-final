<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = '127.0.0.1';
            $db   = 'beethoven_cms'; // استبدليها باسم قاعدة البيانات لديكِ
            $user = 'root';          // اسم مستخدم قاعدة البيانات
            $pass = '';              // كلمة المرور
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // عدم كشف مسارات أو تفاصيل قاعدة البيانات للمستخدم نهائياً (Security Best Practice)
                error_log("Database Connection Error: " . $e->getMessage());
                http_response_code(500);
                exit("Database connection failed. Please try again later.");
            }
        }

        return self::$instance;
    }
}
