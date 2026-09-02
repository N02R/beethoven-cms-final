<?php
declare(strict_types=1);

namespace App\Controllers\Guide;

use App\Models\SiteModel;
use App\Models\GuideBlog1Model;

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

        // تحديد مسار الجذر للمشروع
        $root_path = realpath(__DIR__ . '/../../../');

        // 1. جلب بيانات الهيدر والفوتر والإعدادات العامة لكل الموقع
        $data = SiteModel::getGlobalData();

        // 2. جلب بيانات المقال الأول عبر المودل المخصص
        $blog_data_array = GuideBlog1Model::getData();
        $data['guide_data'] = $blog_data_array; // تم توحيد المفتاح ليتطابق مع ما تستخدمه في الـ View

        // فحص حالة تسجيل الدخول كـ Admin وفق مفاتيح الجلسة المعتمدة
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        // إتاحة حالة المشرف داخل مصفوفة البيانات
        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        // 3. تعريف المتغيرات ومصفوفات الستايل (تمت إزالة style.css لكي لا يلغي ستايل الخدمات)
        $data['path_prefix'] = '/';
        $data['page_css'] = ['/assets/css/education.css'];

        $data['page_js'] = [];

        // تفكيك مصفوفة البيانات لتحويل مفاتيحها إلى متغيرات مستقلة داخل ملفات الـ View
        extract($data);

        // 1. استدعاء الهيدر المشترك
        $header_file = $root_path . '/src/Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        } else {
            echo "<div class='container py-3 text-danger'>Header file not found.</div>";
        }

        // 2. استدعاء ملف الـ View الخاص بالمقال
        $view_file = $root_path . '/src/Views/guide/guide-blog1.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>View file not found.</h3></div>";
        }

        // 3. الفوتر المشترك
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        } else {
            echo "<div class='py-3 text-danger'>Footer file not found.</div>";
        }
    }
}
