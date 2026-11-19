<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class GermanLangModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getGermanData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Deutsch-Sprachkurse',
                'levels_title'    => 'Verfügbare Sprachniveaus (nach GER / CEFR)',
                'features_title'  => 'Vorteile unserer Sprachkurse',
                'tips_title'      => 'Tipps für ein erfolgreiches Studium auf Deutsch',
            ],
            'en' => [
                'page_breadcrumb' => 'German Language Courses',
                'levels_title'    => 'Available Levels (According to CEFR)',
                'features_title'  => 'Features of Our Courses',
                'tips_title'      => 'Tips for Succeeding in Studying in German',
            ],
            'ar' => [
                'page_breadcrumb' => 'دورات اللغة الألمانية',
                'levels_title'    => 'المستويات المتوفرة (طبقًا ل CEFR)',
                'features_title'  => 'مميزات دوراتنا',
                'tips_title'      => 'نصائح للنجاح في الدراسة بالألمانية',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'germanlang_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('GermanLangModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('GermanLangModel Error: ' . $e->getMessage());
        }

        $ger_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $ger_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $ger_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $ger_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $ger_raw = $pageRawData['ar'];
        } else {
            $ger_raw = $pageRawData;
        }

        $rawLevels = is_array($ger_raw['levels_section'] ?? null) ? $ger_raw['levels_section'] : [];
        $rawFeat   = is_array($ger_raw['features_section'] ?? null) ? $ger_raw['features_section'] : [];
        $rawTips   = is_array($ger_raw['tips_section'] ?? null) ? $ger_raw['tips_section'] : [];

        $german_data = [
            'page_breadcrumb'     => $ger_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $ger_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $ger_raw['hero_img'] ?? 'assets/img/education/servicesimg4.png',
            'hero_position'       => $ger_raw['hero_position'] ?? 'center center',
            'main_title'          => $ger_raw['main_title'] ?? '',
            'main_desc'           => $ger_raw['main_desc'] ?? '',
            'levels_section'      => [
                'title'       => (string)($rawLevels['title'] ?? $fallbackLabels['levels_title']),
                'levels_list' => is_array($rawLevels['levels_list'] ?? null) ? $rawLevels['levels_list'] : [],
            ],
            'features_section'    => [
                'title'         => (string)($rawFeat['title'] ?? $fallbackLabels['features_title']),
                'features_list' => is_array($rawFeat['features_list'] ?? null) ? $rawFeat['features_list'] : [],
            ],
            'tips_section'        => [
                'title'     => (string)($rawTips['title'] ?? $fallbackLabels['tips_title']),
                'tips_list' => is_array($rawTips['tips_list'] ?? null) ? $rawTips['tips_list'] : [],
            ],
        ];

        return [
            'german_data'     => $german_data,
            'germanlang_page' => $german_data,
        ];
    }
}
