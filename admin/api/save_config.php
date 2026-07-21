<?php
/**
 * save_config.php - ملف إدارة وتحديث إعدادات الموقع كاملة (Home, About, Education, Job, Contact)
 */
session_start();
header('Content-Type: application/json; charset=UTF-8');

// 1. التحقق من الصلاحيات
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized Access']));
}

$file = __DIR__ . '/../../announcement_config.json';
$upload_path = __DIR__ . '/../../assets/img/';

// 2. قراءة البيانات أو تهيئة مصفوفة افتراضية شاملة لكل الموقع
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [
    // إعدادات عامة
    'announcement'           => [],
    'menu_links'             => [],
    'social_links'           => [],
    'languages'              => [],
    'site_logo_path'         => 'assets/img/logo.png',
    
    // الصفحة الرئيسية (Home)
    'hero'                   => [],
    'services'               => [],
    'services_section_title' => 'خدماتنا المميزة',
    'services_section_desc'  => '',
    'choose_items'           => [],
    'choose_title'           => 'ما الذي يميز بيتهوفن سيتي',
    'choose_section_desc'    => '',
    'reviews_items'          => [],
    'reviews_title'          => 'شاهد ماذا يقول عملاؤنا عنا',
    'guide_items'            => [],
    'guide_title'            => 'دليل بيتهوفن الشامل',
    'guide_desc'             => '',
    'faq_items'              => [],
    'faq_title'              => 'الأسئلة الشائعة',
    'consult_title'          => 'احصل على استشارة مجانية',
    'consult_desc'           => '',
    'footer_desc'            => '',
    'footer_col2_title'      => 'روابط سريعة',
    'footer_col2_links'      => [],
    'footer_col3_title'      => 'تواصل معنا',
    'footer_col3_links'      => [],

    // صفحة عن الشركة (about.php)
    'about'                  => [
        'title'        => 'من نحن',
        'desc'         => '',
        'btn_text'     => 'قراءة المزيد',
        'btn_url'      => '#',
        'main_img'     => 'assets/img/about us icon, image/about1.jpg',
        'sub_img'      => 'assets/img/about us icon, image/about2.png',
        'vision_title' => 'رؤية الشركة',
        'vision_desc'  => '',
        'vision_icon'  => 'assets/img/About us Icon, image/Company vision.svg',
        'message_title'=> 'رسالة الشركة',
        'message_desc' => '',
        'message_icon' => 'assets/img/About us Icon, image/Company message.svg'
    ],
    'team_title'             => 'فريق العمل',
    'team_desc'              => '',
    'team_members'           => [],
    'about_counts'           => [],
    'partners_title'         => 'شركاؤنا داخل وخارج ألمانيا',
    'partners_items'         => [],

    // صفحة التعليم العالي (education.php)
    'edu_hero'               => [
        'title'    => '',
        'desc'     => '',
        'btn_text' => 'ابدأ الآن',
        'btn_url'  => '#',
        'img'      => 'assets/img/education/hero.jpg'
    ],
    'edu_why_title'          => 'لماذا الدراسة في ألمانيا؟',
    'edu_why_desc'           => '',
    'edu_why_items'          => [],
    'edu_timeline_title'     => 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS',
    'edu_timeline_desc'      => '',
    'edu_timeline_steps'     => [],
    'edu_services_title'     => 'ماذا تقدم في بيتهوفن سيتي؟',
    'edu_services_desc'      => '',
    'edu_services_items'     => [],

    // صفحة التدريب المهني (job.php)
    'job_hero'               => [
        'title'    => '',
        'desc'     => '',
        'btn_text' => 'ابدأ الآن',
        'btn_url'  => '#',
        'img'      => 'assets/img/job/hero.jpg'
    ],
    'job_why_title'          => 'لماذا التدريب معنا؟',
    'job_why_desc'           => '',
    'job_why_items'          => [],
    'job_program_title'      => 'أنواع التدريب المهني',
    'job_program_desc'       => '',
    'job_program_types'      => [],
    'job_timeline_title'     => 'كيف نساعدك خطوة بخطوة؟',
    'job_timeline_desc'      => '',
    'job_timeline_steps'     => [],
    'job_services_title'     => 'ماذا تقدم في بيتهوفن سيتي؟',
    'job_services_desc'      => '',
    'job_services_items'     => [],

    // صفحة اتصل بنا (contact.php)
    'contact_hero_img'       => 'assets/img/contact us/contacthero.png',
    'contact_address'        => 'Rheinweg 140 ,53129 Bonn,Germany',
    'contact_address_icon'   => 'assets/img/Location.svg',
    'contact_email'          => 'info@Beethoven-City-Services.com',
    'contact_email_icon'     => 'assets/img/Mail.svg',
    'contact_phone'          => '666-230-71 176 (0) 49+',
    'contact_phone_icon'     => 'assets/img/Call.svg',
    'whatsapp_text'          => 'نحن في Beethoven City نؤمن أن التواصل المباشر هو الأفضل.. لذلك نوفر لك قنوات تواصل واضحة وآمنة بدون أي نماذج أو جمع بيانات',
    'whatsapp_url'           => 'https://wa.me/4917671230666',
    'whatsapp_btn_txt'       => 'تواصل معنا عبر واتساب'
    ,
        // --- صفحة الاستقبال والخدمات الداخلية (arrival.php) ---
    'arrival_page'           => [
        'hero_img'     => 'assets/img/education/servicesimg9.png',
        'main_title'   => '',
        'main_desc'    => '',
        'advice_title' => '',
        'advice_desc'  => '',
        'tips'         => [],
        'notes'        => []
    ],

];

