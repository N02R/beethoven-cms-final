<?php
declare(strict_types=1);

namespace App\Controllers\Guide;

use App\Config\Database;
use PDO;

class GuideBlog1Controller {
    public function index(): void
    {
        // إدارة الجلسات بأمان تام
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_strict_mode', '1');
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }
            session_start();
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

            $pageData = json_decode($settings['guide_blog_one_page'] ?? '', true) ?: [];

        } catch (\Exception $e) {
            error_log("GuideBlog1 Error: " . $e->getMessage());
            $pageData = [];
        }

        // فحص حالة تسجيل الدخول كـ Admin وفق مفاتيح الجلسة المعتمدة
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        // متغيرات إضافية قد تحتاجها الـ View
        $path_prefix = '/';
        $page_css = ['/assets/css/style.css', '/assets/css/education.css', '/assets/css/edu-services.css'];

        // دمج بيانات الصفحة مع حالة المشرف
        $pageData['is_admin'] = $is_admin;
        $pageData['is_logged_in'] = $is_logged_in;
        $pageData['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        // تفكيك مصفوفة البيانات لتحويل مفاتيحها إلى متغيرات داخل ملفات الـ View
        extract($pageData);

        // 1. استدعاء الهيدر المشترك
        $header_file = __DIR__ . '/../../Views/partials/header.php';
        if (file_exists($header_file)) {
            require_once $header_file;
        } else {
            echo "<div class='container py-3 text-danger'>Header file not found.</div>";
        }

        // 2. استدعاء ملف الـ View الخاص بالمقال (guide-blog1.php)
        $view_file = __DIR__ . '/../../Views/guide/guide-blog1.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>View file not found.</h3></div>";
        }

        // 3. استدعاء الفوتر المشترك
        $footer_file = __DIR__ . '/../../Views/partials/footer.php';
        if (file_exists($footer_file)) {
            require_once $footer_file;
        } else {
            echo "<div class='py-3 text-danger'>Footer file not found.</div>";
        }
    }
}
