<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use Exception;
use App\Core\Database;

class CoverLetterModel {
    
    /**
     * جلب بيانات صفحة خطاب الطلب من قاعدة البيانات
     */
    public static function getCoverLetterData(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT content_data FROM site_pages WHERE page_key = 'coverletter' LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && !empty($result['content_data'])) {
                $decoded = json_decode($result['content_data'], true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        } catch (Exception $e) {
            // يمكن تسجيل الخطأ إذا لزم الأمر، وإرجاع القيم الافتراضية حصناً للنظام
        }
        
        // القيم الافتراضية في حال عدم وجود سجل مسبق
        return [
            'page_breadcrumb'     => 'خطاب الطلب',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg1.jpg',
            'main_title'          => 'رسالة تعريف/خطاب طلب احترافي يدعم طلبك، أياً كان هدفك أو وجهتك',
            'main_desc'           => '',
            'advice_title'        => 'النقاط التي يجب مراعاتها عند كتابة رسالة التعريف',
            'advice_points'       => [],
            'note_title'          => 'ملاحظات هامة !!',
            'notes'               => [],
            'download_items'      => []
        ];
    }

    /**
     * تحديث بيانات صفحة خطاب الطلب
     */
    public static function updateCoverLetterData(string $jsonData): bool {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE site_pages SET content_data = ? WHERE page_key = 'coverletter'");
            return $stmt->execute([$jsonData]);
        } catch (Exception $e) {
            return false;
        }
    }
}
