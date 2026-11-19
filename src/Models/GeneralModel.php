<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class GeneralModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getGeneralData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Visabestimmungen',
                'notes_title'     => 'Hinweis !!',
            ],
            'en' => [
                'page_breadcrumb' => 'Visa Requirements',
                'notes_title'     => 'Note !!',
            ],
            'ar' => [
                'page_breadcrumb' => 'متطلبات التأشيرة',
                'notes_title'     => 'ملاحظة !!',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'general_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('GeneralModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('GeneralModel Error: ' . $e->getMessage());
        }

        $gen_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $gen_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $gen_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $gen_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $gen_raw = $pageRawData['ar'];
        } else {
            $gen_raw = $pageRawData;
        }

        $rawNotesSec = is_array($gen_raw['notes_section'] ?? null) ? $gen_raw['notes_section'] : [];
        $notesSection = [
            'title'      => (string)($rawNotesSec['title'] ?? $fallbackLabels['notes_title']),
            'notes_list' => is_array($rawNotesSec['notes_list'] ?? null) ? $rawNotesSec['notes_list'] : [],
        ];

        $visa_data = [
            'page_breadcrumb'     => $gen_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $gen_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $gen_raw['hero_img'] ?? 'assets/img/education/servicesimg14.png',
            'hero_position'       => $gen_raw['hero_position'] ?? 'center center',
            'main_title'          => $gen_raw['main_title'] ?? '',
            'main_desc'           => $gen_raw['main_desc'] ?? '',
            'notes_section'       => $notesSection,
            'download_items'      => is_array($gen_raw['download_items'] ?? null) ? $gen_raw['download_items'] : [],
        ];

        return [
            'visa_data'    => $visa_data,
            'general_page' => $visa_data,
        ];
    }
}
