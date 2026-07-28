<?php
declare(strict_types=1);

namespace App\Controllers\Services;

class ServiceCostController {
    
    public function index(string $lang = 'de'): void {
        // تعريف الثابت الأمني لمنع خطأ Access Denied
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }

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
        
        $pricelist_data = $global_data['pricelist_page'] ?? [
            'page_breadcrumb'     => 'قائمة أسعار الخدمات',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg15.png',
            'hero_position'       => 'center center',
            'main_title'          => 'قائمة الأسعار العامة',
            'main_desc'           => 'يسعى فريق عمل بيتهوفن سيتي جاهدين لتوفير خدمة عالية الجودة وبتكلفة معقولة وتنافسية للطلبة والمتدربين الأجانب الذين يبحثون عن فرص التعليم العالي والتدريب في ألمانيا. يوضح الجدول أدناه بعض الخدمات التي نسعى لتقديمها مع التكلفة التقديرية لكل خِدمة.',
            'download_item'       => [
                'type'  => 'pdf',
                'title' => 'قائمة الأسعار العامة',
                'file'  => 'assets/files/general_price_list.pdf'
            ]
        ];

        $data = $global_data;
        $data['pricelist_page'] = $pricelist_data;

        $is_admin = !empty($_SESSION['is_admin']) || (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && !empty($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $path_prefix = '/';

        // ملفات الـ CSS الخاصة بالخدمة
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
        $view_file = __DIR__ . '/../../Views/edu-services/services-cost.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Service Cost View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/includes/admin_pricelist_modals.php';
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
