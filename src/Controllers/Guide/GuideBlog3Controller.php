<?php
declare(strict_types=1);

namespace App\Controllers\Guide;

class GuideBlog3Controller {
    
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
        
        $guide_blog3_data = $global_data['guide_blog3_page'] ?? [
            'hero_img'           => 'assets/img/guide/image.jpg',
            'hero_position'      => 'center center',
            'main_title'         => 'معالجة طلبات العملاء في بيتهوفن سيتي للخدمات (BCS)',
            'main_desc'          => 'في عالم الهجرة والدراسة والعمل في ألمانيا، لا يكفي تقديم معلومات عامة، بل تحتاج إلى من يرافقك خطوة بخطوة حتى تصل إلى هدفك بثقة وسلاسة. في بيتهوفن سيتي للخدمات (BCS)، نعالج طلبات العملاء بأسلوب احترافي ومنظّم، يضمن لك متابعة طلبك في كل مرحلة، وتسهيل رحلتك من لحظة تواصلك معنا وحتى وصولك إلى ألمانيا.',
            'diff_title'         => 'الذي يجعل معالجة طلبات العملاء لدينا مختلفة',
            'diff_1_bold'        => 'وضوح الخطوات: ',
            'diff_1_text'        => 'نخبرك دائمًا بما يلي بعد كل مرحلة.',
            'diff_2_bold'        => 'التواصل المستمر: ',
            'diff_2_text'        => 'فريقنا معك للإجابة على استفساراتك عبر الواتساب والبريد الإلكتروني.',
            'diff_3_bold'        => 'أسعار تنافسية وشفافية: ',
            'diff_3_text'        => 'خدماتنا بأسعار مناسبة، ونشرح لك كل رسوم المعاهد والخدمات مقدمًا.',
            'diff_4_bold'        => 'خبرة: ',
            'diff_4_text'        => 'نعرف متطلبات المعاهد الألمانية والقنصليات جيدًا',
            'timeline_title'     => 'مراحل معالجة طلبك لدينا',
            'timeline_subtitle'  => 'في بيتهوفن سيتي للخدمات، نقسم معالجة طلبك إلى خطوات واضحة:',
            'contact_title'      => 'جاهز لبدء رحلتك?',
            'contact_text_prefix'=> 'احجز استشارتك المجانية الآن مع فريق BCS وابدأ خطواتك نحو ألمانيا بثقة: ',
            'contact_link_text'  => 'تواصل معنا',
            'contact_url'        => 'contact'
        ];

        $data = $global_data;
        $data['guide_blog3_page'] = $guide_blog3_data;

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
        $view_file = __DIR__ . '/../../Views/guide/guide-blog3.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Guide Blog 3 View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/includes/admin_guide_blog3_modals.php';
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
