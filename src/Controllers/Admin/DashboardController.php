<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

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
            header("Location: login.php?error=" . urlencode('يرجى تسجيل الدخول للوصول إلى لوحة التحكم.'));
            exit();
        }

        // 3. قراءة ملف الإعدادات
        $root_path = realpath(__DIR__ . '/../../../');
        $config_file_path = $root_path . '/announcement_config.json';
        $config_data = [];
        if (file_exists($config_file_path)) {
            $config_data = json_decode(file_get_contents($config_file_path), true);
        }

        // تجهيز المتغيرات للـ View
        $current_logo   = $config_data['site_logo_path'] ?? 'assets/img/logo.png';
        $ad_status      = $config_data['status'] ?? 'Draft';
        $ad_type        = $config_data['type'] ?? 'text';
        $menu_count     = isset($config_data['menu_links']) ? count($config_data['menu_links']) : 6;
        $consult_emails = $config_data['consultation_emails'] ?? [];
        $consult_count  = count($consult_emails);

        $role_badge_text = ($_SESSION['role'] === 'super_admin') ? 'مشرف عام (Super Admin)' : 'مسؤول النظام (Admin)';

        $data = [
            'current_logo'    => $current_logo,
            'ad_status'       => $ad_status,
            'ad_type'         => $ad_type,
            'menu_count'      => $menu_count,
            'consult_emails'  => $consult_emails,
            'consult_count'   => $consult_count,
            'role_badge_text' => $role_badge_text
        ];

        // 4. استدعاء ملف الـ View الخاص باللوحة
        $view_file = $root_path . '/src/Views/admin/dashboard.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "Dashboard View file not found.";
        }
    }
}
