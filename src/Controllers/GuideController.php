<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\SiteModel;

class GuideController {
    public function index(string $lang = 'de'): void {
        // حماية مخرجات اللغة المعروضة
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');

        // إدارة الجلسات بأمان تام
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_strict_mode', '1');
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }
            session_start();
        }

        // ==========================================
        // 🌟 توحيد اللغة في كل الموقع والمودلات
        // ==========================================
        $_SESSION['site_lang'] = $lang; // حفظ اللغة الحالية في الجلسة
        $current_lang = $lang;         // تعريف المتغير للمودلات والصفحة

        // تحميل ملف الترجمة بناءً على اللغة القادمة من الـ Router
        $lang_file = __DIR__ . '/../Lang/' . $current_lang . '.php';
        $lang_data = file_exists($lang_file) ? require $lang_file : require __DIR__ . '/../Lang/ar.php';
        
        // إتاحة مصفوفة الترجمة باسم $lang لصفحة الـ View
        $lang = $lang_data;
        // ==========================================

        // تحديد مسار الجذر للمشروع
        $root_path = realpath(__DIR__ . '/../../');

        // جلب بيانات الموقع العامة والدليل الشامل عبر SiteModel
        $data = SiteModel::getGlobalData();

        // فحص حالة تسجيل الدخول كـ Admin
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        // إتاحة حالة المشرف داخل مصفوفة البيانات والمحيط
        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        // متغيرات إضافية للـ View
        $path_prefix = '/';
        $page_css = ['/assets/css/style.css'];

        // 🌟 النهج الآمن والقياسي: تعريف متغيرات صفحة الدليل بوضوح
        $guide_title    = $data['guide_title'] ?? 'الدليل الشامل';
        $guide_desc     = $data['guide_desc'] ?? '';
        
        // ربط نص زر قراءة المزيد بملف الترجمة النشطة حصرياً
        $read_more_text = $lang_data['read_more'] ?? ('قراءة المزيد');
        
        $guide_items    = $data['guide_items'] ?? [];

        // 1. استدعاء الهيدر المشترك
        $header_file = __DIR__ . '/../Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        } else {
            echo "<div class='container py-3 text-danger'>Header file not found.</div>";
        }

        // 2. استدعاء ملف الـ View الرئيسي (guide.php)
        $view_file = __DIR__ . '/../Views/guide.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>View file not found.</h3></div>";
        }

        // 3. استدعاء الفوتر المشترك (الذي يحقن مودلات لوحة التحكم مركزياً)
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        } else {
            echo "<div class='py-3 text-danger'>Footer file not found.</div>";
        }
    }

    public function switchLang(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'en', 'de'], true)) {
            $_SESSION['site_lang'] = $_GET['lang'];
        }
        
        $redirect_url = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $redirect_url);
        exit;
    }
}
