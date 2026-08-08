<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\DashboardModel;
use App\Config\Database;
use PDO;

class DashboardController {
    
    public function index(): void {
        // 1. السماح بالوصول وتفعيل الحماية المركزية
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }

        // 2. بدء الجلسة والتحقق من الصلاحيات
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin');

        if (!$is_admin) {
            header("Location: index.php?url=admin/login&error=" . urlencode('يرجى تسجيل الدخول للوصول إلى لوحة التحكم.'));
            exit();
        }

        // 3. جلب الإحصائيات وطلبات الاستشارة عبر الـ DashboardModel الأصلي
        $dashboardModel = new DashboardModel();
        $config_data = $dashboardModel->getDashboardData();

        // 4. جلب كافة إعدادات ومحتويات الموقع من قاعدة البيانات (لتغذية الـ Modals والتعديل المباشر)
        $rawSettings = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
        } catch (\Throwable $e) {
            error_log("Failed to fetch site settings in DashboardController: " . $e->getMessage());
        }

        // تجهيز متغيرات اللوحة الإحصائية
        $consult_emails = $config_data['consult_emails'] ?? [];
        $consult_count  = count($consult_emails);
        $menu_links     = json_decode($rawSettings['menu_links'] ?? '[]', true);
        $menu_count     = count($menu_links) > 0 ? count($menu_links) : 6;
        $current_logo   = $rawSettings['site_logo'] ?? $config_data['current_logo'] ?? 'assets/img/logo.png';
        $ad_status      = $rawSettings['ad_status'] ?? $config_data['ad_status'] ?? 'Draft';
        $ad_type        = $rawSettings['ad_type'] ?? $config_data['ad_type'] ?? 'text';

        $role_badge_text = (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin') ? 'مشرف عام (Super Admin)' : 'مسؤول النظام (Admin)';

        // 5. مصفوفة البيانات الشاملة ($data) التي تغذي لوحة التحكم والـ Modals
        $data = [
            // إحصائيات اللوحة وجداول الطلبات
            'current_logo'           => $current_logo,
            'ad_status'              => $ad_status,
            'ad_type'                => $ad_type,
            'menu_count'             => $menu_count,
            'consult_emails'         => $consult_emails,
            'consult_count'          => $consult_count,
            'role_badge_text'        => $role_badge_text,

            // الإعدادات العامة الأساسية
            'site_title'             => $rawSettings['site_title'] ?? 'Beethoven CMS',
            'site_email'             => $rawSettings['site_email'] ?? '',
            'site_logo'              => $current_logo,
            'site_logo_path'         => $rawSettings['site_logo_path'] ?? '',

            // الروابط والقوائم والأقسام مفكوكة بصيغة JSON للـ Modals
            'social_links'           => json_decode($rawSettings['social_links'] ?? '[]', true),
            'menu_links'             => $menu_links,
            'languages'              => json_decode($rawSettings['languages'] ?? '[]', true),
            'announcement'           => json_decode($rawSettings['announcement'] ?? '{}', true),

            // الفوتر، الهيرو، والخدمات
            'consult_title'          => $rawSettings['consult_title'] ?? '',
            'consult_desc'           => $rawSettings['consult_desc'] ?? '',
            'footer_desc'            => $rawSettings['footer_desc'] ?? '',
            'hero'                   => json_decode($rawSettings['hero'] ?? '{}', true),
            'services_section_title' => $rawSettings['services_section_title'] ?? '',
            'services_section_desc'  => $rawSettings['services_section_desc'] ?? '',
            'services'               => json_decode($rawSettings['services'] ?? '[]', true),

            // فريق العمل والأقسام الأخرى
            'team_title'             => $rawSettings['team_title'] ?? 'فريق العمل',
            'team_desc'              => $rawSettings['team_desc'] ?? '',
            'team_members'           => json_decode($rawSettings['team_items'] ?? '[]', true),

            // مرجع خام إن دعت الحاجة
            'raw_settings'           => $rawSettings
        ];

        // 6. استدعاء ملف الـ View الخاص باللوحة الاحترافية
        $root_path = realpath(__DIR__ . '/../../../');
        $view_file = $root_path . '/src/Views/admin/dashboard.php';
        
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "Dashboard View file not found.";
        }
    }

    /**
     * تسجيل الخروج الآمن وتدمير الجلسة والكوكيز
     */
    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header("Location: index.php?url=admin/login&message=" . urlencode('تم تسجيل الخروج بنجاح.'));
        exit();
    }
}
