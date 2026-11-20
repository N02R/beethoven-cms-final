<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class LivingModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getLivingData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Lebenshaltungskosten in Deutschland',
                'tips_title'      => 'Tipps zur Reduzierung der Ausgaben',
                'notes_title'     => 'Wichtige Hinweise !!',
            ],
            'en' => [
                'page_breadcrumb' => 'Cost of Living in Germany',
                'tips_title'      => 'Tips to Reduce Expenses',
                'notes_title'     => 'Important Notes !!',
            ],
            'ar' => [
                'page_breadcrumb' => 'تكلفة المعيشة في ألمانيا',
                'tips_title'      => 'نصائح لتقليل النفقات',
                'notes_title'     => 'ملاحظات هامة !!',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'living_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('LivingModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('LivingModel Error: ' . $e->getMessage());
        }

        $liv_raw = (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang]))
            ? $pageRawData[$activeLang]
            : [];

        $rawTips  = is_array($liv_raw['tips_section'] ?? null) ? $liv_raw['tips_section'] : [];
        $rawNotes = is_array($liv_raw['notes_section'] ?? null) ? $liv_raw['notes_section'] : [];

        $living_data = [
            'page_breadcrumb'     => $liv_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $liv_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $liv_raw['hero_img'] ?? 'assets/img/education/servicesimg8.png',
            'hero_position'       => $liv_raw['hero_position'] ?? 'center center',
            'main_title'          => $liv_raw['main_title'] ?? '',
            'main_desc'           => $liv_raw['main_desc'] ?? '',
            'tips_section'        => [
                'title' => (string)($rawTips['title'] ?? $fallbackLabels['tips_title']),
                'items' => is_array($rawTips['items'] ?? null) ? $rawTips['items'] : [],
            ],
            'notes_section'       => [
                'title' => (string)($rawNotes['title'] ?? $fallbackLabels['notes_title']),
                'items' => is_array($rawNotes['items'] ?? null) ? $rawNotes['items'] : [],
            ],
        ];

        return [
            'living_data' => $living_data,
            'living_page' => $living_data,
        ];
    }
}
