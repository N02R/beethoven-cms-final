<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

class Database {
    private static ?PDO $instance = null;

    /**
     * منع إنشاء كنسخة جديدة (Singleton Pattern)
     */
    private function __construct() {}
    private function __clone() {}

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            // جلب البيانات حصرياً من متغيرات البيئة دون أي قيم افتراضية محلية
            $host    = $_ENV['DB_HOST'] ?? null;
            $db      = $_ENV['DB_NAME'] ?? null;
            $user    = $_ENV['DB_USER'] ?? null;
            $pass    = $_ENV['DB_PASS'] ?? null;
            $charset = 'utf8mb4';

            // التحقق الصارم من توفر الإعدادات الأساسية
            if (!$host || !$db || !$user) {
                throw new RuntimeException("Database configuration is missing in environment variables.");
            }

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false, // حماية صارمة ضد SQL Injection
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // تسجيل الخطأ سرياً في السيرفر ورمي استثناء ليتم التعامل معه مركزياً
                error_log("Database Connection Error: " . $e->getMessage());
                throw new RuntimeException("A database connection error occurred.");
            }
        }

        return self::$instance;
    }
}
