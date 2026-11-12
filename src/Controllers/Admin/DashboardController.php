<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\DashboardModel;
use App\Config\Database;
use PDO;

class DashboardController {

    private const SESSION_IDLE_TIMEOUT = 1800; // 30 دقيقة كحد أقصى للخمول (معيار BSI)

    private function ensureSessionAndAuth(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. التحقق الأساسي من تسجيل الدخول والصلاحية
        $isAdmin = !empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true &&
                   in_array($_SESSION['role'] ?? '', ['admin', 'super_admin'], true);

        if (!$isAdmin) {
            $this->forceLogout('يرجى تسجيل الدخول للوصول إلى لوحة التحكم.');
        }

        // 2. التحقق من بصمة المتصفح لمنع اختطاف الجلسة (Session Hijacking)
        $currentAgentHash = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');
        if (!empty($_SESSION['user_agent_hash']) && !hash_equals($_SESSION['user_agent_hash'], $currentAgentHash)) {
            $this->forceLogout('تم إنهاء الجلسة لأسباب أمنية، يرجى تسجيل الدخول مجدداً.');
        }

        // 3. التحقق من انتهاء مهلة الخمول (Idle Timeout)
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - (int)$_SESSION['LAST_ACTIVITY'] > self::SESSION_IDLE_TIMEOUT)) {
            $this->forceLogout('انتهت صلاحية الجلسة بسبب عدم النشاط، يرجى تسجيل الدخول مجدداً.');
        }

        $_SESSION['LAST_ACTIVITY'] = time();

        // 4. ضمان وجود رمز CSRF نشط لعمليات لوحة التحكم
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    private function forceLogout(string $reason): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();

        header("Location: /admin/login?message=" . urlencode($reason));
        exit();
    }

    private function decodeJsonSafe(mixed $value, array $default = []): array {
        if (is_array($value)) {
            return $value;
        }
        if (!is_string($value) || trim($value) === '') {
            return $default;
        }
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : $default;
    }

    public function index(): void {
        $this->ensureSessionAndAuth();

        // 1. جلب الإحصائيات وطلبات الاستشارة عبر الـ DashboardModel
        $configData = class_exists(DashboardModel::class) ? DashboardModel::getDashboardData() : [];

        // 2. جلب كافة إعدادات ومحتويات الموقع من جدول global_settings
        $rawSettings = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM global_settings");
            $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
        } catch (\Throwable $e) {
            error_log("Failed to fetch site settings in DashboardController: " . $e->getMessage());
        }

        // 3. تجهيز متغيرات اللوحة الإحصائية بأمان تام ضد أخطاء PHP 8 Strict Types
        $consultEmails = is_array($configData['consult_emails'] ?? null) ? $configData['consult_emails'] : [];
        $consultCount  = (int)($configData['consult_count'] ?? count($consultEmails));
        $menuLinks     = $this->decodeJsonSafe($rawSettings['menu_links'] ?? null, []);
        $menuCount     = count($menuLinks) > 0 ? count($menuLinks) : 6;
        $currentLogo   = (string)($rawSettings['site_logo'] ?? ($configData['current_logo'] ?? 'assets/img/logo.png'));
        $adStatus      = (string)($rawSettings['ad_status'] ?? ($configData['ad_status'] ?? 'Draft'));
        $adType        = (string)($rawSettings['ad_type'] ?? ($configData['ad_type'] ?? 'text'));

        $roleBadgeText = (($_SESSION['role'] ?? '') === 'super_admin') ? 'مشرف عام (Super Admin)' : 'مسؤول النظام (Admin)';

        // 4. مصفوفة البيانات الشاملة ($data) التي تغذي لوحة التحكم والـ Modals
        $data = [
            'csrf_token'             => $_SESSION['csrf_token'],
            'admin_name'             => $_SESSION['admin_name'] ?? 'المشرف',
            'current_logo'           => $currentLogo,
            'ad_status'              => $adStatus,
            'ad_type'                => $adType,
            'menu_count'             => $menuCount,
            'consult_emails'         => $consultEmails,
            'consult_count'          => $consultCount,
            'role_badge_text'        => $roleBadgeText,

            'site_title'             => (string)($rawSettings['site_title'] ?? 'Beethoven CMS'),
            'site_email'             => (string)($rawSettings['site_email'] ?? ''),
            'site_logo'              => $currentLogo,
            'site_logo_path'         => (string)($rawSettings['site_logo_path'] ?? ''),

            'social_links'           => $this->decodeJsonSafe($rawSettings['social_links'] ?? null, []),
            'menu_links'             => $menuLinks,
            'languages'              => $this->decodeJsonSafe($rawSettings['languages'] ?? null, []),
            'announcement'           => $this->decodeJsonSafe($rawSettings['announcement'] ?? null, []),

            'consult_title'          => (string)($rawSettings['consult_title'] ?? ''),
            'consult_desc'           => (string)($rawSettings['consult_desc'] ?? ''),
            'footer_desc'            => (string)($rawSettings['footer_desc'] ?? ''),
            'hero'                   => $this->decodeJsonSafe($rawSettings['hero'] ?? null, []),
            'services_section_title' => (string)($rawSettings['services_section_title'] ?? ''),
            'services_section_desc'  => (string)($rawSettings['services_section_desc'] ?? ''),
            'services'               => $this->decodeJsonSafe($rawSettings['services'] ?? null, []),

            'team_title'             => (string)($rawSettings['team_title'] ?? 'فريق العمل'),
            'team_desc'              => (string)($rawSettings['team_desc'] ?? ''),
            'team_members'           => $this->decodeJsonSafe($rawSettings['team_items'] ?? null, []),

            'raw_settings'           => $rawSettings
        ];

        // 5. تعريف الثابت الأمني لتجاوز حماية العرض بشكل آمن
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }

        // 6. استدعاء ملف الـ View الخاص باللوحة الاحترافية
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

        $this->forceLogout('تم تسجيل الخروج بنجاح.');
    }
}
