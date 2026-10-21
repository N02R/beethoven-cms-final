<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;
use Throwable;

class DashboardModel {
    
    /**
     * جلب كافة بيانات وإحصائيات لوحة التحكم من قاعدة البيانات
     */
    public static function getDashboardData(): array {
        $data = [
            'current_logo'   => 'assets/img/logo.png',
            'ad_status'      => 'Draft',
            'ad_type'        => 'text',
            'menu_count'     => 6,
            'consult_emails' => [],
            'consult_count'  => 0 // إضافته ليتطابق مع ما يتم عرضه في الـ Dashboard
        ];

        try {
            $db = Database::getConnection();
            
            // جلب طلبات الاستشارة من جدولها الخاص في قاعدة البيانات
            $stmt_consult = $db->query("SELECT email, created_at AS date FROM consultations ORDER BY id DESC");
            if ($stmt_consult) {
                $consults = $stmt_consult->fetchAll(PDO::FETCH_ASSOC);
                $data['consult_emails'] = $consults;
                $data['consult_count']  = count($consults); // حساب العدد الكلي تلقائياً
            }

        } catch (Throwable $e) {
            error_log("Dashboard Model Error: " . $e->getMessage());
        }

        return $data;
    }
}
