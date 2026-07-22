<?php
// save_data.php

// تطبيق إعدادات أمان الجلسات والكوكيز الحديثة
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// تأكد من صلاحية الأدمن بشكل صارم وآمن
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'غير مصرح'], JSON_UNESCAPED_UNICODE);
    exit;
}

// التحقق من طريقة الطلب (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طلب غير صالح'], JSON_UNESCAPED_UNICODE);
    exit;
}

$config_file = 'announcement_config.json';

// التحقق من وجود ملف التكوين وقراءته بأمان
if (!file_exists($config_file)) {
    $data = ['announcement' => []];
} else {
    $json_content = file_get_contents($config_file);
    $data = json_decode($json_content, true);
    if (!is_array($data)) {
        $data = ['announcement' => []];
    }
}

$action = $_POST['action'] ?? '';

// تحديث الإعلان
if ($action === 'update_announcement') {
    // تنظيف وتطهير المدخلات النصية لمنع ثغرات XSS
    $data['announcement']['type']              = trim($_POST['type'] ?? '');
    $data['announcement']['announcement_text'] = trim($_POST['announcement_text'] ?? '');
    
    // التحقق من صحة الأكواد اللونية (Hex Colors) للأمان
    $bg_color   = trim($_POST['bg_color'] ?? '');
    $text_color = trim($_POST['text_color'] ?? '');
    $data['announcement']['bg_color']   = preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $bg_color) ? $bg_color : '#ffffff';
    $data['announcement']['text_color'] = preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $text_color) ? $text_color : '#000000';

    // التحقق من صحة الرابط المدخل
    $link = trim($_POST['link'] ?? '');
    $data['announcement']['link'] = ($link !== '' && $link !== '#') ? filter_var($link, FILTER_SANITIZE_URL) : '#';
    
    $data['announcement']['open_new_tab'] = isset($_POST['open_new_tab']) ? '1' : '0';

    // معالجة رفع الصور بشكل آمن للغاية
    if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['ad_image']['tmp_name'];
        $file_size = $_FILES['ad_image']['size'];
        
        // التحقق من الحجم (مثال: أقصى حد 5 ميجابايت)
        if ($file_size <= 5 * 1024 * 1024) {
            // التحقق الفعلي من نوع الملف عبر فحص محتواه (MIME Type) وليس فقط الامتداد
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file_tmp);
            finfo_close($finfo);

            $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            
            if (in_array($mime_type, $allowed_mimes, true)) {
                // تحديد الامتداد بناءً على نوع الـ MIME المعتمد
                $extensions = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp',
                    'image/gif'  => 'gif'
                ];
                $ext = $extensions[$mime_type] ?? 'jpg';
                
                // توليد اسم عشوائي وآمن تماماً للملف لمنع التلاعب بالمسارات
                $img_name = 'assets/img/ad_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                
                // التأكد من وجود المجلد أو إنشاؤه إن لم يكن موجوداً
                $upload_dir = dirname($img_name);
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                if (move_uploaded_file($file_tmp, $img_name)) {
                    $data['announcement']['announcement_image_path'] = $img_name;
                }
            }
        }
    }
}

// حفظ الملف بصيغة JSON آمنة
$json_encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if ($json_encoded !== false && file_put_contents($config_file, $json_encoded, LOCK_EX) !== false) {
    echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['success' => false, 'message' => 'فشل الحفظ'], JSON_UNESCAPED_UNICODE);
}
?>
