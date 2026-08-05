<?php
declare(strict_types=1);

use App\Config\Database;

if (!function_exists('get_setting')) {
    /**
     * جلب قيمة إعداد معين من قاعدة البيانات (تدعم النصوص والمصفوفات)
     */
    function get_setting(string $key, mixed $default = ''): mixed {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ? LIMIT 1");
            $stmt->execute([$key]);
            $result = $stmt->fetch();

            if ($result && isset($result['setting_value'])) {
                $value = $result['setting_value'];
                
                // محاولة فك الـ JSON إذا كانت القيمة مخزنة كمصفوفة
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
                
                return $value;
            }
        } catch (\Exception $e) {
            // في حال حدوث أي خطأ
        }
        
        return $default;
    }
}

if (!function_exists('get_image_url')) {
    /**
     * تحويل أي مسار صورة مخزن في قاعدة البيانات إلى رابط يعمل بشكل صحيح من مجلد uploads أو المجلدات الثابتة.
     *
     * @param string|null $path المسار المخزن في قاعدة البيانات
     * @param string $default الصورة الافتراضية في حال عدم وجود الصورة
     * @return string
     */
    function get_image_url(?string $path, string $default = '/assets/img/placeholder.png'): string
    {
        if (empty($path)) {
            return $default;
        }

        // 1. استخراج اسم الملف فقط لو كان المسار يحتوي على مجلدات فرعية
        $filename = basename($path);

        // 2. المسار الفيزيائي للملف على السيرفر (داخل public/uploads)
        $uploadFilePath = __DIR__ . '/../../public/uploads/' . $filename;

        // 3. التحقق من وجود الملف داخل مجلد uploads
        if (file_exists($uploadFilePath)) {
            return '/uploads/' . $filename;
        }

        // 4. إذا كان المسار القديم يشير إلى assets/img وموجود فعلياً على السيرفر
        $staticFilePath = __DIR__ . '/../../public/' . ltrim($path, '/');
        if (file_exists($staticFilePath)) {
            return '/' . ltrim($path, '/');
        }

        // 5. في حال عدم العثور على الملف نهائياً، إرجاع الصورة الافتراضية
        return $default;
    }
}
