<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class FoundationModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getFoundationData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Studienkolleg / Vorbereitungskurs',
                'goals_title'     => 'Ziele des Studienkollegs',
                'courses_title'   => 'Kurstypen des Studienkollegs',
                'types_title'     => 'Arten des Studienkollegs in Deutschland',
                'notes_title'     => 'Wichtige Hinweise !!',
                'tips_title'      => 'Wichtige Tipps vor der Bewerbung',
            ],
            'en' => [
                'page_breadcrumb' => 'Foundation Course / Studienkolleg',
                'goals_title'     => 'Foundation Course Objectives',
                'courses_title'   => 'Types of Studienkolleg Courses',
                'types_title'     => 'Types of Studienkolleg in Germany',
                'notes_title'     => 'Important Notes !!',
                'tips_title'      => 'Important Tips Before Applying',
            ],
            'ar' => [
                'page_breadcrumb' => 'الدورة التأسيسية / السنة التحضيرية',
                'goals_title'     => 'أهداف الدورة التأسيسية',
                'courses_title'   => 'أنواع دورات السنة التحضيرية',
                'types_title'     => 'أنواع السنة التحضيرية في ألمانيا',
                'notes_title'     => 'ملاحظات هامة !!',
                'tips_title'      => 'نصائح مهمة قبل التقديم',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'foundation_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('FoundationModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('FoundationModel Error: ' . $e->getMessage());
        }

        $stk_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $stk_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $stk_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $stk_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $stk_raw = $pageRawData['ar'];
        } else {
            $stk_raw = $pageRawData;
        }

        $stk_data = [
            'page_breadcrumb'     => $stk_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $stk_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $stk_raw['hero_img'] ?? 'assets/img/education/serviceimg11.png',
            'hero_position'       => $stk_raw['hero_position'] ?? 'center -20rem',
            'main_title'          => $stk_raw['main_title'] ?? '',
            'main_desc'           => $stk_raw['main_desc'] ?? '',
            'goals_title'         => $stk_raw['goals_title'] ?? $fallbackLabels['goals_title'],
            'goals_items'         => is_array($stk_raw['goals_items'] ?? null) ? $stk_raw['goals_items'] : [],
            'learning_title'      => $stk_raw['learning_title'] ?? '',
            'learning_intro'      => $stk_raw['learning_intro'] ?? '',
            'learning_p1'         => $stk_raw['learning_p1'] ?? '',
            'learning_p2'         => $stk_raw['learning_p2'] ?? '',
            'courses_title'       => $stk_raw['courses_title'] ?? $fallbackLabels['courses_title'],
            'courses_items'       => is_array($stk_raw['courses_items'] ?? null) ? $stk_raw['courses_items'] : [],
            'uni_type_title'      => $stk_raw['uni_type_title'] ?? '',
            'uni_type_intro'      => $stk_raw['uni_type_intro'] ?? '',
            'uni_public'          => $stk_raw['uni_public'] ?? '',
            'uni_applied'         => $stk_raw['uni_applied'] ?? '',
            'types_title'         => $stk_raw['types_title'] ?? $fallbackLabels['types_title'],
            'type_public_desc'    => $stk_raw['type_public_desc'] ?? '',
            'type_private_desc'   => $stk_raw['type_private_desc'] ?? '',
            'notes_title'         => $stk_raw['notes_title'] ?? $fallbackLabels['notes_title'],
            'notes_items'         => is_array($stk_raw['notes_items'] ?? null) ? $stk_raw['notes_items'] : [],
            'exam_title'          => $stk_raw['exam_title'] ?? '',
            'exam_desc'           => $stk_raw['exam_desc'] ?? '',
            'fsp_title'           => $stk_raw['fsp_title'] ?? '',
            'fsp_desc'            => $stk_raw['fsp_desc'] ?? '',
            'tips_title'          => $stk_raw['tips_title'] ?? $fallbackLabels['tips_title'],
            'tips_items'          => is_array($stk_raw['tips_items'] ?? null) ? $stk_raw['tips_items'] : [],
        ];

        return [
            'stk_data'        => $stk_data,
            'foundation_page' => $stk_data,
        ];
    }
}
