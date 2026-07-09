<?php
/**
 * ملف جلب البيانات (API)
 * المسار: public/api/get_data.php
 */

// استدعاء ملف الـ autoload للوصول للكلاسات تلقائياً
// نصعد مستويين للوصول للمجلد الرئيسي ثم الدخول لمجلد app
require_once '../../app/autoload.php';

use App\Core\Database;
use App\Core\CMS;

// إعداد نوع الاستجابة كـ JSON
header('Content-Type: application/json');

try {
    // جلب المتغيرات من الطلب
    $page    = $_GET['page']    ?? '';
    $section = $_GET['section'] ?? '';
    $field   = $_GET['field']   ?? '';

    // التحقق من وجود المتغيرات الأساسية
    if (empty($page) || empty($section) || empty($field)) {
        throw new Exception("بيانات غير مكتملة");
    }

    // جلب البيانات من قاعدة البيانات باستخدام دالة CMS
    $data = CMS::get($page, $section, $field, false, false);

    // إذا كانت النتيجة مصفوفة (Array) نرسلها كما هي، 
    // أما إذا كانت قيمة نصية نضعها داخل مفتاح 'value'
    if (is_array($data)) {
        echo json_encode($data);
    } else {
        echo json_encode(['value' => $data]);
    }

} catch (Exception $e) {
    // في حال حدوث خطأ، نرسل رسالة خطأ واضحة
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
