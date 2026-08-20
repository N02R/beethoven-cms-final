<?php
declare(strict_types=1);

namespace App\Controllers\Services;

use App\Models\SiteModel;

class CoursesController {
    
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

        // 2. جلب بيانات صفحة دورات اللغة الخاصة
        $global_settings = SiteModel::getSettings();
        $lang_data = isset($global_settings['language_page']) ? json_decode($global_settings['language_page'], true) : [
            'page_breadcrumb'     => 'الدورة التحضيرية لشهادات اللغة الألمانية',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg12.png',
            'main_title'          => 'الدورات التحضيرية لشهادات اللغة الألمانية',
            'main_desc'           => '',
            'goals_title'         => 'أهداف الدورة التحضيرية',
            'goals'               => [],
            'warning_text'        => '',
            'cost_title'          => 'اماكن الالتحاق والتكلفة',
            'cost_items'          => []
        ];

        $data['language_page'] = $lang_data;

        // فحص حالة تسجيل الدخول كـ Admin وفق مفاتيح الجلسة المعتمدة
        $is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $user_role = $_SESSION['role'] ?? '';
        $is_admin = $is_logged_in && ($user_role === 'admin' || $user_role === 'super_admin');

        // إتاحة حالة المشرف داخل مصفوفة البيانات لاستخدامها في الـ Views
        $data['is_admin'] = $is_admin;
        $data['is_logged_in'] = $is_logged_in;
        $data['admin_name'] = $_SESSION['admin_name'] ?? 'المشرف';

        $path_prefix = '/';

        // ملفات الـ CSS والـ JS الخاصة بالخدمة
        $page_css = [
            '/assets/css/edu-services.css'
        ]; 

        $page_js = [];
        $custom_script = '';

        // تفكيك مصفوفة البيانات لتحويل مفاتيحها إلى متغيرات مستقلة داخل ملفات الـ View
        extract($data);

        // 1. استدعاء الهيدر المشترك
        $header_file = $root_path . '/src/Views/partials/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        // 2. استدعاء الـ View الخاص بالصفحة
        $view_file = $root_path . '/src/Views/edu-services/courses.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Courses View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/src/Views/edu-services/includes/admin_language_modals.php';
        if (!empty($is_admin) && file_exists($admin_modals)) {
            include_once $admin_modals;
        }

        // 4. استدعاء الفوتر المشترك
        $footer_file = $root_path . '/src/Views/partials/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        }
    }
}
