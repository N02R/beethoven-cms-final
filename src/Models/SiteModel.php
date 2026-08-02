namespace App\Models;

use App\Config\Database;
use PDO;

class SiteModel {
    
    // الدالة الأصلية لجلب كل الإعدادات
    public static function getSettings(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $settings = [];
        foreach ($results as $row) {
            if (isset($row['setting_key'])) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
        return $settings;
    }

    /**
     * دالة موحدة لجلب وتجهيز بيانات الهيدر والفوتر والإعدادات العامة لكل الموقع
     */
    public static function getGlobalData(): array {
        $settings = self::getSettings();

        return [
            'site_title'        => $settings['site_title'] ?? 'Beethoven Services',
            'site_email'        => $settings['site_email'] ?? '',
            'site_logo_path'    => $settings['site_logo_path'] ?? '',
            'social_links'      => isset($settings['social_links']) ? json_decode($settings['social_links'], true) : [],
            'menu_links'        => isset($settings['menu_links']) ? json_decode($settings['menu_links'], true) : [],
            'languages'         => isset($settings['languages']) ? json_decode($settings['languages'], true) : [],
            'announcement'      => isset($settings['announcement']) ? json_decode($settings['announcement'], true) : [],
            
            // بيانات الفوتر المشتركة
            'consult_title'     => $settings['consult_title'] ?? '',
            'consult_desc'      => $settings['consult_desc'] ?? '',
            'footer_desc'       => $settings['footer_desc'] ?? '',
            'footer_col2_title' => $settings['footer_col2_title'] ?? 'روابط سريعة',
            'footer_col3_title' => $settings['footer_col3_title'] ?? 'تواصل معنا',
            'footer_col3_links' => isset($settings['footer_col3_links']) ? json_decode($settings['footer_col3_links'], true) : [],
        ];
    }

    // دالة التحديث تبقى كما هي لديكِ...
    public static function updateSettings(array $data): bool { ... }
}
