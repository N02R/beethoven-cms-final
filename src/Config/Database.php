<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * منع إنشاء أو استنساخ كائن جديد (Strict Singleton Pattern)
     */
    private function __construct() {}
    private function __clone() {}
    public function __wakeup(): void
    {
        throw new RuntimeException('Cannot unserialize a singleton Database instance.');
    }

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host    = $_ENV['DB_HOST'] ?? null;
            $port    = $_ENV['DB_PORT'] ?? '3306';
            $db      = $_ENV['DB_NAME'] ?? null;
            $user    = $_ENV['DB_USER'] ?? null;
            $pass    = $_ENV['DB_PASS'] ?? null;
            $charset = 'utf8mb4';

            if (empty($host) || empty($db) || empty($user) || $pass === null) {
                throw new RuntimeException('Database configuration is missing in environment variables.');
            }

            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_STRINGIFY_FETCHES  => false,
            ];

            // دعم PHP 8.5+ (Pdo\Mysql::ATTR_INIT_COMMAND) مع التوافق الكامل مع الإصدارات الأقدم
            $initAttr = defined('Pdo\Mysql::ATTR_INIT_COMMAND')
                ? constant('Pdo\Mysql::ATTR_INIT_COMMAND')
                : (defined('PDO::MYSQL_ATTR_INIT_COMMAND') ? constant('PDO::MYSQL_ATTR_INIT_COMMAND') : null);

            if ($initAttr !== null) {
                $options[$initAttr] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci";
            }

            try {
                self::$instance = new PDO($dsn, (string)$user, (string)$pass, $options);
            } catch (PDOException $e) {
                error_log('Database Connection Error: ' . $e->getMessage());
                throw new RuntimeException('A database connection error occurred.');
            }
        }

        return self::$instance;
    }

    /**
     * إغلاق الاتصال يدوياً عند الحاجة
     */
    public static function disconnect(): void
    {
        self::$instance = null;
    }
}
