<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class VocationalModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getVocationalData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Berufsausbildung (Ausbildung)',
                'default_title'   => 'Liste der Ausbildungsberufe',
                'default_sub'     => 'Wählen Sie Ihren Ausbildungsberuf',
            ],
            'en' => [
                'page_breadcrumb' => 'Vocational Training (Ausbildung)',
                'default_title'   => 'List of Vocational Training Specializations',
                'default_sub'     => 'Choose your vocational specialization',
            ],
            'ar' => [
                'page_breadcrumb' => 'التدريب المهني (Ausbildung)',
                'default_title'   => 'قائمة تخصصات التدريب المهني',
                'default_sub'     => 'اختر تخصصك المهني',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'vocational_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('VocationalModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('VocationalModel Error: ' . $e->getMessage());
        }

        $voc_raw = (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang]))
            ? $pageRawData[$activeLang]
            : [];

        $downloadItems = is_array($voc_raw['download_items'] ?? null) ? $voc_raw['download_items'] : [];
        if (empty($downloadItems) && is_array($voc_raw['download_item'] ?? null)) {
            $downloadItems = [$voc_raw['download_item']];
        }
        if (empty($downloadItems)) {
            $downloadItems = [[
                'type'  => 'pdf',
                'title' => $fallbackLabels['default_title'],
                'sub'   => $fallbackLabels['default_sub'],
                'file'  => 'assets/files/vocational_training_list.pdf',
            ]];
        }

        $vocational_data = [
            'page_breadcrumb'     => $voc_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $voc_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $voc_raw['hero_img'] ?? 'assets/img/job/servicesimg2.png',
            'hero_position'       => $voc_raw['hero_position'] ?? 'center center',
            'main_title'          => $voc_raw['main_title'] ?? '',
            'main_desc'           => $voc_raw['main_desc'] ?? '',
            'download_items'      => $downloadItems,
        ];

        return [
            'vocational_data' => $vocational_data,
            'vocational_page' => $vocational_data,
        ];
    }
}
