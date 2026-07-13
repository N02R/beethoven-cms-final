<?php
// إرجاع الاستجابة بصيغة JSON دائماً
header('Content-Type: application/json');

// 1. بدء الجلسة بأمان والتحقق من صلاحيات الأدمن بناءً على نظام الجلسات الخاص بكِ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// جدار حماية صارم: التحقق من صلاحيات الأدمن بنفس منطق الهيدر
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    echo json_encode(['success' => false, 'error' => 'غير مصرح لك بالوصول، يجب تسجيل الدخول كمسؤول.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ✨ التعديل الجوهري: مسار ملف الـ JSON الموحد المشرّح في الهيدر الخاص بكِ
    // الخروج خطوتين من مجلد admin/api/ للوصول إلى الجذر الرئيسي حيث يوجد الملف
    $json_file = '../../announcement_config.json'; 
    
    // قراءة البيانات الحالية إذا كان الملف موجوداً لمنع حذف الإعلانات أو اللوجو أثناء حفظ السوشيال ميديا
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
    
    // تأكدي من إنشاء المجلد ومنحه صلاحيات الكتابة إن لم يكن موجوداً
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    foreach ($posted_social as $key => $item) {
        $name = strip_tags($item['name'] ?? '');
        $url = strip_tags($item['url'] ?? '#');
        // الحفاظ على المسار القديم للأيقونة إذا لم يقم المسؤول برفع صورة جديدة
        $img_path = !empty($item['old_img']) ? $item['old_img'] : 'assets/img/socialicons/default.png'; 

        // 3. التحقق مما إذا كان هناك ملف صورة مرفوع لهذا السطر تحديداً
        $file_input_name = "social_img_" . $key;
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
            
            $file_tmp = $_FILES[$file_input_name]['tmp_name'];
            $file_name = $_FILES[$file_input_name]['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // الصيغ المدعومة ذات الأداء العالي والآمنة
            $allowed_exts = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
            
            if (in_array($file_ext, $allowed_exts)) {
                // توليد اسم فريد عشوائي للأيقونة منعاً لتداخل الأسماء
                $new_file_name = 'icon_' . uniqid() . '.' . $file_ext;
                $destination = $upload_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $destination)) {
                    // المسار النسبي النظيف الذي سيتغذى عليه الهيدر مباشرة
                    $img_path = 'assets/img/socialicons/' . $new_file_name;
                }
            }
        }

        // بناء العنصر النظيف وإضافته للمصفوفة المحدثة
        if (!empty($name)) {
            $updated_social_links[] = [
                'id' => $counter++,
                'name' => $name,
                'img' => $img_path,
                'url' => $url
            ];
        }
    }

    // 4. حفظ المصفوفة الجديدة داخل المفتاح المتوافق مع فرز الهيدر (social_links)
    $settings['social_links'] = $updated_social_links;

    // كتابة التحديثات الشاملة بترميز يدعم اللغة العربية بشكل سليم دون تشفير (JSON_UNESCAPED_UNICODE)
    if (file_put_contents($json_file, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'فشل نظام الملفات في الكتابة وحفظ البيانات داخل announcement_config.json']);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'طلب غير صالح أو لم يتم إرسال البيانات عبر POST']);
