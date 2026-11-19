<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class CoverLetterModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getCoverLetterData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Anschreiben',
                'main_title'      => 'Professionelles Anschreiben / Motivationsschreiben',
                'advice_title'    => 'Wichtige Punkte zum Beachten',
                'note_title'      => 'Wichtige Hinweise !!',
            ],
            'en' => [
                'page_breadcrumb' => 'Cover Letter',
                'main_title'      => 'Professional Cover Letter / Application Letter',
                'advice_title'    => 'Key Points to Consider',
                'note_title'      => 'Important Notes !!',
            ],
            'ar' => [
                'page_breadcrumb' => 'خطاب الطلب',
                'main_title'      => 'رسالة تعريف / خطاب طلب احترافي',
                'advice_title'    => 'النقاط التي يجب مراعاتها',
                'note_title'      => 'ملاحظات هامة !!',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'coverletter_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('CoverLetterModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('CoverLetterModel Error: ' . $e->getMessage());
        }

        $cover_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $cover_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $cover_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $cover_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $cover_raw = $pageRawData['ar'];
        } else {
            $cover_raw = $pageRawData;
        }

        $cover_data = [
            'page_breadcrumb'     => $cover_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $cover_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $cover_raw['hero_img'] ?? 'assets/img/education/servicesimg1.jpg',
            'hero_position'       => $cover_raw['hero_position'] ?? 'center -30px',
            'main_title'          => $cover_raw['main_title'] ?? $fallbackLabels['main_title'],
            'main_desc'           => $cover_raw['main_desc'] ?? '',
            'advice_title'        => $cover_raw['advice_title'] ?? $fallbackLabels['advice_title'],
            'advice_points'       => is_array($cover_raw['advice_points'] ?? null) ? $cover_raw['advice_points'] : [],
            'note_title'          => $cover_raw['note_title'] ?? $fallbackLabels['note_title'],
            'notes'               => is_array($cover_raw['notes'] ?? null) ? $cover_raw['notes'] : [],
            'download_items'      => is_array($cover_raw['download_items'] ?? null) ? $cover_raw['download_items'] : [],
        ];

        return [
            'coverletter_page' => $cover_data,
            'cover'            => $cover_data,
        ];
    }
}
