<?php
declare(strict_types=1);

use App\Config\Database;
use App\Models\SiteModel;

if (!function_exists('get_current_lang')) {
    /**
     * جلب اللغة الحالية للنظام بأمان وفق القائمة البيضاء المعتمدة
     */
    function get_current_lang(): string
    {
        $lang = $_SESSION['site_lang'] ?? 'de';
        return in_array($lang, ['de', 'en', 'ar'], true) ? $lang : 'de';
    }
}

if (!function_exists('get_setting')) {
    /**
     * جلب قيمة إعداد معين بالاعتماد على الكاش المركزي في SiteModel لمنع تكرار استعلامات SQL
     */
    function get_setting(string $key, mixed $default = ''): mixed
    {
        static $settingsCache = [];
        $currentLang = get_current_lang();
        $cacheKey = $key . '_' . $currentLang;

        if (array_key_exists($cacheKey, $settingsCache)) {
            return $settingsCache[$cacheKey];
        }

        try {
            // قراءة الإعدادات من الكاش المركزي (استعلام واحد فقط لكل الطلب)
            if (class_exists(SiteModel::class)) {
                $allSettings = SiteModel::getSettings();
            } else {
                $db = Database::getConnection();
                $stmt = $db->query("SELECT setting_key, setting_value FROM global_settings");
                $allSettings = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR) ?: [];
            }

            $langKey = $key . '_' . $currentLang;
            if (isset($allSettings[$langKey]) && $allSettings[$langKey] !== '') {
                $value = $allSettings[$langKey];
            } elseif (array_key_exists($key, $allSettings)) {
                $value = $allSettings[$key];
            } else {
                return $settingsCache[$cacheKey] = $default;
            }

            // فك الـ JSON بأمان تام واستخراج اللغة المطلوبة
            if (is_string($value)) {
                $trimmed = trim($value);
                if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
                    $decoded = json_decode($trimmed, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        if (isset($decoded['de']) || isset($decoded['en']) || isset($decoded['ar'])) {
                            return $settingsCache[$cacheKey] = ($decoded[$currentLang] ?? ($decoded['de'] ?? ($decoded['en'] ?? ($decoded['ar'] ?? $default))));
                        }
                        return $settingsCache[$cacheKey] = $decoded;
                    }
                }
            }

            return $settingsCache[$cacheKey] = $value;
        } catch (\Throwable $e) {
            error_log('Get Setting Error: ' . $e->getMessage());
        }

        return $settingsCache[$cacheKey] = $default;
    }
}

if (!function_exists('get_image_url')) {
    /**
     * تحويل مسار الصورة مع التخزين المؤقت وفحص الوجود الفعلي على القرص وحماية الروابط
     */
    function get_image_url(?string $path, string $default = '/assets/img/logo.png'): string
    {
        if ($path === null || trim($path) === '') {
            return $default;
        }

        $cleanPath = trim($path);

        // حظر البروتوكولات الخطرة ومحاولات اجتياز المسارات
        if (preg_match('/^(javascript|data|vbscript):/i', $cleanPath) || str_contains($cleanPath, '..')) {
            return $default;
        }

        // دعم الروابط الخارجية الموثوقة (http/https)
        if (str_starts_with($cleanPath, 'http://') || str_starts_with($cleanPath, 'https://')) {
            return filter_var($cleanPath, FILTER_VALIDATE_URL) ? $cleanPath : $default;
        }

        static $imageCache = [];
        if (isset($imageCache[$cleanPath])) {
            return $imageCache[$cleanPath];
        }

        $publicRoot = realpath(__DIR__ . '/../../public');
        if ($publicRoot === false) {
            return $default;
        }

        // 1. الفحص داخل مجلد uploads
        $filename = basename($cleanPath);
        $uploadFilePath = $publicRoot . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $filename;
        if ($filename !== '' && is_file($uploadFilePath)) {
            return $imageCache[$cleanPath] = '/assets/uploads/' . $filename;
        }

        // 2. الفحص داخل المسار الثابت في public
        $relativePath = ltrim($cleanPath, '/\\');
        $staticFilePath = $publicRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
        if (is_file($staticFilePath)) {
            return $imageCache[$cleanPath] = '/' . str_replace('\\', '/', $relativePath);
        }

        return $imageCache[$cleanPath] = $default;
    }
}

if (!function_exists('__')) {
    /**
     * دالة ترجمة النصوص الثابتة بناءً على لغة الجلسة الحالية مع حماية الأنواع
     */
    function __(string $key, string $default = ''): string
    {
        static $langData = [];
        $currentLang = get_current_lang();

        if (!isset($langData[$currentLang])) {
            $langFile = __DIR__ . '/../Lang/' . $currentLang . '.php';
            if (!is_file($langFile)) {
                $langFile = __DIR__ . '/../lang/' . $currentLang . '.php';
            }
            $loaded = is_file($langFile) ? require $langFile : [];
            $langData[$currentLang] = is_array($loaded) ? $loaded : [];
        }

        $translated = $langData[$currentLang][$key] ?? null;
        if (is_string($translated) && $translated !== '') {
            return $translated;
        }

        return $default !== '' ? $default : $key;
    }
}

if (!function_exists('safe_admin_string')) {
    /**
     * معالجة وحماية النصوص والمصفوفات داخل حقول لوحة التحكم لمنع أخطاء تحويل البيانات
     */
    function safe_admin_string(mixed $val, ?string $current_lang = null): string
    {
        $lang = ($current_lang !== null && in_array($current_lang, ['de', 'en', 'ar'], true))
            ? $current_lang
            : get_current_lang();

        if (is_array($val)) {
            $picked = $val[$lang] ?? ($val['de'] ?? ($val['en'] ?? ($val['ar'] ?? reset($val))));
            return is_scalar($picked) ? (string)$picked : '';
        }

        if (is_string($val)) {
            $trimmed = trim($val);
            if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
                $decoded = json_decode($trimmed, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $picked = $decoded[$lang] ?? ($decoded['de'] ?? ($decoded['en'] ?? ($decoded['ar'] ?? reset($decoded))));
                    return is_scalar($picked) ? (string)$picked : '';
                }
            }
        }

        return is_scalar($val) ? (string)$val : '';
    }
}
