<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class CheckModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getCheckData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => ['page_breadcrumb' => 'Zeugnisanerkennung prüfen'],
            'en' => ['page_breadcrumb' => 'Check Your Educational Certificates'],
            'ar' => ['page_breadcrumb' => 'تحقق من شهاداتك التعليمية'],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'check_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('CheckModel Error: ' . $e->getMessage());
        }

        $check_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $check_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $check_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $check_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $check_raw = $pageRawData['ar'];
        } else {
            $check_raw = $pageRawData;
        }

        $check_data = [
            'page_breadcrumb'     => $check_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $check_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $check_raw['hero_img'] ?? 'assets/img/education/servicesimg13.png',
            'hero_position'       => $check_raw['hero_position'] ?? 'center -9rem',
            'main_title'          => $check_raw['main_title'] ?? '',
            'main_desc'           => $check_raw['main_desc'] ?? '',
            'note_title'          => $check_raw['note_title'] ?? '',
            'notes'               => is_array($check_raw['notes'] ?? null) ? $check_raw['notes'] : [],
            'links_intro'         => $check_raw['links_intro'] ?? '',
            'anabin_url'          => $check_raw['anabin_url'] ?? '',
            'uniassist_url'       => $check_raw['uniassist_url'] ?? '',
            'uni_contact_intro'   => $check_raw['uni_contact_intro'] ?? '',
            'condition_1'         => $check_raw['condition_1'] ?? '',
            'condition_2'         => $check_raw['condition_2'] ?? '',
            'conclusion_text'     => $check_raw['conclusion_text'] ?? '',
        ];

        return [
            'check_data' => $check_data,
        ];
    }
}
