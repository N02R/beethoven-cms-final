<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class DashboardModel {
    
    /**
     * جلب كافة بيانات وإحصائيات لوحة التحكم من قاعدة البيانات
     */
    public function getDashboardData(): array {
        $db = Database::getConnection();
        
        $data = [
            'current_logo'   => 'assets/img/logo.png',
            'ad_status'      => 'Draft',
            'ad_type'        => 'text',
            'menu_count'     => 6,
            'consult_emails' => []
        ];

        try {
            // 1. جلب إعدادات الموقع العامة أو الشعار من جدول الإعدادات (إن وجد)
            // $stmt = $db->query("SELECT * FROM site_settings LIMIT 1");
            // $settings = $stmt->fetch(PDO::FETCH_ASSOC);
            // if ($settings) { ... }

            // 2. جلب طلبات الاستشارة من جدولها الخاص في قاعدة البيانات
            $stmt_consult = $db->query("SELECT email, created_at AS date FROM consultations ORDER BY id DESC");
            $data['consult_emails'] = $stmt_consult->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Throwable $e) {
            error_log("Dashboard Model Error: " . $e->getMessage());
        }

        return $data;
    }
}
