<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class SiteModel {
    private static ?array $cachedSettings = null;
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    /**
     * جلب جميع الإعدادات العامة من global_settings مع التخزين المؤقت على مستوى الطلب (Request Caching)
     */
    public static function getSettings(bool $forceRefresh = false): array {
        if (!$forceRefresh && self::$cachedSettings !== null) {
            return self::$cachedSettings;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM global_settings");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $settings = [];
        foreach ($results as $row) {
            if (isset($row['setting_key'])) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }

        self::$cachedSettings = $settings;
        return $settings;
    }

    /**
     * تحديد اللغة الحالية بأمان وفق القائمة البيضاء المعتمدة
     */
    public static function resolveLang(?string $lang = null): string {
        $candidate = $lang ?? ($_SESSION['site_lang'] ?? 'de');
        return in_array($candidate, self::SUPPORTED_LANGS, true) ? $candidate : 'de';
    }

    /**
     * دالة موحدة لجلب وتجهيز بيانات الهيدر والفوتر والإعدادات العامة لكل الموقع مع دعم اللغات
     */
    public static function getGlobalData(?string $langOverride = null): array {
        $settings = self::getSettings();
        $lang = self::resolveLang($langOverride);

        $getLangData = function(string $key, mixed $default = []) use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $raw = $settings[$key];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            
            if (is_array($decoded) && (isset($decoded['de']) || isset($decoded['en']) || isset($decoded['ar']))) {
                return $decoded[$lang] ?? ($decoded['de'] ?? ($decoded['en'] ?? ($decoded['ar'] ?? $default)));
            }
            
            return is_array($decoded) ? $decoded : $default;
        };

        $getMultiLang = function(string $settingKey, string $default) use ($settings, $lang): string {
            if (!isset($settings[$settingKey])) {
                return $default;
            }
            $raw = $settings[$settingKey];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            if (is_array($decoded)) {
                $val = $decoded[$lang] ?? ($decoded['de'] ?? ($decoded['en'] ?? ($decoded['ar'] ?? $default)));
                return is_string($val) ? $val : $default;
            }
            return is_string($raw) ? $raw : $default;
        };
        
        // استخراج الشعار مع دعم اللغات
        $rawLogo = $settings['site_logo_path'] ?? ($settings['site_logo'] ?? '');
        $decodedLogo = is_string($rawLogo) ? json_decode($rawLogo, true) : $rawLogo;
        if (is_array($decodedLogo) && (isset($decodedLogo['de']) || isset($decodedLogo['en']) || isset($decodedLogo['ar']))) {
            $site_logo_path = $decodedLogo[$lang] ?? ($decodedLogo['de'] ?? ($decodedLogo['en'] ?? ($decodedLogo['ar'] ?? '')));
        } else {
            $site_logo_path = is_string($rawLogo) ? $rawLogo : '';
        }

        // معالجة بيانات الدليل الشامل الموحدة (من المفتاح المركزي 'guide')
        $guideBlock    = $getLangData('guide', []);
        $guide_title   = $guideBlock['title'] ?? '';
        $guide_desc    = $guideBlock['desc'] ?? '';
        $read_more_btn = $guideBlock['read_more_btn'] ?? '';
        $guide_items   = is_array($guideBlock['items'] ?? null) ? $guideBlock['items'] : [];

        // معالجة بيانات الأسئلة الشائعة الموحدة (من المفتاح المركزي 'faq')
        $faqBlock  = $getLangData('faq', []);
        $faq_title = $faqBlock['title'] ?? '';
        $faq_items = is_array($faqBlock['items'] ?? null) ? $faqBlock['items'] : [];

        // معالجة بيانات الفوتر الموحدة (من المفتاح المركزي الشامل 'footer')
        $footerBlock       = $getLangData('footer', []);
        $consult_title     = $footerBlock['consult_title'] ?? '';
        $consult_desc      = $footerBlock['consult_desc'] ?? '';
        $footer_desc       = $footerBlock['footer_desc'] ?? '';
        $footer_col2_title = $footerBlock['col2_title'] ?? '';
        $footer_col3_title = $footerBlock['col3_title'] ?? '';
        $footer_col3_links = is_array($footerBlock['col3_items'] ?? null) ? $footerBlock['col3_items'] : [];

        return [
            'site_title'             => $getMultiLang('site_title', 'Beethoven City Services'),
            'site_email'             => $settings['site_email'] ?? '',
            'site_logo_path'         => $site_logo_path,
            'site_logo'              => $site_logo_path,
            'social_links'           => isset($settings['social_links']) && is_string($settings['social_links']) ? (json_decode($settings['social_links'], true) ?? []) : [],
            'menu_links'             => $getLangData('menu_links', []),
            'languages'              => isset($settings['languages']) && is_string($settings['languages']) ? (json_decode($settings['languages'], true) ?? []) : [],
            'announcement'           => $getLangData('announcement', []),
            
            'services_section_title' => $getMultiLang('services_section_title', ''),
            'services_section_desc'  => $getMultiLang('services_section_desc', ''),
            'services'               => $getLangData('services', []),

            // بيانات الفوتر الموحدة
            'footer'                 => $settings['footer'] ?? '{}',
            'consult_title'          => $consult_title,
            'consult_desc'           => $consult_desc,
            'footer_desc'            => $footer_desc,
            'footer_col2_title'      => $footer_col2_title,
            'footer_col3_title'      => $footer_col3_title,
            'footer_col3_links'      => $footer_col3_links,
            
            // بيانات الدليل الشامل الموحدة
            'guide'                  => $settings['guide'] ?? '{}',
            'guide_title'            => $guide_title,
            'guide_desc'             => $guide_desc,
            'read_more_btn'          => $read_more_btn,
            'guide_items'            => $guide_items,

            // بيانات الأسئلة الشائعة الموحدة
            'faq'                    => $settings['faq'] ?? '{}',
            'faq_title'              => $faq_title,
            'faq_items'              => $faq_items,
        ];
    }

    public static function updateSettings(array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO global_settings (setting_key, setting_value) 
            VALUES (:k, :v) 
            ON DUPLICATE KEY UPDATE setting_value = :v_update
        ");

        $success = true;
        foreach ($data as $key => $value) {
            if (in_array($key, ['csrf_token', 'action'], true)) {
                continue;
            }
            $res = $stmt->execute([
                'k'        => $key,
                'v'        => $value,
                'v_update' => $value
            ]);
            if (!$res) {
                $success = false;
            }
        }

        // تفريغ الذاكرة المؤقتة بعد التحديث
        self::$cachedSettings = null;
        return $success;
    }
}
