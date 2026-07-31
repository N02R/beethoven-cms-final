<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class HomeModel {
    public function getHomeData(): array {
        $db = Database::getConnection();
        
        // استعلام جلب الإعدادات والبيانات من قاعدة البيانات
        $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
        
        return [
            'settings' => $settings
        ];
    }
}
