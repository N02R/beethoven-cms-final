<?php
declare(strict_types=1);

namespace App\Controllers\Services;

class FoundationController {
    
    public function index(string $lang = 'de'): void {
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');

        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_strict_mode', '1');
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }
            session_start();
        }

        $root_path = realpath(__DIR__ . '/../../../');
        $config_file = $root_path . '/announcement_config.json';
        $global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];
        
        $stk_data = $global_data['studienkolleg_page'] ?? [
            'page_breadcrumb'   => 'الدورة التأسيسية / السنة التحضيرية',
            'page_breadcrumb_url' => '#',
            'hero_img'          => 'assets/img/education/serviceimg11.png',
            'hero_position'     => 'center -20rem',
            'main_title'        => 'الدورة التأسيسيّة/السنة التحضيرية \"Studienkolleg\"',
            'main_desc'         => '',
            'goals_title'       => 'أهداف الدورة التأسيسية',
            'goals_items'       => [],
            'learning_title'    => '',
            'learning_intro'    => '',
            'learning_p1'       => '',
            'learning_p2'       => '',
            'courses_title'     => 'أنواع دورات السنة التحضيرية',
            'courses_items'     => [],
            'uni_type_title'    => '',
            'uni_type_intro'    => '',
            'uni_public'        => '',
            'uni_applied'       => '',
            'types_title'       => 'أنواع السنة التحضيرية في ألمانيا',
            'type_public_desc'  => '',
            'type_private_desc' => '',
            'notes_title'       => 'ملاحظات هامة !!',
            'notes_items'       => [],
            'exam_title'        => '',
            'exam_desc'         => '',
            'fsp_title'         => '',
            'fsp_desc'          => '',
            'tips_title'        => 'نصائح مهمة قبل التقديم',
            'tips_items'        => []
        ];

        $data = $global_data;
        $data['studienkolleg_page'] = $stk_data;

        $is_admin = !empty($_SESSION['is_admin']);
        $path_prefix = '/';

        // ملفات الـ CSS والـ JS الخاصة بالخدمة
        $page_css = [
            '/edu-services/css/edu-services.css'
        ]; 

        $page_js = [];
        $custom_script = '';

        // 1. استدعاء الهيدر المشترك
        $header_file = $root_path . '/includes/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        // 2. استدعاء الـ View الخاص بالصفحة
        $view_file = __DIR__ . '/../../Views/edu-services/foundation.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Foundation View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/includes/admin_studienkolleg_modals.php';
        if (!empty($is_admin) && file_exists($admin_modals)) {
            include_once $admin_modals;
        }

        // 4. استدعاء الفوتر المشترك
        $footer_file = $root_path . '/includes/footer.php';
        if (file_exists($footer_file)) {
            include_once $footer_file;
        }
    }
}