// دالة رفع الملفات الآمنة
function handle_upload($file_key, $upload_dir) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        $file_name = $_FILES[$file_key]['name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_ext)) {
            $new_name = $file_key . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $new_name)) {
                return 'assets/img/' . $new_name;
            }
        }
    }
    return null;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    // --- إعدادات عامة ---
    case 'update_announcement':
        $img_path = handle_upload('ad_image', $upload_path);
        $data['announcement'] = [
            'status'            => $_POST['status'] ?? 'Draft',
            'type'              => $_POST['type'] ?? 'text',
            'announcement_text' => $_POST['announcement_text'] ?? '',
            'link'              => $_POST['link'] ?? '',
            'start_date'        => $_POST['start_date'] ?? '',
            'end_date'          => $_POST['end_date'] ?? '',
            'bg_color'          => $_POST['bg_color'] ?? '#f1f5f9',
            'text_color'        => $_POST['text_color'] ?? '#1e293b',
            'font_size'         => $_POST['font_size'] ?? '16',
            'image_path'        => $img_path ?? ($data['announcement']['image_path'] ?? '')
        ];
        break;

    case 'update_logo':
        $img_path = handle_upload('logo_img', $upload_path);
        if ($img_path) $data['site_logo_path'] = $img_path;
        break;

    // --- صفحة Home ---
    case 'update_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        $data['hero'] = [
            'title'    => $_POST['hero_title'] ?? '',
            'desc'     => $_POST['hero_desc'] ?? '',
            'btn_text' => $_POST['hero_btn_text'] ?? '',
            'btn_url'  => $_POST['hero_btn_url'] ?? '',
            'img'      => $img_path ?? ($_POST['old_hero_img'] ?? 'assets/img/hero-bg.jpg')
        ];
        break;

    case 'update_services':
        $data['services_section_title'] = $_POST['services_title'] ?? '';
        $data['services_section_desc'] = $_POST['services_desc'] ?? ''; 
        $new_services = [];
        if (isset($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $index => $s) {
                $img_path = handle_upload('service_img_' . $index, $upload_path);
                $new_services[] = [
                    'title' => $s['title'] ?? '',
                    'url'   => $s['url'] ?? '#',
                    'img'   => $img_path ?? ($s['old_img'] ?? '')
                ];
            }
        }
        $data['services'] = $new_services;
        break;

    case 'update_choose':
        $data['choose_title'] = $_POST['choose_title'] ?? '';
        $data['choose_section_desc'] = $_POST['choose_desc'] ?? '';
        $choose_items = [];
        if (isset($_POST['choose']) && is_array($_POST['choose'])) {
            foreach ($_POST['choose'] as $index => $c) {
                $img_path = handle_upload('choose_img_' . $index, $upload_path);
                $choose_items[] = [
                    'title' => $c['title'] ?? '',
                    'desc'  => $c['desc'] ?? '',
                    'img'   => $img_path ?? ($c['old_img'] ?? '')
                ];
            }
        }
        $data['choose_items'] = $choose_items;
        break;

    case 'update_reviews':
        $data['reviews_title'] = $_POST['reviews_title'] ?? '';
        $reviews = [];
        if (isset($_POST['reviews']) && is_array($_POST['reviews'])) {
            foreach ($_POST['reviews'] as $r) {
                if (!empty($r['url'])) $reviews[] = ['url' => $r['url']];
            }
        }
        $data['reviews_items'] = $reviews;
        break;

    case 'update_guide':
        $data['guide_title'] = $_POST['guide_title'] ?? '';
        $data['guide_desc'] = $_POST['guide_desc'] ?? '';
        $new_guides = [];
        if (isset($_POST['guide']) && is_array($_POST['guide'])) {
            foreach ($_POST['guide'] as $index => $g) {
                $img_path = handle_upload('guide_img_' . $index, $upload_path);
                $new_guides[] = [
                    'title' => $g['title'] ?? '',
                    'desc'  => $g['desc'] ?? '',
                    'url'   => $g['url'] ?? '#',
                    'img'   => $img_path ?? ($g['old_img'] ?? '')
                ];
            }
        }
        $data['guide_items'] = $new_guides;
        break;

    case 'update_faq':
        $data['faq_title'] = $_POST['faq_title'] ?? '';
        $new_faqs = [];
        if (isset($_POST['faq']) && is_array($_POST['faq'])) {
            foreach ($_POST['faq'] as $f) {
                $new_faqs[] = ['question' => $f['question'] ?? '', 'answer' => $f['answer'] ?? ''];
            }
        }
        $data['faq_items'] = $new_faqs;
        break;

    case 'update_footer':
        $data['consult_title'] = $_POST['consult_title'] ?? '';
        $data['consult_desc']  = $_POST['consult_desc'] ?? '';
        $data['footer_desc'] = $_POST['footer_desc'] ?? '';
        $data['footer_col2_title'] = $_POST['footer_col2_title'] ?? '';
        $data['footer_col3_title'] = $_POST['footer_col3_title'] ?? '';
        $data['footer_col3_links'] = [];
        if (isset($_POST['col3']) && is_array($_POST['col3'])) {
            foreach ($_POST['col3'] as $i => $item) {
                $img_path = handle_upload('col3_img_' . $i, $upload_path);
                $data['footer_col3_links'][] = [
                    'title' => $item['title'] ?? '',
                    'url'   => $item['url'] ?? '#',
                    'img'   => $img_path ?? ($item['old_img'] ?? '')
                ];
            }
        }
        break;

    // --- صفحة About ---
    case 'update_about_section':
        $main_img = handle_upload('about_main_img', $upload_path);
        $sub_img  = handle_upload('about_sub_img', $upload_path);
        $vision_icon = handle_upload('about_vision_icon', $upload_path);
        $message_icon = handle_upload('about_message_icon', $upload_path);

        $data['about'] = [
            'title'        => $_POST['about_title'] ?? '',
            'desc'         => $_POST['about_desc'] ?? '',
            'btn_text'     => $_POST['about_btn_text'] ?? '',
            'btn_url'      => $_POST['about_btn_url'] ?? '#',
            'main_img'     => $main_img ?? ($_POST['old_about_main_img'] ?? ''),
            'sub_img'      => $sub_img ?? ($_POST['old_about_sub_img'] ?? ''),
            'vision_title' => $_POST['vision_title'] ?? '',
            'vision_desc'  => $_POST['vision_desc'] ?? '',
            'vision_icon'  => $vision_icon ?? ($_POST['old_vision_icon'] ?? ''),
            'message_title'=> $_POST['message_title'] ?? '',
            'message_desc' => $_POST['message_desc'] ?? '',
            'message_icon' => $message_icon ?? ($_POST['old_message_icon'] ?? '')
        ];
        break;

    case 'update_about_team':
        $data['team_title'] = $_POST['team_title'] ?? '';
        $data['team_desc']  = $_POST['team_desc'] ?? '';
        $new_team = [];
        if (isset($_POST['team']) && is_array($_POST['team'])) {
            foreach ($_POST['team'] as $index => $member) {
                $img_path = handle_upload('team_img_' . $index, $upload_path);
                $new_team[] = [
                    'name' => $member['name'] ?? '',
                    'role' => $member['role'] ?? '',
                    'img'  => $img_path ?? ($member['old_img'] ?? '')
                ];
            }
        }
        $data['team_members'] = $new_team;
        break;

    case 'update_about_counts':
        $new_counts = [];
        if (isset($_POST['counts']) && is_array($_POST['counts'])) {
            foreach ($_POST['counts'] as $index => $c) {
                $img_path = handle_upload('count_img_' . $index, $upload_path);
                $new_counts[] = [
                    'number' => $c['number'] ?? '',
                    'title'  => $c['title'] ?? '',
                    'img'    => $img_path ?? ($c['old_img'] ?? '')
                ];
            }
        }
        $data['about_counts'] = $new_counts;
        break;

    case 'update_about_partners':
        $data['partners_title'] = $_POST['partners_title'] ?? '';
        $new_partners = [];
        if (isset($_POST['partners']) && is_array($_POST['partners'])) {
            foreach ($_POST['partners'] as $index => $p) {
                $img_path = handle_upload('partner_img_' . $index, $upload_path);
                $new_partners[] = ['img' => $img_path ?? ($p['old_img'] ?? '')];
            }
        }
        $data['partners_items'] = $new_partners;
        break;

    // --- صفحة Education ---
    case 'update_edu_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        $data['edu_hero'] = [
            'title'    => $_POST['title'] ?? '',
            'desc'     => $_POST['desc'] ?? '',
            'btn_text' => $_POST['btn_text'] ?? '',
            'btn_url'  => $_POST['btn_url'] ?? '#',
            'img'      => $img_path ?? ($_POST['old_img'] ?? '')
        ];
        break;

    case 'update_edu_why':
        $data['edu_why_title'] = $_POST['why_title'] ?? '';
        $data['edu_why_desc']  = $_POST['why_desc'] ?? '';
        $new_why = [];
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $index => $item) {
                $img_path = handle_upload('why_img_' . $index, $upload_path);
                $new_why[] = ['title' => $item['title'] ?? '', 'desc' => $item['desc'] ?? '', 'img' => $img_path ?? ($item['old_img'] ?? '')];
            }
        }
        $data['edu_why_items'] = $new_why;
        break;

    case 'update_edu_timeline':
        $data['edu_timeline_title'] = $_POST['timeline_title'] ?? '';
        $data['edu_timeline_desc']  = $_POST['timeline_desc'] ?? '';
        $new_steps = [];
        if (isset($_POST['steps']) && is_array($_POST['steps'])) {
            foreach ($_POST['steps'] as $index => $step) {
                $icon_path = handle_upload('step_icon_' . $index, $upload_path);
                $new_steps[] = [
                    'title'    => $step['title'] ?? '',
                    'subtitle' => $step['subtitle'] ?? '',
                    'desc'     => $step['desc'] ?? '',
                    'order'    => (int)($step['order'] ?? $index),
                    'icon'     => $icon_path ?? ($step['old_icon'] ?? '')
                ];
            }
        }
        usort($new_steps, fn($a, $b) => $a['order'] <=> $b['order']);
        $data['edu_timeline_steps'] = $new_steps;
        break;

    case 'update_edu_services':
        $data['edu_services_title'] = $_POST['services_title'] ?? '';
        $data['edu_services_desc']  = $_POST['services_desc'] ?? '';
        $new_edu_srv = [];
        if (isset($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $index => $srv) {
                $img_path = handle_upload('srv_img_' . $index, $upload_path);
                $new_edu_srv[] = ['title' => $srv['title'] ?? '', 'url' => $srv['url'] ?? '#', 'img' => $img_path ?? ($srv['old_img'] ?? '')];
            }
        }
        $data['edu_services_items'] = $new_edu_srv;
        break;

    // --- صفحة Job ---
    case 'update_job_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        $data['job_hero'] = [
            'title'    => $_POST['title'] ?? '',
            'desc'     => $_POST['desc'] ?? '',
            'btn_text' => $_POST['btn_text'] ?? '',
            'btn_url'  => $_POST['btn_url'] ?? '#',
            'img'      => $img_path ?? ($_POST['old_img'] ?? '')
        ];
        break;

    case 'update_job_why':
        $data['job_why_title'] = $_POST['why_title'] ?? '';
        $data['job_why_desc']  = $_POST['why_desc'] ?? '';
        $new_job_why = [];
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $index => $item) {
                $img_path = handle_upload('why_img_' . $index, $upload_path);
                $new_job_why[] = ['title' => $item['title'] ?? '', 'desc' => $item['desc'] ?? '', 'img' => $img_path ?? ($item['old_img'] ?? '')];
            }
        }
        $data['job_why_items'] = $new_job_why;
        break;

    case 'update_job_program':
        $data['job_program_title'] = $_POST['program_title'] ?? '';
        $data['job_program_desc']  = $_POST['program_desc'] ?? '';
        $new_programs = [];
        if (isset($_POST['programs']) && is_array($_POST['programs'])) {
            foreach ($_POST['programs'] as $index => $prog) {
                $img_path = handle_upload('prog_img_' . $index, $upload_path);
                $new_programs[] = [
                    'title'    => $prog['title'] ?? '',
                    'desc'     => $prog['desc'] ?? '',
                    'btn_text' => $prog['btn_text'] ?? '',
                    'btn_url'  => $prog['btn_url'] ?? '#',
                    'img'      => $img_path ?? ($prog['old_img'] ?? ''),
                    'is_dark'  => isset($prog['is_dark']) && $prog['is_dark'] == '1'
                ];
            }
        }
        $data['job_program_types'] = $new_programs;
        break;

    case 'update_job_timeline':
        $data['job_timeline_title'] = $_POST['timeline_title'] ?? '';
        $data['job_timeline_desc']  = $_POST['timeline_desc'] ?? '';
        $new_job_steps = [];
        if (isset($_POST['steps']) && is_array($_POST['steps'])) {
            foreach ($_POST['steps'] as $index => $step) {
                $icon_path = handle_upload('step_icon_' . $index, $upload_path);
                $new_job_steps[] = [
                    'title'    => $step['title'] ?? '',
                    'subtitle' => $step['subtitle'] ?? '',
                    'desc'     => $step['desc'] ?? '',
                    'order'    => (int)($step['order'] ?? $index),
                    'icon'     => $icon_path ?? ($step['old_icon'] ?? '')
                ];
            }
        }
        usort($new_job_steps, fn($a, $b) => $a['order'] <=> $b['order']);
        $data['job_timeline_steps'] = $new_job_steps;
        break;

    case 'update_job_services':
        $data['job_services_title'] = $_POST['services_title'] ?? '';
        $data['job_services_desc']  = $_POST['services_desc'] ?? '';
        $new_job_srv = [];
        if (isset($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $index => $srv) {
                $img_path = handle_upload('srv_img_' . $index, $upload_path);
                $new_job_srv[] = ['title' => $srv['title'] ?? '', 'url' => $srv['url'] ?? '#', 'img' => $img_path ?? ($srv['old_img'] ?? '')];
            }
        }
        $data['job_services_items'] = $new_job_srv;
        break;

    // --- صفحة Contact ---
    case 'update_contact_hero':
        $img_path = handle_upload('contact_hero_img', $upload_path);
        $data['contact_hero_img'] = $img_path ?: ($_POST['old_contact_hero_img'] ?? '');
        break;

    case 'update_contact_info':
        $data['contact_address'] = $_POST['contact_address'] ?? '';
        $data['contact_email']   = $_POST['contact_email'] ?? '';
        $data['contact_phone']   = $_POST['contact_phone'] ?? '';

        // حفظ أيقونة العنوان
        $addr_icon = handle_upload('contact_address_icon', $upload_path);
        $data['contact_address_icon'] = $addr_icon ?: ($_POST['old_contact_address_icon'] ?? '');

        // حفظ أيقونة البريد
        $email_icon = handle_upload('contact_email_icon', $upload_path);
        $data['contact_email_icon'] = $email_icon ?: ($_POST['old_contact_email_icon'] ?? '');

        // حفظ أيقونة الهاتف
        $phone_icon = handle_upload('contact_phone_icon', $upload_path);
        $data['contact_phone_icon'] = $phone_icon ?: ($_POST['old_contact_phone_icon'] ?? '');
        break;

    case 'update_whatsapp_section':
        $data['whatsapp_text']    = $_POST['whatsapp_text'] ?? '';
        $data['whatsapp_url']     = $_POST['whatsapp_url'] ?? '';
        $data['whatsapp_btn_txt'] = $_POST['whatsapp_btn_txt'] ?? '';
        break;
            // --- صفحة الاستقبال والخدمات (Arrival Service) ---
    case 'update_arrival_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        // التحقق من تهيئة مصفوفة arrival_page إن لم تكن موجودة
        if (!isset($data['arrival_page'])) { $data['arrival_page'] = []; }
        
        $data['arrival_page']['hero_img'] = $img_path ?: ($_POST['old_img'] ?? 'assets/img/education/servicesimg9.png');
        break;

    case 'update_arrival_content':
        if (!isset($data['arrival_page'])) { $data['arrival_page'] = []; }
        
        $data['arrival_page']['main_title']   = $_POST['main_title'] ?? '';
        $data['arrival_page']['main_desc']    = $_POST['main_desc'] ?? '';
        $data['arrival_page']['advice_title'] = $_POST['advice_title'] ?? '';
        $data['arrival_page']['advice_desc']  = $_POST['advice_desc'] ?? '';
        
        // معالجة مصفوفة النصائح (Tips)
        $new_tips = [];
        if (isset($_POST['tips']) && is_array($_POST['tips'])) {
            foreach ($_POST['tips'] as $tip) {
                $trimmed = trim($tip);
                if (!empty($trimmed)) {
                    $new_tips[] = $trimmed;
                }
            }
        }
        $data['arrival_page']['tips'] = $new_tips;

        // معالجة مصفوفة الملاحظات (Notes)
        $new_notes = [];
        if (isset($_POST['notes']) && is_array($_POST['notes'])) {
            foreach ($_POST['notes'] as $note) {
                $trimmed = trim($note);
                if (!empty($trimmed)) {
                    $new_notes[] = $trimmed;
                }
            }
        }
        $data['arrival_page']['notes'] = $new_notes;
        break;


    default:
        die(json_encode(['success' => false, 'message' => 'Action invalid: ' . $action]));
}

// حفظ النتيجة النهائية
if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'message' => 'تم الحفظ بنجاح']);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ أثناء الكتابة في الملف']);
}
?>
