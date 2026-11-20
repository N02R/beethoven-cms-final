<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class MotivationModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getMotivationData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Motivationsschreiben',
                'advice_title'    => 'Kurze Tipps zum Verfassen eines Motivationsschreibens',
            ],
            'en' => [
                'page_breadcrumb' => 'Motivation Letter',
                'advice_title'    => 'Quick Tips for Writing a Motivation Letter',
            ],
            'ar' => [
                'page_breadcrumb' => 'خطاب الدافع / التحفيز',
                'advice_title'    => 'نصائح سريعة لكتابة خطاب الدافع',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'motivation_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('MotivationLetterModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('MotivationLetterModel Error: ' . $e->getMessage());
        }

        $mot_raw = (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang]))
            ? $pageRawData[$activeLang]
            : [];

        $adviceSecRaw = is_array($mot_raw['advice_section'] ?? null) ? $mot_raw['advice_section'] : [];
        $adviceItemsRaw = $adviceSecRaw['items'] ?? ($mot_raw['advice_points'] ?? ($mot_raw['tips'] ?? []));
        $cleanAdviceItems = is_array($adviceItemsRaw)
            ? array_values(array_filter(array_map(static fn($v) => trim((string)$v), $adviceItemsRaw), static fn($v) => $v !== ''))
            : [];

        $downloadItems = is_array($mot_raw['download_items'] ?? null) ? $mot_raw['download_items'] : [];
        if (empty($downloadItems) && is_array($mot_raw['download_item'] ?? null)) {
            $downloadItems = [$mot_raw['download_item']];
        }

        $motivation_data = [
            'page_breadcrumb'     => $mot_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $mot_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $mot_raw['hero_img'] ?? 'assets/img/education/servicesimg3.png',
            'hero_position'       => $mot_raw['hero_position'] ?? 'center center',
            'main_title'          => $mot_raw['main_title'] ?? '',
            'main_desc'           => $mot_raw['main_desc'] ?? '',
            'advice_section'      => [
                'title' => (string)($adviceSecRaw['title'] ?? ($mot_raw['advice_title'] ?? $fallbackLabels['advice_title'])),
                'items' => $cleanAdviceItems,
            ],
            'download_items'      => $downloadItems,
        ];

        return [
            'motivation_data' => $motivation_data,
            'motivation_page' => $motivation_data,
        ];
    }
}
