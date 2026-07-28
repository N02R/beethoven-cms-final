<?php
declare(strict_types=1);

namespace App\Controllers\Services;

class GermanLangController {
    
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
        
        $german_data = $global_data['germanlang_page'] ?? [
            'page_breadcrumb'   => 'دورات اللغة الألمانية',
            'page_breadcrumb_url' => '#',
            'hero_img'          => 'assets/img/education/servicesimg4.png',
            'hero_position'     => 'center center',
            'main_title'        => 'دورات اللغة الألمانية باحتراف تدعم خطتك الأكاديمية والمهنية',
            'main_desc'         => 'اتقن اللغة الألمانية من البداية حتى التفوق مع دوراتنا المعتمدة حسب المستويات الرسمية (A1-C2), تُعدك للدراسة, العمل, التقديم للسفارة, او الحياة اليومية في ألمانيا.',
            'levels_section'    => [
                'title' => 'المستويات المتوفرة (طبقًا ل CEFR)',
                'levels_list' => [
                    'المستوى A1: للمبتدئين.',
                    'المستوى A2: المعرفة الأساسية في اللغة.',
                    'المستوى B1: قبل المتوسط.',
                    'المستوى B2: معرفة متوسطة في اللغة.',
                    'المستوى C1: المستوى العلوي.',
                    'المستوى C2: مُتقدم.'
                ]
            ],
            'features_section'  => [
                'title' => 'مميزات دوراتنا',
                'features_list' => [
                    'معتمدون من CEFR.',
                    'مدرسون ناطقون أصليون.',
                    'شهادات مقبولة للسفارات والجامعات.',
                    'دعم في التقدم للإمتحانات (DSH, TestDaf).'
                ]
            ],
            'tips_section'      => [
                'title' => 'نصائح للنجاح في الدراسة بالألمانية',
                'tips_list' => [
                    'حضّر لامتحانات اللغة مبكرًا.',
                    'مارس مهارات الاستماع والمحادثة يوميًا.',
                    'راقب تقدمك من خلال اختبارات دورية.',
                    'استخدم موارد مساعدة مثل كتب ووسائط صوت.'
                ]
            ]
        ];

        $data = $global_data;
        $data['germanlang_page'] = $german_data;

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
        $view_file = __DIR__ . '/../../Views/edu-services/germanlang.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>German Lang View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/includes/admin_german_modals.php';
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
