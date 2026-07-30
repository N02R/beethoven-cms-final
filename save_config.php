<?>
<?php
/**
 * save_config.php - ملف إدارة وتحديث إعدادات الموقع عبر قاعدة البيانات
 */
session_start();
header('Content-Type: application/json; charset=UTF-8');

// 1. التحقق من الصلاحيات
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized Access']));
}

// استدعاء ملف الاتصال بقاعدة البيانات
require_once __DIR__ . '/database/database.php';
use App\Config\Database;

try {
    $pdo = Database::getConnection();
} catch (\Exception $e) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// مسار رفع الصور الفعلي على السيرفر
$upload_path = __DIR__ . '/public/uploads/';

// التأكد من أن مجلد الرفع موجود
if (!is_dir($upload_path)) {
    mkdir($upload_path, 0755, true);
}

// دالة لتخزين أو تحديث الإعدادات في جدول site_settings
function saveSetting($pdo, $key, $value) {
    $valueToStore = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    
    $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) 
                           ON DUPLICATE KEY UPDATE setting_value = ?");
    return $stmt->execute([$key, $valueToStore, $valueToStore]);
}

// دالة رفع الملفات مع إرجاع المسار النسبي الصحيح
function handle_upload($file_key, $upload_dir) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $file_name = $_FILES[$file_key]['name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_ext)) {
            $new_name = $file_key . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $new_name)) {
                return 'uploads/' . $new_name;
            }
        }
    }
    return null;
}

$action = $_POST['action'] ?? '';
$success = false;

// 2. معالجة العمليات والحفظ في قاعدة البيانات مباشرة
switch ($action) {
    case 'update_announcement':
        $img_path = handle_upload('ad_image', $upload_path);
        $announcement_data = [
            'status'            => $_POST['status'] ?? 'Draft',
            'announcement_text' => $_POST['announcement_text'] ?? '',
            'link'              => $_POST['link'] ?? '',
            'type'              => $_POST['type'] ?? 'text',
            'image_path'        => $img_path ?? ($_POST['old_image_path'] ?? '')
        ];
        $success = saveSetting($pdo, 'announcement', $announcement_data);
        break;

    case 'update_logo':
    case 'update_general_settings':
        $img_path = handle_upload('logo_img', $upload_path);
        if (!$img_path) {
            $img_path = $_POST['site_logo_path'] ?? '';
        }
        $success = saveSetting($pdo, 'site_logo_path', $img_path);
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
        $success = saveSetting($pdo, 'social_links', $socials);
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
        $success = saveSetting($pdo, 'menu_links', $new_menu);
        break;

    case 'update_languages':
        $new_langs = [];
        if (isset($_POST['lang']) && is_array($_POST['lang'])) {
            foreach ($_POST['lang'] as $l) {
                if (!empty($l['name'])) $new_langs[] = ['name' => $l['name'], 'url' => $l['url'] ?? '#'];
            }
        }
        $success = saveSetting($pdo, 'languages', $new_langs);
        break;

    case 'update_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        $hero_data = [
            'title'    => $_POST['hero_title'] ?? '',
            'desc'     => $_POST['hero_desc'] ?? '',
            'btn_text' => $_POST['hero_btn_text'] ?? '',
            'btn_url'  => $_POST['hero_btn_url'] ?? '',
            'img'      => $img_path ?? ($_POST['old_hero_img'] ?? 'assets/img/hero-bg.jpg')
        ];
        $success = saveSetting($pdo, 'hero', $hero_data);
        break;

    case 'update_services':
        $services_title = $_POST['services_title'] ?? 'خدماتنا المميزة';
        $services_desc = $_POST['services_desc'] ?? '';
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
        saveSetting($pdo, 'services_section_title', $services_title);
        saveSetting($pdo, 'services_section_desc', $services_desc);
        $success = saveSetting($pdo, 'services', $new_services);
        break;

    case 'update_choose':
        $choose_title = $_POST['choose_title'] ?? 'ما الذي يميز بيتهوفن سيتي';
        $choose_desc = $_POST['choose_desc'] ?? '';
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
        saveSetting($pdo, 'choose_title', $choose_title);
        saveSetting($pdo, 'choose_section_desc', $choose_desc);
        $success = saveSetting($pdo, 'choose_items', $choose_items);
        break;

    case 'update_reviews':
        $reviews_title = $_POST['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا';
        $reviews = [];
        if (isset($_POST['reviews']) && is_array($_POST['reviews'])) {
            foreach ($_POST['reviews'] as $r) {
                if (!empty($r['url'])) {
                    $reviews[] = ['url' => $r['url']];
                }
            }
        }
        saveSetting($pdo, 'reviews_title', $reviews_title);
        $success = saveSetting($pdo, 'reviews_items', $reviews);
        break;

    case 'update_guide':
        $guide_title = $_POST['guide_title'] ?? 'دليل بيتهوفن الشامل';
        $guide_desc = $_POST['guide_desc'] ?? '';
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
        saveSetting($pdo, 'guide_title', $guide_title);
        saveSetting($pdo, 'guide_desc', $guide_desc);
        $success = saveSetting($pdo, 'guide_items', $new_guides);
        break;

    case 'update_faq':
        $faq_title = $_POST['faq_title'] ?? 'الأسئلة الشائعة';
        $new_faqs = [];
        if (isset($_POST['faq']) && is_array($_POST['faq'])) {
            foreach ($_POST['faq'] as $f) {
                $new_faqs[] = [
                    'question' => $f['question'] ?? '',
                    'answer'   => $f['answer'] ?? ''
                ];
            }
        }
        saveSetting($pdo, 'faq_title', $faq_title);
        $success = saveSetting($pdo, 'faq_items', $new_faqs);
        break;

    case 'update_footer':
        saveSetting($pdo, 'consult_title', $_POST['consult_title'] ?? '');
        saveSetting($pdo, 'consult_desc', $_POST['consult_desc'] ?? '');
        saveSetting($pdo, 'footer_desc', $_POST['footer_desc'] ?? '');
        saveSetting($pdo, 'footer_col2_title', $_POST['footer_col2_title'] ?? 'روابط سريعة');
        saveSetting($pdo, 'footer_col3_title', $_POST['footer_col3_title'] ?? 'تواصل معنا');
        
        $col3_links = [];
        if (isset($_POST['col3']) && is_array($_POST['col3'])) {
            foreach ($_POST['col3'] as $i => $item) {
                $img_path = handle_upload('col3_img_' . $i, $upload_path);
                $col3_links[] = [
                    'title' => $item['title'] ?? '',
                    'url'   => $item['url'] ?? '#',
                    'img'   => $img_path ?? ($item['old_img'] ?? '')
                ];
            }
        }
        $success = saveSetting($pdo, 'footer_col3_links', $col3_links);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Action invalid']);
        exit;
}

// 3. النتيجة النهائية للعميل (AJAX Response)
if ($success) {
    echo json_encode(['success' => true, 'message' => 'تم الحفظ في قاعدة البيانات بنجاح']);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ أثناء الحفظ في قاعدة البيانات']);
}
