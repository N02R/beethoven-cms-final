<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class EnglishLangModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getEnglishData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Englischsprachige Studiengänge',
                'who_title'       => 'Wer kann von diesen Programmen profitieren',
                'who_subtitle'    => 'Alle, die folgende Voraussetzungen erfüllen:',
                'lang_title'      => 'Allgemeine Sprachanforderungen',
                'note_highlight'  => 'Hinweis:',
            ],
            'en' => [
                'page_breadcrumb' => 'English-Taught Study Programs',
                'who_title'       => 'Who Can Benefit from These Programs',
                'who_subtitle'    => 'Anyone who meets the following requirements:',
                'lang_title'      => 'General Language Requirements',
                'note_highlight'  => 'Note:',
            ],
            'ar' => [
                'page_breadcrumb' => 'برامج دراسية باللغة الإنجليزية',
                'who_title'       => 'من يمكنه الاستفادة من هذه البرامج',
                'who_subtitle'    => 'كل من يستوفي الشروط التالية:',
                'lang_title'      => 'متطلبات اللغة بشكل عام',
                'note_highlight'  => 'ملاحظة:',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'englishlang_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('EnglishLangModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('EnglishLangModel Error: ' . $e->getMessage());
        }

        $english_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $english_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $english_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $english_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $english_raw = $pageRawData['ar'];
        } else {
            $english_raw = $pageRawData;
        }

        $english_data = [
            'page_breadcrumb'     => $english_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $english_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $english_raw['hero_img'] ?? 'assets/img/education/servicesimg5.png',
            'hero_position'       => $english_raw['hero_position'] ?? 'center center',
            'main_title'          => $english_raw['main_title'] ?? '',
            'main_desc'           => $english_raw['main_desc'] ?? '',
            'who_title'           => $english_raw['who_title'] ?? $fallbackLabels['who_title'],
            'who_subtitle'        => $english_raw['who_subtitle'] ?? $fallbackLabels['who_subtitle'],
            'who_items'           => is_array($english_raw['who_items'] ?? null) ? $english_raw['who_items'] : [],
            'lang_title'          => $english_raw['lang_title'] ?? $fallbackLabels['lang_title'],
            'lang_points'         => is_array($english_raw['lang_points'] ?? null) ? $english_raw['lang_points'] : [],
            'note_highlight'      => $english_raw['note_highlight'] ?? $fallbackLabels['note_highlight'],
            'note_text'           => $english_raw['note_text'] ?? '',
        ];

        return [
            'english_data'     => $english_data,
            'englishlang_page' => $english_data,
        ];
    }
}
