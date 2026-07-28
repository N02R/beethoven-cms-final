<?php
declare(strict_types=1);

namespace App\Controllers\Services;

class MotivationLetterController {
    
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
        
        $motivation_data = $global_data['motivation_page'] ?? [
            'page_breadcrumb'     => 'خطاب الدافع / التحفيز',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg3.png',
            'hero_position'       => 'center center',
            'main_title'          => 'خطاب دافع احترافي يعزز طلبك الأكاديمي أو المهني',
            'main_desc'           => "خطاب الدافع/التحفيز هي وثيقة من صفحة واحدة كحد أقصى. تكتُب فيها عن نفسك وتُظهر إهتمامك بالطلب الذي تتقدم إليه و الهدف الذي تريد تحقيقه مثل: (دورة لغة ألمانية، سنة تحضيرية بهدف دخول الجامعة، درجة البكالوريوس أو الماجستير، التدريب أو الزمالة الطبية، إلخ).\nإضافة الى ذلك، يتركز الأمر أكثر على دراستك المستقبلية وخططك المهنية وكيف أن درجة البكالوريوس مثلا التي تتقدم إليها ستساعدك على تحقيق أهدافك المستقبلية. أيضا يمكنك أن تشرح بها الأسباب التي تجعل منك المرشح المثالي لهذا المنصب.",
            'advice_section'      => [
                'title' => 'نصائح سريعة لكتابة خطاب الدافع',
                'items' => [
                    'ابدأ بمقدمة تلخّص دوافعك',
                    'اذكر أمثلة ملموسة (دراسة، تدريب، تجربة)',
                    'اربط خبراتك بأهدافك القادمة',
                    'استخدم لغة واضحة وشخصية',
                    'احصل على مراجعة من مختص أو ناطق أصلي.',
                    'راجع الأخطاء اللغوية جيدًا.'
                ]
            ],
            'download_items'      => [
                [
                    'type'  => 'pdf',
                    'title' => 'خطاب الدافع / التحفيز',
                    'sub'   => 'Example (PDF)',
                    'file'  => 'assets/files/motivation_letter.pdf'
                ],
                [
                    'type'  => 'word',
                    'title' => 'خطاب الدافع / التحفيز',
                    'sub'   => 'Example (Word)',
                    'file'  => 'assets/files/motivation_letter.docx'
                ]
            ]
        ];

        $data = $global_data;
        $data['motivation_page'] = $motivation_data;

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
        $view_file = __DIR__ . '/../../Views/edu-services/motivitionletter.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Motivation Letter View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/includes/admin_motivation_modals.php';
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
