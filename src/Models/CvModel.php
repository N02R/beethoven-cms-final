<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class CvModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getCvData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Lebenslauf (CV)',
                'main_title'      => 'Lebenslauf „CV“',
                'advice_title'    => 'Schnelle Tipps für einen effektiven Lebenslauf',
            ],
            'en' => [
                'page_breadcrumb' => 'Curriculum Vitae (CV)',
                'main_title'      => 'Curriculum Vitae "CV"',
                'advice_title'    => 'Quick Tips for Writing an Effective CV',
            ],
            'ar' => [
                'page_breadcrumb' => 'السيرة الذاتية CV',
                'main_title'      => 'السيرة الذاتية "CV"',
                'advice_title'    => 'نصائح سريعة لكتابة CV فعّال',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'cv_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('CvModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('CvModel Error: ' . $e->getMessage());
        }

        $cv_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $cv_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $cv_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $cv_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $cv_raw = $pageRawData['ar'];
        } else {
            $cv_raw = $pageRawData;
        }

        $cv_data = [
            'page_breadcrumb'     => $cv_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $cv_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $cv_raw['hero_img'] ?? 'assets/img/education/servicesimg2.jpg',
            'hero_position'       => $cv_raw['hero_position'] ?? 'center -30px',
            'main_title'          => $cv_raw['main_title'] ?? $fallbackLabels['main_title'],
            'main_desc'           => $cv_raw['main_desc'] ?? '',
            'advice_title'        => $cv_raw['advice_title'] ?? $fallbackLabels['advice_title'],
            'advice_points'       => is_array($cv_raw['advice_points'] ?? null) ? $cv_raw['advice_points'] : [],
            'download_items'      => is_array($cv_raw['download_items'] ?? null) ? $cv_raw['download_items'] : [],
        ];

        return [
            'cv_data' => $cv_data,
            'cv_page' => $cv_data,
        ];
    }
}
