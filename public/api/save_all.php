<?php
/**
 * ملف حفظ التعديلات (API)
 * المسار: public/api/save_all.php
 */

require_once '../../app/autoload.php';

use App\Core\CMS;

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("طريقة طلب غير صحيحة");
    }

    $page    = $_POST['page']    ?? '';
    $section = $_POST['section'] ?? '';
    $field   = $_POST['field']   ?? '';

    // 1. التعامل مع رفع الصور
    if (isset($_FILES['new_file']) && $_FILES['new_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/img/';
        $fileName = basename($_FILES['new_file']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['new_file']['tmp_name'], $uploadPath)) {
            // تحديث المسار في القاعدة
            CMS::update($page, $section, $field, 'assets/img/' . $fileName);
            echo "تم تحديث الصورة بنجاح!";
        } else {
            throw new Exception("فشل في رفع الصورة");
        }
    } 
    // 2. التعامل مع النصوص أو روابط السوشيال
    else {
        // إذا كان حقل محتوى نصي عادي
        if (isset($_POST['content'])) {
            CMS::update($page, $section, $field, $_POST['content']);
        } 
        // إذا كان حقل مصفوفة روابط (سوشيال ميديا)
        elseif (isset($_POST['social'])) {
            CMS::update($page, $section, $field, $_POST['social']);
        }
        echo "تم حفظ التعديلات بنجاح!";
    }

} catch (Exception $e) {
    http_response_code(400);
    echo "خطأ: " . $e->getMessage();
}
