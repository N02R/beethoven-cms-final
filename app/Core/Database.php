<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    // إعدادات الاتصال - قمنا بتغيير localhost إلى 127.0.0.1 لحل مشكلة الـ Socket في Termux
    private $host = '127.0.0.1'; 
    private $db   = 'beethoven_cms';
    private $user = 'root';
    private $pass = '';

    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            // توضيح الخطأ بدلاً من الشاشة البيضاء
            die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
    }

    // نمط الـ Singleton لضمان اتصال واحد فقط طوال فترة تشغيل الصفحة
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    // دالة التحديث الديناميكي (تُستخدم في لوحة التحكم)
    public function update($page, $section, $key, $value) {
        $stmt = $this->pdo->prepare("
            INSERT INTO site_content (page_key, section_key, field_key, content) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE content = ?
        ");
        return $stmt->execute([$page, $section, $key, $value, $value]);
    }
}
