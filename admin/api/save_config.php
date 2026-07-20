<?php
/**
 * save_config.php - ملف إدارة وتحديث إعدادات الموقع
 */
session_start();
header('Content-Type: application/json; charset=UTF-8');

// 1. التحقق من الصلاحيات
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized Access']));
}

$file = __DIR__ . '/../../announcement_config.json';
$upload_path = __DIR__ . '/../../assets/img/';

// 2. قراءة البيانات أو تهيئة مصفوفة افتراضية
// 2. قراءة البيانات أو تهيئة مصفوفة افتراضية كاملة
// 2. قراءة البيانات أو تهيئة مصفوفة افتراضية كاملة
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [
    'announcement'           => [],
    'menu_links'             => [],
    'social_links'           => [],
    'languages'              => [],
    'site_logo_path'         => 'assets/img/logo.png',
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
    // إعدادات الفوتر
    'consult_title'          => 'احصل على استشارة مجانية',
    'consult_desc'           => 'هذا النص هو مثال لنص يمكن أن يُستبدل في نفس المساحة',
    'footer_desc'            => '',
    'footer_col2_title'      => 'روابط سريعة',
    'footer_col2_links'      => [],
    'footer_col3_title'      => 'تواصل معنا',
    'footer_col3_links'      => [],

    // ==========================================
    // القيم الافتراضية لصفحة عن الشركة (about.php)
    // ==========================================
    'about'                  => [
        'title'        => 'من نحن',
        'desc'         => 'شركة بيتهوفن سيتي للخدمات (BCS) تأسست في عام 2014 في بون/ألمانيا..',
        'btn_text'     => 'قراءة المزيد',
        'btn_url'      => '#',
        'main_img'     => 'assets/img/about us icon, image/about1.jpg',
        'sub_img'      => 'assets/img/about us icon, image/about2.png',
        'vision_title' => 'رؤية الشركة',
        'vision_desc'  => 'نطمح لنكون من بين الجهات الرائدة في خدمة الأجانب وتعزيز التفاهم بين الثقافات في ألمانيا.',
        'vision_icon'  => 'assets/img/About us Icon, image/Company vision.svg',
        'message_title'=> 'رسالة الشركة',
        'message_desc' => 'نقدّم خدمات متكاملة ومتعددة اللغات بأسعار منافسة لتحقيق طموحاتك في التعليم، التدريب، والعلاج في ألمانيا.',
        'message_icon' => 'assets/img/About us Icon, image/Company message.svg'
    ],
    'team_title'             => 'فريق العمل',
    'team_desc'              => 'يسر فريق العمل تقديم خدمات عالية الجودة لتتناسب مع احتياجات العميل الكريم وتوقعاته.',
    'team_members'           => [],
    'about_counts'           => [],
    'partners_title'         => 'شركاؤنا داخل وخارج ألمانيا',
    'partners_items'         => []
];


// 3. دالة رفع الملفات مع فحص الأمان
function handle_upload($file_key, $upload_dir) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
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

