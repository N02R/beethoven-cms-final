<?php
/**
 * save_config.php - ملف إدارة وتحديث إعدادات الموقع كاملة (Home, About, Education, Job, Contact, Health Insurance, Living Cost, etc.)
 */
session_start();
header('Content-Type: application/json; charset=UTF-8');

// 1. التحقق من الصلاحيات
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized Access']));
}

$file = __DIR__ . '/../../announcement_config.json';
$upload_path = __DIR__ . '/../../assets/img/';

// دالة ذكية لتصحيح الروابط تلقائياً وحمايتها من أخطاء الإدخال البشري
function format_service_url($raw_url) {
    $raw_url = trim($raw_url);
    
    // إذا كان الرابط فارغاً أو مجرد شباك، اتركه كما هو
    if (empty($raw_url) || $raw_url === '#') {
        return '#';
    }
    
    // إذا كان رابطاً خارجيًّا كاملاً (يبدأ بـ http أو https أو mailto أو tel) لا تعدل عليه
    if (preg_match('/^(http:\/\/|https:\/\/|mailto:|tel:)/i', $raw_url)) {
        return $raw_url;
    }
    
    // إذا كان الرابط هو اسم صفحة فقط (مثل arrival.php) ولم يكتب المدير المجلد الفرعي
    if (!str_contains($raw_url, 'edu-services/') && !str_contains($raw_url, '/')) {
        return 'edu-services/' . ltrim($raw_url, '/');
    }
    
    return ltrim($raw_url, '/');
}

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
    'whatsapp_btn_txt'       => 'تواصل معنا عبر واتساب',

    // --- صفحة الاستقبال والخدمات الداخلية (arrival.php) ---
    'arrival_page'           => [
        'hero_img'     => 'assets/img/education/servicesimg9.png',
        'main_title'   => 'إستمتع برحلتك إلى ألمانيا، وابدأ حياتك الجديدة دون أية مشقة!',
        'main_desc'    => '',
        'advice_title' => 'ما الذي يجب فعله قبل السفر؟',
        'advice_desc'  => '',
        'note_title'   => 'ملاحظات هامة !!',
        'tips'         => [],
        'notes'        => []
    ],
    
    // --- صفحة التأمين الصحي (Health Insurance Page) ---
    'health_insurance_page'  => [
        'page_breadcrumb'     => 'التأمين الصحي',
        'page_breadcrumb_url' => '#',
        'hero_img'            => 'assets/img/education/servicesimg6.png',
        'hero_position'       => 'center center',
        'main_title'          => 'التأمين الصحي للطلاب، الزائرين، المتدربين، العاملين، وغيرهم.',
        'main_desc'           => 'نوفر لك خيارات تأمين صحي حكومي وخاص تناسب حالتك وتعتمد من السفارات والجامعات الألمانية، لتبدأ رحلتك بثقة وبدون تعقيدات.',
        'importance_section'  => [
            'title' => 'لماذا التأمين الصحي مهم؟',
            'items' => []
        ],
        'documents_section'   => [
            'title' => 'الوثائق المكملة',
            'items' => []
        ],
        'expert_note'         => '',
        'insurance_links'     => []
    ],

    // --- صفحة تكلفة المعيشة (Living Cost Page) ---
    'living_cost_page'       => [
        'page_breadcrumb'     => 'تكلفة المعيشة في ألمانيا',
        'page_breadcrumb_url' => '#',
        'hero_img'            => 'assets/img/education/servicesimg8.png',
        'hero_position'       => 'center center',
        'main_title'          => 'تكلفة المعيشة في ألمانيا للطلاب الأجانب',
        'main_desc'           => 'تُعد تكلفة المعيشة في ألمانيا معتدلة مقارنة بدول أوروبية أخرى، لكنها أعلى من الدول النامية. يحتاج الطالب الأجنبي عادةً بين 700 و900 يورو شهريًا لتغطية الإيجار، الطعام، المواصلات، التأمين وغيرها، وتختلف التكاليف حسب المدينة ونمط الحياة.',
        'tips_section'        => [
            'title' => 'نصائح لتقليل النفقات',
            'items' => [
                'اختيار السكن الجامعي أو مشاركة شقة مع طلاب آخرين.',
                'استخدام بطاقة الطالب لخصومات في المواصلات والمتاجر.',
                'فرز النفايات المنزلية إلزامي لتجنب الغرامات والإخلاء.',
                'تجنّب المكتبات العقارية إلا عند الضرورة، لتوفير التكاليف.'
            ]
        ],
        'notes_section'       => [
            'title' => 'ملاحظات هامة !!',
            'items' => [
                'يتوجب دفع وديعة (Kaution) تعادل إيجار شهر أو شهرين عند توقيع العقد.',
                'عند طلب سكن عن طريق مكتب عقاري، قد تُدفع عمولة تتراوح بين 600 إلى 1000 يورو.',
                'خيار مشاركة شقة (WG) مع طلاب آخرين يساعد في تقليل التكاليف.'
            ]
        ]
    ]
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
            'link'              => format_service_url($_POST['link'] ?? ''),
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
            'btn_url'  => format_service_url($_POST['hero_btn_url'] ?? ''),
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
                    'url'   => format_service_url($s['url'] ?? '#'),
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
                if (!empty($r['url'])) $reviews[] = ['url' => format_service_url($r['url'])];
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
                    'url'   => format_service_url($g['url'] ?? '#'),
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
                    'url'   => format_service_url($item['url'] ?? '#'),
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
            'btn_url'      => format_service_url($_POST['about_btn_url'] ?? '#'),
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
            'btn_url'  => format_service_url($_POST['btn_url'] ?? '#'),
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
                $new_edu_srv[] = [
                    'title' => $srv['title'] ?? '', 
                    'url'   => format_service_url($srv['url'] ?? '#'), 
                    'img'   => $img_path ?? ($srv['old_img'] ?? '')
                ];
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
            'btn_url'  => format_service_url($_POST['btn_url'] ?? '#'),
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
                    'btn_url'  => format_service_url($prog['btn_url'] ?? '#'),
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
                $new_job_srv[] = [
                    'title' => $srv['title'] ?? '', 
                    'url'   => format_service_url($srv['url'] ?? '#'), 
                    'img'   => $img_path ?? ($srv['old_img'] ?? '')
                ];
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

        $addr_icon = handle_upload('contact_address_icon', $upload_path);
        $data['contact_address_icon'] = $addr_icon ?: ($_POST['old_contact_address_icon'] ?? '');

        $email_icon = handle_upload('contact_email_icon', $upload_path);
        $data['contact_email_icon'] = $email_icon ?: ($_POST['old_contact_email_icon'] ?? '');

        $phone_icon = handle_upload('contact_phone_icon', $upload_path);
        $data['contact_phone_icon'] = $phone_icon ?: ($_POST['old_contact_phone_icon'] ?? '');
        break;

    case 'update_whatsapp_section':
        $data['whatsapp_text']    = $_POST['whatsapp_text'] ?? '';
        $data['whatsapp_url']     = format_service_url($_POST['whatsapp_url'] ?? '');
        $data['whatsapp_btn_txt'] = $_POST['whatsapp_btn_txt'] ?? '';
        break;

    // --- صفحة الاستقبال والخدمات (Arrival Service) ---
    case 'update_arrival_breadcrumb':
        if (!isset($data['arrival_page'])) { $data['arrival_page'] = []; }
        $data['arrival_page']['page_breadcrumb']     = $_POST['page_breadcrumb'] ?? '';
        $data['arrival_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_arrival_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['arrival_page'])) { $data['arrival_page'] = []; }
        $data['arrival_page']['hero_img'] = $img_path ?: ($_POST['old_img'] ?? 'assets/img/education/servicesimg9.png');
        break;

    case 'update_arrival_main_title':
        if (!isset($data['arrival_page'])) { $data['arrival_page'] = []; }
        $data['arrival_page']['main_title'] = $_POST['main_title'] ?? '';
        $data['arrival_page']['main_desc']  = $_POST['main_desc'] ?? '';
        break;

    case 'update_arrival_tips':
        if (!isset($data['arrival_page'])) { $data['arrival_page'] = []; }
        $data['arrival_page']['advice_title'] = $_POST['advice_title'] ?? '';
        $data['arrival_page']['advice_desc']  = $_POST['advice_desc'] ?? '';
        
        $tips = $_POST['tips'] ?? [];
        $data['arrival_page']['tips'] = array_values(array_filter(array_map('trim', $tips)));
        break;

    case 'update_arrival_notes':
        if (!isset($data['arrival_page'])) { $data['arrival_page'] = []; }
        $data['arrival_page']['note_title'] = $_POST['note_title'] ?? '';
        
        $notes = $_POST['notes'] ?? [];
        $data['arrival_page']['notes'] = array_values(array_filter(array_map('trim', $notes)));
        break;

    // --- صفحة Bachelor Package ---
    case 'update_bachelor_breadcrumb':
        if (!isset($data['bachelor_page'])) { $data['bachelor_page'] = []; }
        $data['bachelor_page']['page_breadcrumb']     = $_POST['page_breadcrumb'] ?? '';
        $data['bachelor_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_bachelor_content':
        if (!isset($data['bachelor_page'])) { $data['bachelor_page'] = []; }
        $data['bachelor_page']['main_title']     = $_POST['main_title'] ?? '';
        $data['bachelor_page']['main_desc']      = $_POST['main_desc'] ?? '';
        $data['bachelor_page']['password_label'] = $_POST['password_label'] ?? '';
        $data['bachelor_page']['btn_text']       = $_POST['btn_text'] ?? '';
        break;

    // --- صفحة Check (التحقق من الشهادات) ---
    case 'update_check_breadcrumb':
        if (!isset($data['check_page'])) { $data['check_page'] = []; }
        $data['check_page']['page_breadcrumb']     = $_POST['page_breadcrumb'] ?? '';
        $data['check_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_check_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['check_page'])) { $data['check_page'] = []; }
        $data['check_page']['hero_img'] = $img_path ?: ($_POST['old_img'] ?? 'assets/img/education/servicesimg13.png');
        break;

    case 'update_check_main':
        if (!isset($data['check_page'])) { $data['check_page'] = []; }
        $data['check_page']['main_title'] = $_POST['main_title'] ?? '';
        $data['check_page']['main_desc']  = $_POST['main_desc'] ?? '';
        break;

    case 'update_check_notes':
        if (!isset($data['check_page'])) { $data['check_page'] = []; }
        $data['check_page']['note_title'] = $_POST['note_title'] ?? '';
        $notes = $_POST['notes'] ?? [];
        $data['check_page']['notes'] = array_values(array_filter(array_map('trim', $notes)));
        break;

    case 'update_check_links':
        if (!isset($data['check_page'])) { $data['check_page'] = []; }
        $data['check_page']['links_intro']       = $_POST['links_intro'] ?? '';
        $data['check_page']['anabin_url']        = format_service_url($_POST['anabin_url'] ?? '#');
        $data['check_page']['uniassist_url']     = format_service_url($_POST['uniassist_url'] ?? '#');
        $data['check_page']['uni_contact_intro'] = $_POST['uni_contact_intro'] ?? '';
        $data['check_page']['condition_1']       = $_POST['condition_1'] ?? '';
        $data['check_page']['condition_2']       = $_POST['condition_2'] ?? '';
        $data['check_page']['conclusion_text']   = $_POST['conclusion_text'] ?? '';
        break;

    // --- صفحة دورات اللغة الألمانية (Language Courses) ---
    case 'update_lang_breadcrumb':
        if (!isset($data['language_page'])) { $data['language_page'] = []; }
        $data['language_page']['page_breadcrumb']     = $_POST['page_breadcrumb'] ?? '';
        $data['language_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_lang_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['language_page'])) { $data['language_page'] = []; }
        $data['language_page']['hero_img'] = $img_path ?: ($_POST['old_img'] ?? 'assets/img/education/servicesimg12.png');
        break;

    case 'update_lang_main':
        if (!isset($data['language_page'])) { $data['language_page'] = []; }
        $data['language_page']['main_title'] = $_POST['main_title'] ?? '';
        $data['language_page']['main_desc']  = $_POST['main_desc'] ?? '';
        break;

    case 'update_lang_goals':
        if (!isset($data['language_page'])) { $data['language_page'] = []; }
        $data['language_page']['goals_title'] = $_POST['goals_title'] ?? '';
        $goals = $_POST['goals'] ?? [];
        $data['language_page']['goals'] = array_values(array_filter(array_map('trim', $goals)));
        break;

    case 'update_lang_warning':
        if (!isset($data['language_page'])) { $data['language_page'] = []; }
        $data['language_page']['warning_text'] = $_POST['warning_text'] ?? '';
        break;

    case 'update_lang_cost':
        if (!isset($data['language_page'])) { $data['language_page'] = []; }
        $data['language_page']['cost_title'] = $_POST['cost_title'] ?? '';
        
        $titles = $_POST['cost_titles'] ?? [];
        $descs  = $_POST['cost_descs'] ?? [];
        $cost_items = [];
        
        for ($i = 0; $i < count($titles); $i++) {
            $t = trim($titles[$i]);
            $d = trim($descs[$i]);
            if (!empty($t) || !empty($d)) {
                $cost_items[] = ['title' => $t, 'desc' => $d];
            }
        }
        $data['language_page']['cost_items'] = $cost_items;
        break;

    // --- صفحة خطاب الطلب (Cover Letter) ---
    case 'update_cover_breadcrumb':
        if (!isset($data['coverletter_page'])) { $data['coverletter_page'] = []; }
        $data['coverletter_page']['page_breadcrumb']     = $_POST['page_breadcrumb'] ?? '';
        $data['coverletter_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_cover_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['coverletter_page'])) { $data['coverletter_page'] = []; }
        $data['coverletter_page']['hero_img'] = $img_path ?: ($_POST['old_img'] ?? 'assets/img/education/servicesimg1.jpg');
        break;

    case 'update_cover_main':
        if (!isset($data['coverletter_page'])) { $data['coverletter_page'] = []; }
        $data['coverletter_page']['main_title'] = $_POST['main_title'] ?? '';
        $data['coverletter_page']['main_desc']  = $_POST['main_desc'] ?? '';
        break;

    case 'update_cover_advice':
        if (!isset($data['coverletter_page'])) { $data['coverletter_page'] = []; }
        $data['coverletter_page']['advice_title'] = $_POST['advice_title'] ?? '';
        $points = $_POST['advice_points'] ?? [];
        $data['coverletter_page']['advice_points'] = array_values(array_filter(array_map('trim', $points)));
        break;

    case 'update_cover_notes':
        if (!isset($data['coverletter_page'])) { $data['coverletter_page'] = []; }
        $data['coverletter_page']['note_title'] = $_POST['note_title'] ?? '';
        $notes = $_POST['notes'] ?? [];
        $data['coverletter_page']['notes'] = array_values(array_filter(array_map('trim', $notes)));
        break;

    case 'update_cover_downloads':
        if (!isset($data['coverletter_page'])) { $data['coverletter_page'] = []; }
        
        $titles = $_POST['download_titles'] ?? [];
        $download_items = [];
        for ($i = 0; $i < count($titles); $i++) {
            $t = trim($titles[$i]);
            if (!empty($t)) {
                $download_items[] = [
                    'type'  => $_POST['download_types'][$i] ?? 'pdf',
                    'title' => $t,
                    'sub'   => trim($_POST['download_subs'][$i] ?? 'Example'),
                    'file'  => trim($_POST['download_files'][$i] ?? '#')
                ];
            }
        }
        $data['coverletter_page']['download_items'] = $download_items;
        break;

    // --- صفحة السيرة الذاتية (CV Page) ---
    case 'update_cv_breadcrumb':
        if (!isset($data['cv_page'])) { $data['cv_page'] = []; }
        $data['cv_page']['page_breadcrumb']     = $_POST['page_breadcrumb'] ?? '';
        $data['cv_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_cv_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['cv_page'])) { $data['cv_page'] = []; }
        $data['cv_page']['hero_img'] = $img_path ?: ($_POST['old_img'] ?? 'assets/img/education/servicesimg2.jpg');
        break;

    case 'update_cv_main':
        if (!isset($data['cv_page'])) { $data['cv_page'] = []; }
        $data['cv_page']['main_title'] = $_POST['main_title'] ?? '';
        $data['cv_page']['main_desc']  = $_POST['main_desc'] ?? '';
        break;

    case 'update_cv_advice':
        if (!isset($data['cv_page'])) { $data['cv_page'] = []; }
        $data['cv_page']['advice_title'] = $_POST['advice_title'] ?? '';
        $points = $_POST['advice_points'] ?? [];
        $data['cv_page']['advice_points'] = array_values(array_filter(array_map('trim', $points)));
        break;

    case 'update_cv_downloads':
        if (!isset($data['cv_page'])) { $data['cv_page'] = []; }
        
        $titles = $_POST['download_titles'] ?? [];
        $download_items = [];
        for ($i = 0; $i < count($titles); $i++) {
            $t = trim($titles[$i]);
            if (!empty($t)) {
                $download_items[] = [
                    'type'  => $_POST['download_types'][$i] ?? 'pdf',
                    'title' => $t,
                    'sub'   => trim($_POST['download_subs'][$i] ?? 'Example'),
                    'file'  => trim($_POST['download_files'][$i] ?? '#')
                ];
            }
        }
        $data['cv_page']['download_items'] = $download_items;
        break;

    // --- صفحة برامج دراسية باللغة الإنجليزية (English Programs Page) ---
    case 'update_english_breadcrumb':
        if (!isset($data['english_programs_page'])) { $data['english_programs_page'] = []; }
        $data['english_programs_page']['page_breadcrumb']     = $_POST['page_breadcrumb'] ?? '';
        $data['english_programs_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_english_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['english_programs_page'])) { $data['english_programs_page'] = []; }
        $data['english_programs_page']['hero_img'] = $img_path ?: ($_POST['old_img'] ?? 'assets/img/education/servicesimg5.png');
        break;

    case 'update_english_main':
        if (!isset($data['english_programs_page'])) { $data['english_programs_page'] = []; }
        $data['english_programs_page']['main_title'] = $_POST['main_title'] ?? '';
        $data['english_programs_page']['main_desc']  = $_POST['main_desc'] ?? '';
        break;

    case 'update_english_who':
        if (!isset($data['english_programs_page'])) { $data['english_programs_page'] = []; }
        $data['english_programs_page']['who_title']    = $_POST['who_title'] ?? '';
        $data['english_programs_page']['who_subtitle'] = $_POST['who_subtitle'] ?? '';
        $items = $_POST['who_items'] ?? [];
        $data['english_programs_page']['who_items'] = array_values(array_filter(array_map('trim', $items)));
        break;

    case 'update_english_lang':
        if (!isset($data['english_programs_page'])) { $data['english_programs_page'] = []; }
        $data['english_programs_page']['lang_title'] = $_POST['lang_title'] ?? '';
        $points = $_POST['lang_points'] ?? [];
        $data['english_programs_page']['lang_points'] = array_values(array_filter(array_map('trim', $points)));
        break;

    case 'update_english_note':
        if (!isset($data['english_programs_page'])) { $data['english_programs_page'] = []; }
        $data['english_programs_page']['note_highlight'] = $_POST['note_highlight'] ?? 'ملاحظة:';
        $data['english_programs_page']['note_text']      = $_POST['note_text'] ?? '';
        break;

    // --- صفحة الضمانات المالية والحساب المغلق (Blocked Account Page) ---
    case 'update_blocked_breadcrumb':
        if (!isset($data['blocked_account_page'])) { $data['blocked_account_page'] = []; }
        $data['blocked_account_page']['page_breadcrumb']     = $_POST['page_breadcrumb'] ?? '';
        $data['blocked_account_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_blocked_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['blocked_account_page'])) { $data['blocked_account_page'] = []; }
        $data['blocked_account_page']['hero_img'] = $img_path ?: ($_POST['old_img'] ?? 'assets/img/education/servicesimg7.png');
        break;

    case 'update_blocked_main':
        if (!isset($data['blocked_account_page'])) { $data['blocked_account_page'] = []; }
        $data['blocked_account_page']['main_title']       = $_POST['main_title'] ?? '';
        $data['blocked_account_page']['main_desc']        = $_POST['main_desc'] ?? '';
        $data['blocked_account_page']['importance_title'] = $_POST['importance_title'] ?? '';
        $data['blocked_account_page']['importance_desc']  = $_POST['importance_desc'] ?? '';
        break;

    case 'update_blocked_options':
        if (!isset($data['blocked_account_page'])) { $data['blocked_account_page'] = []; }
        $data['blocked_account_page']['options_title'] = $_POST['options_title'] ?? '';
        $opts = $_POST['options_items'] ?? [];
        $data['blocked_account_page']['options_items'] = array_values(array_filter(array_map('trim', $opts)));
        break;

    case 'update_blocked_account':
        if (!isset($data['blocked_account_page'])) { $data['blocked_account_page'] = []; }
        $data['blocked_account_page']['account_title'] = $_POST['account_title'] ?? '';
        $pts = $_POST['account_points'] ?? [];
        $data['blocked_account_page']['account_points'] = array_values(array_filter(array_map('trim', $pts)));
        break;

    case 'update_blocked_links':
        if (!isset($data['blocked_account_page'])) { $data['blocked_account_page'] = []; }
        $texts = $_POST['link_texts'] ?? [];
        $urls  = $_POST['link_urls'] ?? [];
        $service_links = [];
        for ($i = 0; $i < count($texts); $i++) {
            $txt = trim($texts[$i]);
            if (!empty($txt)) {
                $service_links[] = [
                    'text' => $txt,
                    'url'  => format_service_url(trim($urls[$i] ?? '#'))
                ];
            }
        }
        $data['blocked_account_page']['service_links'] = $service_links;
        break;

    // --- صفحة الدورة التأسيسية / السنة التحضيرية (Studienkolleg Page) ---
    case 'update_stk_breadcrumb':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['page_breadcrumb']     = $_POST['page_breadcrumb'] ?? '';
        $data['studienkolleg_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_stk_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['hero_img'] = $img_path ?: ($_POST['old_img'] ?? 'assets/img/education/serviceimg11.png');
        $data['studienkolleg_page']['hero_position'] = $_POST['hero_position'] ?? 'center -20rem';
        break;

    case 'update_stk_main':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['main_title'] = $_POST['main_title'] ?? '';
        $data['studienkolleg_page']['main_desc']  = $_POST['main_desc'] ?? '';
        break;

    case 'update_stk_goals':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['goals_title'] = $_POST['goals_title'] ?? '';
        $items = $_POST['goals_items'] ?? [];
        $data['studienkolleg_page']['goals_items'] = array_values(array_filter(array_map('trim', $items)));
        break;

    case 'update_stk_learning':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['learning_title'] = $_POST['learning_title'] ?? '';
        $data['studienkolleg_page']['learning_intro'] = $_POST['learning_intro'] ?? '';
        $data['studienkolleg_page']['learning_p1']    = $_POST['learning_p1'] ?? '';
        $data['studienkolleg_page']['learning_p2']    = $_POST['learning_p2'] ?? '';
        break;

    case 'update_stk_courses':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['courses_title'] = $_POST['courses_title'] ?? '';
        $courses = $_POST['courses_items'] ?? [];
        $data['studienkolleg_page']['courses_items'] = array_values(array_filter(array_map('trim', $courses)));
        break;

    case 'update_stk_unitype':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['uni_type_title'] = $_POST['uni_type_title'] ?? '';
        $data['studienkolleg_page']['uni_type_intro'] = $_POST['uni_type_intro'] ?? '';
        $data['studienkolleg_page']['uni_public']     = $_POST['uni_public'] ?? '';
        $data['studienkolleg_page']['uni_applied']    = $_POST['uni_applied'] ?? '';
        break;

    case 'update_stk_types':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['types_title']      = $_POST['types_title'] ?? '';
        $data['studienkolleg_page']['type_public_desc'] = $_POST['type_public_desc'] ?? '';
        $data['studienkolleg_page']['type_private_desc']= $_POST['type_private_desc'] ?? '';
        break;

    case 'update_stk_notes':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['notes_title'] = $_POST['notes_title'] ?? '';
        $notes = $_POST['notes_items'] ?? [];
        $data['studienkolleg_page']['notes_items'] = array_values(array_filter(array_map('trim', $notes)));
        break;

    case 'update_stk_examfsp':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['exam_title'] = $_POST['exam_title'] ?? '';
        $data['studienkolleg_page']['exam_desc']  = $_POST['exam_desc'] ?? '';
        $data['studienkolleg_page']['fsp_title']  = $_POST['fsp_title'] ?? '';
        $data['studienkolleg_page']['fsp_desc']   = $_POST['fsp_desc'] ?? '';
        break;

    case 'update_stk_tips':
        if (!isset($data['studienkolleg_page'])) { $data['studienkolleg_page'] = []; }
        $data['studienkolleg_page']['tips_title'] = $_POST['tips_title'] ?? '';
        $tips = $_POST['tips_items'] ?? [];
        $data['studienkolleg_page']['tips_items'] = array_values(array_filter(array_map('trim', $tips)));
        break;

    // --- صفحة متطلبات التأشيرة (Visa Requirements Page) ---
    case 'update_visa_notes':
        if (!isset($data['visa_requirements_page'])) { $data['visa_requirements_page'] = []; }
        $title = $_POST['note_title'] ?? 'ملاحظة !!';
        $raw_notes = $_POST['notes_list'] ?? [];
        
        $clean_notes = [];
        foreach ($raw_notes as $note) {
            if (!empty(trim($note))) {
                $clean_notes[] = trim($note);
            }
        }
        
        $data['visa_requirements_page']['notes_section'] = [
            'title' => trim($title),
            'notes_list' => $clean_notes
        ];
        break;

    case 'update_visa_downloads':
        if (!isset($data['visa_requirements_page'])) { $data['visa_requirements_page'] = []; }
        $types  = $_POST['download_types'] ?? [];
        $titles = $_POST['download_titles'] ?? [];
        $subs   = $_POST['download_subs'] ?? [];
        $files  = $_POST['download_files'] ?? [];
        
        $download_items = [];
        for ($i = 0; $i < count($titles); $i++) {
            if (!empty(trim($titles[$i]))) {
                $download_items[] = [
                    'type'  => $types[$i] ?? 'pdf',
                    'title' => trim($titles[$i]),
                    'sub'   => trim($subs[$i] ?? ''),
                    'file'  => trim($files[$i] ?? '#')
                ];
            }
        }
        $data['visa_requirements_page']['download_items'] = $download_items;
        break;

    // --- صفحة دورات اللغة (German Language Courses) ---
    case 'update_german_hero':
        if (!isset($data['germanlang_page'])) { $data['germanlang_page'] = []; }
        $data['germanlang_page']['hero_img'] = trim($_POST['hero_img'] ?? '');
        $data['germanlang_page']['hero_position'] = trim($_POST['hero_position'] ?? 'center center');
        break;

    case 'update_german_main':
        if (!isset($data['germanlang_page'])) { $data['germanlang_page'] = []; }
        $data['germanlang_page']['main_title'] = trim($_POST['main_title'] ?? '');
        $data['germanlang_page']['main_desc'] = trim($_POST['main_desc'] ?? '');
        break;

    case 'update_german_levels':
        if (!isset($data['germanlang_page'])) { $data['germanlang_page'] = []; }
        $levels_title = $_POST['levels_title'] ?? 'المستويات المتوفرة (طبقًا ل CEFR)';
        $raw_levels = $_POST['levels_list'] ?? [];
        
        $clean_levels = [];
        foreach ($raw_levels as $lvl) {
            if (!empty(trim($lvl))) {
                $clean_levels[] = trim($lvl);
            }
        }
        
        $data['germanlang_page']['levels_section'] = [
            'title' => trim($levels_title),
            'levels_list' => $clean_levels
        ];
        break;

    case 'update_german_features_tips':
        if (!isset($data['germanlang_page'])) { $data['germanlang_page'] = []; }
        
        $feat_title = $_POST['features_title'] ?? 'مميزات دوراتنا';
        $raw_feats = $_POST['features_list'] ?? [];
        $clean_feats = [];
        foreach ($raw_feats as $f) {
            if (!empty(trim($f))) { $clean_feats[] = trim($f); }
        }
        $data['germanlang_page']['features_section'] = [
            'title' => trim($feat_title),
            'features_list' => $clean_feats
        ];

        $tips_title = $_POST['tips_title'] ?? 'نصائح للنجاح في الدراسة بالألمانية';
        $raw_tips = $_POST['tips_list'] ?? [];
        $clean_tips = [];
        foreach ($raw_tips as $t) {
            if (!empty(trim($t))) { $clean_tips[] = trim($t); }
        }
        $data['germanlang_page']['tips_section'] = [
            'title' => trim($tips_title),
            'tips_list' => $clean_tips
        ];
        break;

    // --- صفحة التأمين الصحي (Health Insurance Page) ---
    case 'update_health_breadcrumb':
        if (!isset($data['health_insurance_page'])) { $data['health_insurance_page'] = []; }
        $data['health_insurance_page']['page_breadcrumb']     = trim($_POST['page_breadcrumb'] ?? '');
        $data['health_insurance_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_health_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['health_insurance_page'])) { $data['health_insurance_page'] = []; }
        $data['health_insurance_page']['hero_img']      = $img_path ?: trim($_POST['old_img'] ?? 'assets/img/education/servicesimg6.png');
        $data['health_insurance_page']['hero_position'] = trim($_POST['hero_position'] ?? 'center center');
        break;

    case 'update_health_main':
        if (!isset($data['health_insurance_page'])) { $data['health_insurance_page'] = []; }
        $data['health_insurance_page']['main_title'] = trim($_POST['main_title'] ?? '');
        $data['health_insurance_page']['main_desc']  = trim($_POST['main_desc'] ?? '');
        break;

    case 'update_health_importance':
        if (!isset($data['health_insurance_page'])) { $data['health_insurance_page'] = []; }
        $importance_title = trim($_POST['importance_title'] ?? 'لماذا التأمين الصحي مهم؟');
        $raw_items = $_POST['importance_items'] ?? [];
        
        $cleaned_items = [];
        if (is_array($raw_items)) {
            foreach ($raw_items as $item) {
                $trimmed = trim($item);
                if (!empty($trimmed)) {
                    $cleaned_items[] = $trimmed;
                }
            }
        }

        $data['health_insurance_page']['importance_section'] = [
            'title' => $importance_title,
            'items' => $cleaned_items
        ];
        break;

    case 'update_health_documents':
        if (!isset($data['health_insurance_page'])) { $data['health_insurance_page'] = []; }
        $documents_title = trim($_POST['documents_title'] ?? 'الوثائق المكملة');
        $raw_docs = $_POST['documents_items'] ?? [];
        
        $cleaned_docs = [];
        if (is_array($raw_docs)) {
            foreach ($raw_docs as $doc) {
                $trimmed = trim($doc);
                if (!empty($trimmed)) {
                    $cleaned_docs[] = $trimmed;
                }
            }
        }

        $data['health_insurance_page']['documents_section'] = [
            'title' => $documents_title,
            'items' => $cleaned_docs
        ];
        break;

    case 'update_health_links':
        if (!isset($data['health_insurance_page'])) { $data['health_insurance_page'] = []; }
        $expert_note = trim($_POST['expert_note'] ?? '');
        $link_titles = $_POST['link_titles'] ?? [];
        $link_urls   = $_POST['link_urls'] ?? [];
        $raw_actives = $_POST['link_actives'] ?? [];

        $insurance_links = [];
        if (is_array($link_titles)) {
            foreach ($link_titles as $index => $title) {
                $title_trimmed = trim($title);
                $url_trimmed   = format_service_url(trim($link_urls[$index] ?? '#'));

                if (!empty($title_trimmed)) {
                    $is_active = in_array((string)$index, array_map('strval', $raw_actives), true) || in_array($index, $raw_actives);

                    $insurance_links[] = [
                        'title'  => $title_trimmed,
                        'url'    => $url_trimmed,
                        'active' => (bool)$is_active
                    ];
                }
            }
        }

        $data['health_insurance_page']['expert_note']     = $expert_note;
        $data['health_insurance_page']['insurance_links'] = $insurance_links;
        break;

    // --- صفحة تكلفة المعيشة (Living Cost Page) ---
    case 'update_living_breadcrumb':
        if (!isset($data['living_cost_page'])) { $data['living_cost_page'] = []; }
        $data['living_cost_page']['page_breadcrumb']     = trim($_POST['page_breadcrumb'] ?? '');
        $data['living_cost_page']['page_breadcrumb_url'] = format_service_url($_POST['page_breadcrumb_url'] ?? '#');
        break;

    case 'update_living_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        if (!isset($data['living_cost_page'])) { $data['living_cost_page'] = []; }
        $data['living_cost_page']['hero_img']      = $img_path ?: trim($_POST['old_img'] ?? 'assets/img/education/servicesimg8.png');
        $data['living_cost_page']['hero_position'] = trim($_POST['hero_position'] ?? 'center center');
        break;

    case 'update_living_main':
        if (!isset($data['living_cost_page'])) { $data['living_cost_page'] = []; }
        $data['living_cost_page']['main_title'] = trim($_POST['main_title'] ?? '');
        $data['living_cost_page']['main_desc']  = trim($_POST['main_desc'] ?? '');
        break;

    case 'update_living_tips':
        if (!isset($data['living_cost_page'])) { $data['living_cost_page'] = []; }
        $tips_title = trim($_POST['tips_title'] ?? 'نصائح لتقليل النفقات');
        $raw_tips   = $_POST['tips_items'] ?? [];
        
        $cleaned_tips = [];
        if (is_array($raw_tips)) {
            foreach ($raw_tips as $tip) {
                $trimmed = trim($tip);
                if (!empty($trimmed)) {
                    $cleaned_tips[] = $trimmed;
                }
            }
        }

        $data['living_cost_page']['tips_section'] = [
            'title' => $tips_title,
            'items' => $cleaned_tips
        ];
        break;

    case 'update_living_notes':
        if (!isset($data['living_cost_page'])) { $data['living_cost_page'] = []; }
        $notes_title = trim($_POST['notes_title'] ?? 'ملاحظات هامة !!');
        $raw_notes   = $_POST['notes_items'] ?? [];
        
        $cleaned_notes = [];
        if (is_array($raw_notes)) {
            foreach ($raw_notes as $note) {
                $trimmed = trim($note);
                if (!empty($trimmed)) {
                    $cleaned_notes[] = $trimmed;
                }
            }
        }

        $data['living_cost_page']['notes_section'] = [
            'title' => $notes_title,
            'items' => $cleaned_notes
        ];
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
