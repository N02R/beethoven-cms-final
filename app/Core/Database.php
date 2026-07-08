<?php
namespace App\Core;

use PDO;

class Database {
    private static $instance = null;
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=beethoven_cms;charset=utf8mb4', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // هذه هي الدالة التي يطلبها الكود وتسبب الخطأ حالياً
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
    
    // دالة التحديث التي يستخدمها CMS
    public function update($page, $section, $key, $value) {
        $stmt = $this->pdo->prepare("INSERT INTO site_content (page_key, section_key, field_key, content) 
                                     VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE content = ?");
        return $stmt->execute([$page, $section, $key, $value, $value]);
    }
}
