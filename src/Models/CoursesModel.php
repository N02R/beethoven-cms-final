<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class CoursesModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getCoursesData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Vorbereitungskurs für Deutsch-Sprachzertifikate',
                'goals_title'     => 'Ziele des Vorbereitungskurses',
                'cost_title'      => 'Studienorte und Kosten',
            ],
            'en' => [
                'page_breadcrumb' => 'German Language Certificate Preparatory Course',
                'goals_title'     => 'Preparatory Course Objectives',
                'cost_title'      => 'Enrollment Locations and Costs',
            ],
            'ar' => [
                'page_breadcrumb' => 'الدورة التحضيرية لشهادات اللغة الألمانية',
                'goals_title'     => 'أهداف الدورة التحضيرية',
                'cost_title'      => 'أماكن الالتحاق والتكلفة',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'courses_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('CoursesModel Error: ' . $e->getMessage());
        }

        $courses_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $courses_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $courses_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $courses_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $courses_raw = $pageRawData['ar'];
        } else {
            $courses_raw = $pageRawData;
        }

        $courses_data = [
            'page_breadcrumb'     => $courses_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $courses_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $courses_raw['hero_img'] ?? 'assets/img/education/servicesimg12.png',
            'hero_position'       => $courses_raw['hero_position'] ?? 'center center',
            'main_title'          => $courses_raw['main_title'] ?? '',
            'main_desc'           => $courses_raw['main_desc'] ?? '',
            'goals_title'         => $courses_raw['goals_title'] ?? $fallbackLabels['goals_title'],
            'goals'               => is_array($courses_raw['goals'] ?? null) ? $courses_raw['goals'] : [],
            'warning_text'        => $courses_raw['warning_text'] ?? '',
            'cost_title'          => $courses_raw['cost_title'] ?? $fallbackLabels['cost_title'],
            'cost_items'          => is_array($courses_raw['cost_items'] ?? null) ? $courses_raw['cost_items'] : [],
        ];

        return [
            'lang_data'    => $courses_data,
            'courses_data' => $courses_data,
        ];
    }
}
