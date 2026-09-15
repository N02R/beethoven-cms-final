<?php
declare(strict_types=1);

use App\Config\Database;

if (!function_exists('get_setting')) {
    /**
     * جلب قيمة إعداد معين مع دعم تلقائي للغات (de, en, ar) والـ JSON متعدد اللغات
     */
    function get_setting(string $key, mixed $default = ''): mixed {
        try {
            $db = Database::getConnection();
            $currentLang = get_current_lang(); // جلب اللغة الحالية (de, en, ar)
            
            // 1. محاولة البحث عن المفتاح مضافاً إليه اللغة (مثلاً: site_title_ar)
            $langKey = $key . '_' . $currentLang;
            $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ? LIMIT 1");
            $stmt->execute([$langKey]);
            $result = $stmt->fetch();

            $value = null;

            // 2. إذا وجدنا قيمة خاصة باللغة، نرجعها
            if ($result && isset($result['setting_value']) && $result['setting_value'] !== '') {
                $value = $result['setting_value'];
            } else {
                // 3. إن لم يوجد، نحاول جلب المفتاح الأساسي الافتراضي
                $stmt->execute([$key]);
                $result = $stmt->fetch();
                
                if ($result && isset($result['setting_value'])) {
                    $value = $result['setting_value'];
                } else {
                    return $default;
                }
            }
            
            // محاولة فك الـ JSON
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // إذا كان الـ JSON يحتوي على مفاتيح للغات (ar, en, de)، نقوم باختيار اللغة الحالية تلقائياً
                if (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de'])) {
                    // إرجاع بيانات اللغة الحالية، وإذا لم تكن موجودة نرجع العربية كاحتياطي، وإذا لم تتوفر نرجع الـ decoded كاملاً
                    return $decoded[$currentLang] ?? ($decoded['ar'] ?? $decoded);
                }
                
                return $decoded;
            }
            
            return $value;

        } catch (\Exception $e) {
            // في حال حدوث أي خطأ في قاعدة البيانات
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

        // 2. المسار الفيزيائي الصحيح للملف على السيرفر (داخل public/assets/uploads)
        $uploadFilePath = __DIR__ . '/../../public/assets/uploads/' . $filename;

        // 3. التحقق من وجود الملف داخل مجلد assets/uploads
        if (file_exists($uploadFilePath)) {
            return '/assets/uploads/' . $filename;
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

if (!function_exists('get_current_lang')) {
    /**
     * جلب اللغة الحالية للنظام بناءً على الرابط أو الجلسة
     */
    function get_current_lang(): string {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $segments = explode('/', trim(parse_url($uri, PHP_URL_PATH) ?? '', '/'));
        
        // التحقق إذا كان الجزء الأول من الرابط يمثل لغة معتمدة
        foreach ($segments as $segment) {
            if (in_array($segment, ['ar', 'en', 'de'], true)) {
                return $segment;
            }
        }
        
        return $_SESSION['site_lang'] ?? 'de';
    }
}

if (!function_exists('__')) {
    /**
     * دالة ترجمة النصوص الثابتة بناءً على لغة الجلسة الحالية
     */
    function __(string $key, string $default = ''): string {
        static $langData = [];
        $currentLang = get_current_lang();

        // تحميل ملف اللغة مرة واحدة فقط لكل طلب (Performance Optimization)
        if (!isset($langData[$currentLang])) {
            $langFile = __DIR__ . '/../Lang/' . $currentLang . '.php';
            if (file_exists($langFile)) {
                $langData[$currentLang] = require $langFile;
            } else {
                $langData[$currentLang] = [];
            }
        }

        // إرجاع الترجمة إن وجدت، أو النص الافتراضي أو المفتاح نفسه
        return $langData[$currentLang][$key] ?? ($default !== '' ? $default : $key);
    }
}
