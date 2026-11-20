<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class HealthModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getHealthData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Krankenversicherung',
                'advice_title'    => 'Warum ist eine Krankenversicherung wichtig?',
                'note_title'      => 'Ergänzende Unterlagen',
            ],
            'en' => [
                'page_breadcrumb' => 'Health Insurance',
                'advice_title'    => 'Why Is Health Insurance Important?',
                'note_title'      => 'Supporting Documents',
            ],
            'ar' => [
                'page_breadcrumb' => 'التأمين الصحي',
                'advice_title'    => 'لماذا التأمين الصحي مهم؟',
                'note_title'      => 'الوثائق المكملة',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'health_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('HealthModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('HealthModel Error: ' . $e->getMessage());
        }

        $health_raw = (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang]))
            ? $pageRawData[$activeLang]
            : [];

        $health_data = [
            'page_breadcrumb'     => $health_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $health_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $health_raw['hero_img'] ?? 'assets/img/education/servicesimg6.png',
            'hero_position'       => $health_raw['hero_position'] ?? 'center center',
            'main_title'          => $health_raw['main_title'] ?? '',
            'main_desc'           => $health_raw['main_desc'] ?? '',
            'advice_title'        => $health_raw['advice_title'] ?? $fallbackLabels['advice_title'],
            'tips'                => is_array($health_raw['tips'] ?? null) ? $health_raw['tips'] : [],
            'note_title'          => $health_raw['note_title'] ?? $fallbackLabels['note_title'],
            'notes'               => is_array($health_raw['notes'] ?? null) ? $health_raw['notes'] : [],
            'intro_desc'          => $health_raw['intro_desc'] ?? '',
            'links'               => is_array($health_raw['links'] ?? null) ? $health_raw['links'] : [],
        ];

        return [
            'health_data' => $health_data,
            'health_page' => $health_data,
        ];
    }
}
