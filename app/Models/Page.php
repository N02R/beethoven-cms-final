<?php
namespace App\Models;
use App\Core\Database;

class Page {
    public static function getCount() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT COUNT(*) FROM pages"); // افترضنا وجود جدول باسم pages
        return $stmt->fetchColumn();
    }
}
