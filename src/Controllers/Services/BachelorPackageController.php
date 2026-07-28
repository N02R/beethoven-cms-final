<?php
declare(strict_types=1);

namespace App\Controllers\Services;

class BachelorPackageController {
    
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
        
        $bachelor_data = $global_data['bachelor_page'] ?? [
            'page_breadcrumb'     => 'BCS Bachelor Package',
            'page_breadcrumb_url' => '#',
            'main_title'          => 'BCS Bachelor Package and Agreement Templet',
            'main_desc'           => 'هذا المستند محمي بكلمة مرور. يرجى <span style="color: #66aeee;">الاتصال بنا</span> للحصول على كلمة المرور<br>هذا المحتوى محمي بكلمة مرور. لإظهار المحتوى يتعين عليك كتابة كلمة المرور في الأدنى:',
            'password_label'      => 'كلمة المرور:',
            'btn_text'            => 'ادخال'
        ];

        $data = $global_data;
        $data['bachelor_page'] = $bachelor_data;

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
        $view_file = __DIR__ . '/../../Views/edu-services/bachelor-package.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Bachelor Package View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/includes/admin_bachelor_modals.php';
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
