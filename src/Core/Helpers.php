<?php
declare(strict_types=1);

use App\Config\Database;

if (!function_exists('get_current_lang')) {
    /**
     * جلب اللغة الحالية للنظام مباشرة من الجلسة (الاعتماد على المعالجة المركزية للراوتر)
     */
    function get_current_lang(): string {
        return $_SESSION['site_lang'] ?? 'de';
    }
}

if (!function_exists('get_setting')) {
    /**
     * جلب قيمة إعداد معين مع دعم التخزين المؤقت (Request-Level Caching) لمنع تكرار استعلامات قاعدة البيانات
     */
    function get_setting(string $key, mixed $default = ''): mixed {
        static $settingsCache = [];
        $currentLang = get_current_lang();
        $cacheKey = $key . '_' . $currentLang;

        if (array_key_exists($cacheKey, $settingsCache)) {
            return $settingsCache[$cacheKey];
        }

        try {
            $db = Database::getConnection();
            
            // 1. محاولة البحث عن المفتاح مضافاً إليه اللغة
            $langKey = $key . '_' . $currentLang;
            $stmt = $db->prepare("SELECT setting_value FROM global_settings WHERE setting_key = ? LIMIT 1");
            $stmt->execute([$langKey]);
            $result = $stmt->fetch();

            $value = null;

            if ($result && isset($result['setting_value']) && $result['setting_value'] !== '') {
                $value = $result['setting_value'];
            } else {
                // 2. جلب المفتاح الأساسي الافتراضي
                $stmt->execute([$key]);
                $result = $stmt->fetch();
                
                if ($result && isset($result['setting_value'])) {
                    $value = $result['setting_value'];
                } else {
                    return $settingsCache[$cacheKey] = $default;
                }
            }
            
            // 🌟 فك الـ JSON بأمان تام مع التأكد أنه نص (String) لمنع أي خطأ نوع بيانات
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    if (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de'])) {
                        return $settingsCache[$cacheKey] = ($decoded[$currentLang] ?? ($decoded['ar'] ?? $decoded));
                    }
                    return $settingsCache[$cacheKey] = $decoded;
                }
            }
            
            return $settingsCache[$cacheKey] = $value;

        } catch (\Throwable $e) {
            error_log("Get Setting Error: " . $e->getMessage());
        }
        
        return $settingsCache[$cacheKey] = $default;
    }
}

if (!function_exists('get_image_url')) {
    /**
     * تحويل مسار الصورة مع تطبيق التخزين المؤقت (Static Caching) وفحص القرص الصلب أو الروابط الخارجية
     */
    function get_image_url(?string $path, string $default = '/assets/img/logo.png'): string
    {
        if (empty($path)) {
            return $default;
        }

        // دعم الروابط الخارجية مباشرة دون فحص محلي
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        static $imageCache = [];
        if (isset($imageCache[$path])) {
            return $imageCache[$path];
        }

        $filename = basename($path);
        $uploadFilePath = __DIR__ . '/../../public/assets/uploads/' . $filename;

        if (file_exists($uploadFilePath)) {
            return $imageCache[$path] = '/assets/uploads/' . $filename;
        }

        $staticFilePath = __DIR__ . '/../../public/' . ltrim($path, '/');
        if (file_exists($staticFilePath)) {
            return $imageCache[$path] = '/' . ltrim($path, '/');
        }

        return $imageCache[$path] = $default;
    }
}

if (!function_exists('__')) {
    /**
     * دالة ترجمة النصوص الثابتة بناءً على لغة الجلسة الحالية
     */
    function __(string $key, string $default = ''): string {
        static $langData = [];
        $currentLang = get_current_lang();

        if (!isset($langData[$currentLang])) {
            $langFile = __DIR__ . '/../Lang/' . $currentLang . '.php';
            if (file_exists($langFile)) {
                $langData[$currentLang] = require $langFile;
            } else {
                $langData[$currentLang] = [];
            }
        }

        return $langData[$currentLang][$key] ?? ($default !== '' ? $default : $key);
    }
}
