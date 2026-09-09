<?php
declare(strict_types=1);

namespace App\Controllers\Guide;

use App\Models\SiteModel;
use App\Models\GuideBlogOneModel;

class GuideBlog1Controller {
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

        // تحديد مسار الجذر للمشروع بدقة (الصعود 3 مستويات للوصول لمجلد الجذر)
        $root_path = realpath(__DIR__ . '/../../../');

        // 1. جلب بيانات الهيدر والفوتر العامة لكل الموقع عبر SiteModel
        $data = SiteModel::getGlobalData();

        // 2. جلب بيانات الصفحة ودمجها بطريقة آمنة
        $raw_guide_data = class_exists('App\Models\GuideBlogOneModel') ? GuideBlogOneModel::getGuideData() : [];
        
        // التأكد من استخراج المصفوفة الداخلية سواء كانت مغلفة بمفتاح أو لا
        $guide_data = $raw_guide_data['guide_blog1'] ?? $raw_guide_data;
        
        // دمج البيانات العامة مع بيانات الدليل
        $data = array_merge($data, is_array($guide_data) ? $guide_data : []);

        // فحص حالة تسجيل الدخول كـ Admin
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';
        // متغيرات إضافية ومسارات
        $path_prefix = '/';

        // تعريف ملفات الـ CSS والـ JS الخاصة بصفحة من نحن (مع دعم Swiper)
        $page_css = [
            '/assets/css/education.css',
            '/assets/css/edu-services.css'
        ]; 

        // 1. استدعاء الهيدر المشترك
        $header_file = $root_path . '/src/Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        } else {
            echo "<div class='container py-3 text-danger'>Header file not found: {$header_file}</div>";
        }

        // 2. استدعاء الـ View الخاص بالصفحة (تم تصحيح المسار بالاعتماد على $root_path)
        $view_file = $root_path . '/src/Views/guide/guide-blog1.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>About View file not found:</h3> <p>{$view_file}</p></div>";
        }

        // 3. استدعاء مودلز لوحة التحكم الخاصة بالصفحة
        if ($is_admin) {
            $modals_file = $root_path . '/src/Views/guide/includes/admin_guide_blog1_modals.php';
            if (file_exists($modals_file)) {
                include_once $modals_file;
            }
        }

        // 4. استدعاء الفوتر المشترك
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        } else {
            echo "<div class='py-3 text-danger'>Footer file not found: {$footer_file}</div>";
        }
    }
}