// 4. معالجة العمليات
switch ($action) {
    case 'update_announcement':
        $img_path = handle_upload('ad_image', $upload_path);
        $data['announcement'] = [
            'status'            => $_POST['status'] ?? 'Draft',
            'announcement_text' => $_POST['announcement_text'] ?? '',
            'link'              => $_POST['link'] ?? '',
            'type'              => $_POST['type'] ?? 'text',
            'image_path'        => $img_path ?? ($data['announcement']['image_path'] ?? '')
        ];
        break;

    case 'update_logo':
        $img_path = handle_upload('logo_img', $upload_path);
        if ($img_path) $data['site_logo_path'] = $img_path;
        break;

    case 'update_social':
        $socials = [];
        if (isset($_POST['social']) && is_array($_POST['social'])) {
            foreach ($_POST['social'] as $index => $s) {
                $img_path = handle_upload('social_img_' . $index, $upload_path);
                $socials[] = [
                    'name' => $s['name'] ?? '',
                    'url'  => $s['url'] ?? '',
                    'img'  => $img_path ?? ($s['old_img'] ?? '')
                ];
            }
        }
        $data['social_links'] = $socials;
        break;

    case 'update_menu':
        $new_menu = [];
        if (isset($_POST['menu']) && is_array($_POST['menu'])) {
            foreach ($_POST['menu'] as $item) {
                $new_menu[] = [
                    'title' => $item['title'] ?? 'رابط جديد',
                    'url'   => $item['url'] ?? '#',
                    'order' => (int)($item['order'] ?? 0)
                ];
            }
        }
        usort($new_menu, fn($a, $b) => $a['order'] <=> $b['order']);
        $data['menu_links'] = $new_menu;
        break;

    case 'update_languages':
        $new_langs = [];
        if (isset($_POST['lang']) && is_array($_POST['lang'])) {
            foreach ($_POST['lang'] as $l) {
                if (!empty($l['name'])) $new_langs[] = ['name' => $l['name'], 'url' => $l['url'] ?? '#'];
            }
        }
        $data['languages'] = $new_langs;
        break;

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
        $data['services_section_title'] = $_POST['services_title'] ?? 'خدماتنا المميزة';
        $data['services_section_desc'] = $_POST['services_desc'] ?? ''; 
        $new_services = [];
        if (isset($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $index => $s) {
                $img_path = handle_upload('service_img_' . $index, $upload_path);
                $new_services[] = [
                    'title' => $s['title'] ?? 'عنوان الخدمة',
                    'url'   => $s['url'] ?? '#',
                    'img'   => $img_path ?? ($s['old_img'] ?? 'assets/img/home/default.jpg')
                ];
            }
        }
        $data['services'] = $new_services;
        break;

    case 'update_choose':
        $data['choose_title'] = $_POST['choose_title'] ?? 'ما الذي يميز بيتهوفن سيتي';
        $data['choose_section_desc'] = $_POST['choose_desc'] ?? '';
        $choose_items = [];
        if (isset($_POST['choose']) && is_array($_POST['choose'])) {
            foreach ($_POST['choose'] as $index => $c) {
                $img_path = handle_upload('choose_img_' . $index, $upload_path);
                $choose_items[] = [
                    'title' => $c['title'] ?? '',
                    'desc'  => $c['desc'] ?? '',
                    'img'   => $img_path ?? ($c['old_img'] ?? 'assets/img/home/Grouphome1.svg')
                ];
            }
        }
        $data['choose_items'] = $choose_items;
        break;

    case 'update_reviews':
        $data['reviews_title'] = $_POST['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا';
        $reviews = [];
        if (isset($_POST['reviews']) && is_array($_POST['reviews'])) {
            foreach ($_POST['reviews'] as $r) {
                if (!empty($r['url'])) {
                    $reviews[] = ['url' => $r['url']];
                }
            }
        }
        $data['reviews_items'] = $reviews;
        break;
case 'update_guide':
    $data['guide_title'] = $_POST['guide_title'] ?? 'دليل بيتهوفن الشامل';
    $data['guide_desc'] = $_POST['guide_desc'] ?? '';
    
    $new_guides = [];
    if (isset($_POST['guide']) && is_array($_POST['guide'])) {
        foreach ($_POST['guide'] as $index => $g) {
            $img_path = handle_upload('guide_img_' . $index, $upload_path);
            $new_guides[] = [
                'title' => $g['title'] ?? '',
                'desc'  => $g['desc'] ?? '',
                'url'   => $g['url'] ?? '#',
                'img'   => $img_path ?? ($g['old_img'] ?? 'assets/img/home/default.jpg')
            ];
        }
    }
    $data['guide_items'] = $new_guides;
    break;
    case 'update_faq':
    $data['faq_title'] = $_POST['faq_title'] ?? 'الأسئلة الشائعة';
    $new_faqs = [];
    if (isset($_POST['faq']) && is_array($_POST['faq'])) {
        foreach ($_POST['faq'] as $f) {
            $new_faqs[] = [
                'question' => $f['question'] ?? '',
                'answer'   => $f['answer'] ?? ''
            ];
        }
    }
    $data['faq_items'] = $new_faqs;
    break;

case 'update_footer':
    // 1. بيانات الاستشارة
    $data['consult_title'] = $_POST['consult_title'] ?? '';
    $data['consult_desc']  = $_POST['consult_desc'] ?? '';
    
    // 2. بيانات العمود الأول
    $data['footer_desc'] = $_POST['footer_desc'] ?? '';
    
    // 3. بيانات العمود الثاني (تم إلغاء تخزينه هنا لأنه يُجلب من الـ menu_links)
    $data['footer_col2_title'] = $_POST['footer_col2_title'] ?? 'روابط سريعة';
    
    // 4. بيانات العمود الثالث (تواصل) - مع دعم الصور
    $data['footer_col3_title'] = $_POST['footer_col3_title'] ?? 'تواصل معنا';
    $data['footer_col3_links'] = [];
    
    if (isset($_POST['col3']) && is_array($_POST['col3'])) {
        foreach ($_POST['col3'] as $i => $item) {
            // معالجة رفع الصورة لكل عنصر في التواصل
            $img_path = handle_upload('col3_img_' . $i, $upload_path);
            
            $data['footer_col3_links'][] = [
                'title' => $item['title'] ?? '',
                'url'   => $item['url'] ?? '#',
                'img'   => $img_path ?? ($item['old_img'] ?? '')
            ];
        }
    }
    break;

    // ==========================================
    // معالجات صفحة "عن الشركة" (about.php)
    // ==========================================

    case 'update_about_section':
        // 1. رفع الصور المرفقة إن وجدت
        $main_img = handle_upload('about_main_img', $upload_path);
        $sub_img  = handle_upload('about_sub_img', $upload_path);
        $vision_icon = handle_upload('about_vision_icon', $upload_path);
        $message_icon = handle_upload('about_message_icon', $upload_path);

        // 2. تحديث بيانات مصفوفة "عن الشركة"
        $data['about'] = [
            'title'        => $_POST['about_title'] ?? 'من نحن',
            'desc'         => $_POST['about_desc'] ?? '',
            'btn_text'     => $_POST['about_btn_text'] ?? 'قراءة المزيد',
            'btn_url'      => $_POST['about_btn_url'] ?? '#',
            'main_img'     => $main_img ?? ($_POST['old_about_main_img'] ?? 'assets/img/about us icon, image/about1.jpg'),
            'sub_img'      => $sub_img ?? ($_POST['old_about_sub_img'] ?? 'assets/img/about us icon, image/about2.png'),
            'vision_title' => $_POST['vision_title'] ?? 'رؤية الشركة',
            'vision_desc'  => $_POST['vision_desc'] ?? '',
            'vision_icon'  => $vision_icon ?? ($_POST['old_vision_icon'] ?? 'assets/img/About us Icon, image/Company vision.svg'),
            'message_title'=> $_POST['message_title'] ?? 'رسالة الشركة',
            'message_desc' => $_POST['message_desc'] ?? '',
            'message_icon' => $message_icon ?? ($_POST['old_message_icon'] ?? 'assets/img/About us Icon, image/Company message.svg')
        ];
        break;

    case 'update_about_team':
        $data['team_title'] = $_POST['team_title'] ?? 'فريق العمل';
        $data['team_desc']  = $_POST['team_desc'] ?? '';
        
        $new_team = [];
        if (isset($_POST['team']) && is_array($_POST['team'])) {
            foreach ($_POST['team'] as $index => $member) {
                $img_path = handle_upload('team_img_' . $index, $upload_path);
                $new_team[] = [
                    'name' => $member['name'] ?? '',
                    'role' => $member['role'] ?? '',
                    'img'  => $img_path ?? ($member['old_img'] ?? 'assets/img/team/member1.jpg')
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
        $data['partners_title'] = $_POST['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا';
        
        $new_partners = [];
        if (isset($_POST['partners']) && is_array($_POST['partners'])) {
            foreach ($_POST['partners'] as $index => $p) {
                $img_path = handle_upload('partner_img_' . $index, $upload_path);
                $new_partners[] = [
                    'img' => $img_path ?? ($p['old_img'] ?? '')
                ];
            }
        }
        $data['partners_items'] = $new_partners;
        break;


    default:
        die(json_encode(['success' => false, 'message' => 'Action invalid']));
}


// 5. حفظ البيانات
if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'message' => 'تم الحفظ بنجاح']);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ أثناء الكتابة في الملف']);
}
?>
