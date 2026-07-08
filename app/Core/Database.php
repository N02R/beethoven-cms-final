<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost';
    private $db   = 'beethoven_cms';
    private $user = 'root';
    private $pass = '';
    private $pdo;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
    }

    // جلب محتوى معين
    public function getContent($page, $section, $field) {
        $stmt = $this->pdo->prepare("SELECT content FROM site_content WHERE page_key = ? AND section_key = ? AND field_key = ?");
        $stmt->execute([$page, $section, $field]);
        return $stmt->fetchColumn();
    }

    // تحديث المحتوى (للمدير)
    public function updateContent($page, $section, $field, $value) {
        $stmt = $this->pdo->prepare("INSERT INTO site_content (page_key, section_key, field_key, content) 
                                     VALUES (?, ?, ?, ?) 
                                     ON DUPLICATE KEY UPDATE content = ?");
        return $stmt->execute([$page, $section, $field, $value, $value]);
    }
}
