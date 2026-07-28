<?php
declare(strict_types=1);

namespace App\Controllers\Guide;

class GuideBlog1Controller {
    
    public function index(string $lang = 'de'): void {
        // تعريف الثابت الأمني لمنع خطأ الوصول
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
        
        $guide_data = $global_data['guide_blog1_page'] ?? [
            'hero_img'           => 'assets/img/home/image(0).jpg',
            'hero_position'      => 'center center',
            'main_title'         => 'تعتبر ألمانيا من أفضل الوجهات المفضلة للدراسة لكثير من الطلبة الأجانب',
            'main_desc'          => 'في الوقت الراهن، من بين 2.7 مليون طالب يدرسون في الجامعات الألمانية هنالك أكثر من 380 ألف طالب أجنبي -من بينهم الكثير من الطلبه العرب-. وبالإعتماد على أحدث التقارير، فإن هذا العدد يزداد بإستمرار سنوياً.',
            'notes_title'        => 'ملاحظات هامة جداً',
            'note_1_bold'        => 'لدى الجامعات الألمانية مواعيد مختلفة لتقديم طلبات التسجيل، ويوجد غالباً مواعيد لفصول الشتاء و الصيف:',
            'note_winter'        => 'تبدأ عملية التقديم في مارس، يكون الموعد النهائي للتقديم هو 15 يوليو ويبدأ الفصل الدراسي في أكتوبر.',
            'note_summer'        => 'تبدأ عملية التقديم في سبتمبر، يكون الموعد النهائي للتقديم هو 15 يناير ويبدأ الفصل الدراسي في مارس / أبريل',
            'note_2_text'        => 'الأخذ بعين الإعتبار أن عملية التسجيل لدى الجامعة و تجهيز الوثائق اللازمة للتأشيرة الدخول إلى ألمانيا تستغرق من شهرين على الأقل لغاية أكثر من أربعة أشهر بحسب الحالة، لذلك ننصح بشدة البدء باكراً بإجراءات التسجيل.',
            'note_3_title'       => 'للإجابة على أكثر الأسئلة التي يطرحُها أغلب الطلاب، نقول:',
            'faq_1'              => '1. الأوراق المطلوبة للحصول على قبول جامعي يَعتمِد كثيراً على الدرجة الجامِعية التي تريد أن تَحصل عليها و كذلك المستوى العلمي الذي حصلت عليه سابقاً، مثلاً لِدرجة البكالوريوس تحتاج إلى: شهادة ثانوية عامة مُصدقة من السفارة الألمانية، سيرة ذاتية، رسالة الدافع/التحفيز، صورة عن جواز السفر، شهادة إتمام سنة تحضيرية أو شهادة لغة ألمانية تُؤهلك لدخول الجامعة، أو أحياناً إثبات إتمام سنة جامعية في بلدك و شهادة لغة ألمانية تُؤهلك لدخول الجامعة معاً، تأمين صحي. أما لِدرجة الماجستير فتحتاج إلى درجة بكالوريوس مُعترف بها في ألمانيا مُصدقة من السفارة، شهادة لغة ألمانية تُؤهلك لدخول الجامعة أو شهادة لغة إنجليزية مُعترف بها إذا أردت الدراسة باللغة الإنجليزية، سيرة ذاتية، رسالة الدافع/التحفيز، صورة عن جواز السفر، تأمين صحي.',
            'faq_2_prefix'       => '2. لمعرفة',
            'faq_2_link_text'    => 'متطلبات تأشيرة الدراسة',
            'faq_2_url'          => 'contact',
            'faq_2_suffix'       => 'لدى السفارة أو القنصلية الألمانية، أيضاً قمنا بجمع معلومات قيِّمة تجدونها في أسفل الصفحة. علماً بأن هذه المُتطلبات تختلف بحسب نوع التأشيرة و الدولة.'
        ];

        $data = $global_data;
        $data['guide_blog1_page'] = $guide_data;

        $is_admin = !empty($_SESSION['is_admin']) || !empty($_SESSION['is_logged_in']) || (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin');
        $path_prefix = '/';

        // ملفات الـ CSS الخاصة بالصفحة
        $page_css = [
            '/assets/css/education.css',
            '/edu-services/css/edu-services.css',
            '/assets/css/responsive-education.css'
        ]; 

        $page_js = [];
        $custom_script = '';

        // 1. استدعاء الهيدر المشترك
        $header_file = $root_path . '/includes/header.php';
        if (file_exists($header_file)) {
            include_once $header_file;
        }

        // 2. استدعاء الـ View الخاص بالصفحة
        $view_file = __DIR__ . '/../../Views/guide/guide-blog1.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "<div class='container py-5 text-center'><h3>Guide Blog View file not found.</h3></div>";
        }

        // 3. استدعاء مودالات الأدمن إن وجدت
        $admin_modals = $root_path . '/includes/admin_guide_blog1_modals.php';
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
