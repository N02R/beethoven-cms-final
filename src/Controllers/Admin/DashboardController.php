<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\DashboardModel;
use App\Config\Database;
use PDO;

class DashboardController {

    private function ensureSessionAndAuth(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isAdmin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && 
                   isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'super_admin'], true);

        if (!$isAdmin) {
            header("Location: /admin/login?error=" . urlencode('يرجى تسجيل الدخول للوصول إلى لوحة التحكم.'));
            exit();
        }
    }
    
    public function index(): void {
        $this->ensureSessionAndAuth();

        // 1. جلب الإحصائيات وطلبات الاستشارة عبر الـ DashboardModel
        $configData = DashboardModel::getDashboardData();

        // 2. جلب كافة إعدادات ومحتويات الموقع من قاعدة البيانات
        $rawSettings = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
            $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
        } catch (\Throwable $e) {
            error_log("Failed to fetch site settings in DashboardController: " . $e->getMessage());
        }

        // 3. تجهيز متغيرات اللوحة الإحصائية
        $consultEmails = $configData['consult_emails'] ?? [];
        $consultCount  = $configData['consult_count'] ?? count($consultEmails);
        $menuLinks     = json_decode($rawSettings['menu_links'] ?? '[]', true);
        $menuCount     = count($menuLinks) > 0 ? count($menuLinks) : 6;
        $currentLogo   = $rawSettings['site_logo'] ?? $configData['current_logo'] ?? 'assets/img/logo.png';
        $adStatus      = $rawSettings['ad_status'] ?? $configData['ad_status'] ?? 'Draft';
        $adType        = $rawSettings['ad_type'] ?? $configData['ad_type'] ?? 'text';

        $roleBadgeText = (($_SESSION['role'] ?? '') === 'super_admin') ? 'مشرف عام (Super Admin)' : 'مسؤول النظام (Admin)';

        // 4. مصفوفة البيانات الشاملة ($data) التي تغذي لوحة التحكم والـ Modals
        $data = [
            'current_logo'           => $currentLogo,
            'ad_status'              => $adStatus,
            'ad_type'                => $adType,
            'menu_count'             => $menuCount,
            'consult_emails'         => $consultEmails,
            'consult_count'          => $consultCount,
            'role_badge_text'        => $roleBadgeText,

            'site_title'             => $rawSettings['site_title'] ?? 'Beethoven CMS',
            'site_email'             => $rawSettings['site_email'] ?? '',
            'site_logo'              => $currentLogo,
            'site_logo_path'         => $rawSettings['site_logo_path'] ?? '',

            'social_links'           => json_decode($rawSettings['social_links'] ?? '[]', true),
            'menu_links'             => $menuLinks,
            'languages'              => json_decode($rawSettings['languages'] ?? '[]', true),
            'announcement'           => json_decode($rawSettings['announcement'] ?? '{}', true),

            'consult_title'          => $rawSettings['consult_title'] ?? '',
            'consult_desc'           => $rawSettings['consult_desc'] ?? '',
            'footer_desc'            => $rawSettings['footer_desc'] ?? '',
            'hero'                   => json_decode($rawSettings['hero'] ?? '{}', true),
            'services_section_title' => $rawSettings['services_section_title'] ?? '',
            'services_section_desc'  => $rawSettings['services_section_desc'] ?? '',
            'services'               => json_decode($rawSettings['services'] ?? '[]', true),

            'team_title'             => $rawSettings['team_title'] ?? 'فريق العمل',
            'team_desc'              => $rawSettings['team_desc'] ?? '',
            'team_members'           => json_decode($rawSettings['team_items'] ?? '[]', true),

            'raw_settings'           => $rawSettings
        ];

        // 5. استدعاء ملف الـ View الخاص باللوحة الاحترافية
        $viewFile = __DIR__ . '/../../Views/admin/dashboard.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            http_response_code(404);
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

        header("Location: /admin/login?message=" . urlencode('تم تسجيل الخروج بنجاح.'));
        exit();
    }
}
