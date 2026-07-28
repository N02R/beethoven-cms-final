<?php
declare(strict_types=1);

namespace App\Controllers\Guide;

class GuideBlog2Controller {
    
    public function index(string $lang = 'de'): void {
        // تأمين إذن الوصول
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
        
        $guide_blog2_data = $global_data['guide_blog2_page'] ?? [
            'hero_img'           => 'assets/img/guide/image (1).jpg',
            'hero_position'      => 'center center',
            'main_title'         => 'التعليم والعمل في ألمانيا: فرص جديدة لحياة أفضل',
            'main_desc'          => 'تعتبر ألمانيا واحدة من أفضل الوجهات عالميًا للراغبين في إكمال تعليمهم أو بدء مسيرتهم المهنية، بفضل جودة التعليم المجاني، وتوفّر فرص التدريب المهني، وسوق العمل المستقر الذي يرحب بالكفاءات من جميع أنحاء العالم. في هذه المدونة، سنرشدك إلى كيفية الاستفادة من فرص التعليم والعمل في ألمانيا، والخطوات العملية للبدء، مع نصائح عملية تسهل رحلتك نحو حياة مستقرة وآمنة في أوروبا.',
            'why_title'          => 'لماذا التعليم والعمل في ألمانيا؟',
            'why_subtitle'       => 'إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي',
            'services_title'     => 'ماذا تقدم لك بيتهوفن سيتي للخدمات الطلابية؟',
            'service_1'          => 'تقديم استشارات فردية مصممة وفق احتياجاتك.',
            'service_2'          => 'مساعدتك في إعداد السيرة الذاتية ورسائل التحفيز باللغة الألمانية.',
            'service_3'          => 'التقديم على برامج التدريب المهني (Ausbildung) المناسبة لك',
            'service_4'          => 'التقديم على برامج الإقامة الطبية وخريجي الصحة',
            'service_5'          => 'دعمك في إعداد الوثائق، تعلم اللغة، والحصول على السكن في ألمانيا.',
            'tips_title'         => 'كيف نضمن لك تجربة سلسة وآمنة؟',
            'tip_1_bold'         => 'سهولة الوصول للمعلومات: ',
            'tip_1_text'         => 'جميع خطوات التقديم واضحة وستعرف ماذا عليك أن تفعل في كل مرحلة.',
            'tip_2_bold'         => 'التواصل المستمر: ',
            'tip_2_text'         => 'فريقنا معك للإجابة على استفساراتك عبر الواتساب والبريد الإلكتروني.',
            'tip_3_bold'         => 'أسعار تنافسية وشفافية: ',
            'tip_3_text'         => 'خدماتنا بأسعار مناسبة، ونشرح لك كل رسوم المعاهد والخدمات مقدمًا.'
        ];

        $data = $global_data;
        $data['guide_blog2_page'] = $guide_blog2_data;

        $is_admin = !empty($_SESSION['is_admin']) || !empty($_SESSION['is_logged_in']) || (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $path_prefix = '/';

        // ملفات الـ CSS الخاصة بالصفحة
        $page_css = [
            '/assets/css/education.css',
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
        $view_file = __DIR__ . '/../../Views/guide/guide-blog2.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Guide Blog 2 View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/includes/admin_guide_blog2_modals.php';
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
