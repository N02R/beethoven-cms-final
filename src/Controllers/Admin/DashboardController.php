<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\DashboardModel;

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
            header("Location: /login?error=" . urlencode('يرجى تسجيل الدخول للوصول إلى لوحة التحكم.'));
            exit();
        }

        // 3. جلب البيانات عبر الـ Model وقاعدة البيانات بدلاً من الـ JSON
        $dashboardModel = new DashboardModel();
        $config_data = $dashboardModel->getDashboardData();

        // تجهيز المتغيرات للـ View
        $current_logo   = $config_data['current_logo'];
        $ad_status      = $config_data['ad_status'];
        $ad_type        = $config_data['ad_type'];
        $menu_count     = count($config_data['menu_links'] ?? []);
        $consult_emails = $config_data['consult_emails'];
        $consult_count  = count($consult_emails);

        $role_badge_text = ($_SESSION['role'] === 'super_admin') ? 'مشرف عام (Super Admin)' : 'مسؤول النظام (Admin)';

        $data = [
            'current_logo'    => $current_logo,
            'ad_status'       => $ad_status,
            'ad_type'         => $ad_type,
            'menu_count'      => $menu_count > 0 ? $menu_count : 6,
            'consult_emails'  => $consult_emails,
            'consult_count'   => $consult_count,
            'role_badge_text' => $role_badge_text
        ];

        // 4. استدعاء ملف الـ View الخاص باللوحة
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

        header("Location: /login?message=" . urlencode('تم تسجيل الخروج بنجاح.'));
        exit();
    }
}
