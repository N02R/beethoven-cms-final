<?php
declare(strict_types=1);

namespace App\Controllers\Services;

class MedicalPackageController {
    
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
        
        $medical_data = $global_data['medical_package_page'] ?? [
            'page_breadcrumb'     => 'باقة التدريب الطبي',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/job/servicesimg3.png',
            'hero_position'       => 'center center',
            'main_title'          => 'عرض وإتفاقية التدريب المهني',
            'main_desc'           => 'كل عرضٍ (حالة) له تكلفة الخدمة الخاصة به حيث أن كل عرض يتضمن خدمات مختلفة وبذلك يتطلب إجراءات ومراسلات وجهود مختلفة. للحصول على فكرةٍ عامة عن العرض الخاص بك وتكلفة الخدمات الخاصة به، تجد أدناه العروض الأكثر طلباً (مثال لكل عرض).',
            'note_text'           => 'جميع العروض والاتفاقيات تكتب وتملأ باللغة الإنجليزية، للإستفسار عن أي بند أو شرح أي معلومات، لا تتردد بالتواصل معنا.',
            'download_item'       => [
                'type'  => 'pdf',
                'title' => 'عرض واتفاقية التدريب الطبي',
                'sub'   => 'Example',
                'file'  => 'assets/files/medical_training_agreement.pdf'
            ]
        ];

        $data = $global_data;
        $data['medical_package_page'] = $medical_data;

        $is_admin = !empty($_SESSION['is_admin']) || !empty($_SESSION['is_logged_in']) || (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin');
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
        $view_file = __DIR__ . '/../../Views/job-services/medical-pakeges.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Medical Packages View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/includes/admin_medical_modals.php';
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
