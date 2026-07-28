<?php
declare(strict_types=1);

namespace App\Controllers;

class GuideController {
    
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

        $root_path = realpath(__DIR__ . '/../../');
        $config_file = $root_path . '/announcement_config.json';
        $global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];
        
        $data = $global_data;
        $is_admin = !empty($_SESSION['is_admin']);
        $path_prefix = '/';

        // تعريف ملفات الـ CSS الخاصة بصفحة الدليل
        $page_css = [
            '/assets/css/style.css'
        ]; 

        $page_js = [];
        $custom_script = '';

        // 1. استدعاء الهيدر المشترك
        $header_file = $root_path . '/includes/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        // 2. استدعاء الـ View الخاص بـ Guide
        $view_file = __DIR__ . '/../Views/guide.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Guide View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن الخاصة بالدليل إن وجدت
        $admin_modals = $root_path . '/includes/admin_guide_modals.php';
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
