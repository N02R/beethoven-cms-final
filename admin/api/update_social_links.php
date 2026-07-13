<?php
// إرجاع الاستجابة بصيغة JSON دائماً
header('Content-Type: application/json');

// فرضاً أن هناك نظام جلسات للتحقق من الأدمن
session_start();
// يمكنك تفعيل التحقق الخاص بكِ هنا (مثال):
// if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
//     echo json_encode(['success' => false, 'error' => 'غير مصرح لك بالوصول']);
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. مسار ملف الـ JSON الذي تخزنين فيه الإعدادات (عدلي المسار حسب هيكلة مشروعك)
    $json_file = '../../config/site_settings.json'; 
    
    // قراءة البيانات الحالية إذا كان الملف موجوداً
    $settings = [];
    if (file_exists($json_file)) {
        $settings = json_decode(file_get_contents($json_file), true) ?? [];
    }

    // 2. استقبال مصفوفة الأيقونات المرسلة من الفورم
    $posted_social = $_POST['social'] ?? [];
    $updated_social_links = [];
    $counter = 1;

    // المجلد الذي سيتم حفظ أيقونات التواصل الاجتماعي المرفوعة فيه
    $upload_dir = '../../assets/img/socialicons/';
    // تأكدي من إنشاء المجلد ومنحه صلاحيات الكتابة
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    foreach ($posted_social as $key => $item) {
        $name = strip_tags($item['name'] ?? '');
        $url = strip_tags($item['url'] ?? '#');
        $img_path = $item['old_img'] ?? 'assets/img/socialicons/default.png'; // المسار الافتراضي القديم

        // 3. التحقق مما إذا كان هناك ملف صورة مرفوع لهذا السطر تحديداً
        $file_input_name = "social_img_" . $key;
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
            
            $file_tmp = $_FILES[$file_input_name]['tmp_name'];
            $file_name = $_FILES[$file_input_name]['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // الصيغ المسموحة لأيقونات السوشيال ميديا
            $allowed_exts = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
            
            if (in_array($file_ext, $allowed_exts)) {
                // توليد اسم فريد للصورة لمنع التداخل
                $new_file_name = 'icon_' . uniqid() . '.' . $file_ext;
                $destination = $upload_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $destination)) {
                    // تخزين المسار النسبي الذي سيقرأه الهيدر في الفرونت إند
                    $img_path = 'assets/img/socialicons/' . $new_file_name;
                }
            }
        }

        // بناء العنصر النظيف بعد المعالجة
        if (!empty($name)) {
            $updated_social_links[] = [
                'id' => $counter++,
                'name' => $name,
                'img' => $img_path,
                'url' => $url
            ];
        }
    }

    // 4. حفظ المصفوفة الجديدة داخل الإعدادات العامة للموقع
    $settings['social_links'] = $updated_social_links;

    // كتابة التحديثات داخل ملف الـ JSON
    if (file_put_contents($json_file, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'فشل الكتابة وحفظ البيانات في ملف الإعدادات']);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'طلب غير صالح']);
