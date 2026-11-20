<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class OffersModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getOffersData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Angebote und Vereinbarungen',
                'note_title'      => 'Wichtige Hinweise !!',
            ],
            'en' => [
                'page_breadcrumb' => 'Offers and Agreements',
                'note_title'      => 'Important Notes !!',
            ],
            'ar' => [
                'page_breadcrumb' => 'العروض والاتفاقيات',
                'note_title'      => 'ملاحظات هامة !!',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'offers_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('OffersModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('OffersModel Error: ' . $e->getMessage());
        }

        $off_raw = (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang]))
            ? $pageRawData[$activeLang]
            : [];

        $notesList = [];
        if (is_array($off_raw['notes_list'] ?? null)) {
            $notesList = array_values(array_filter(array_map(static fn($v) => trim((string)$v), $off_raw['notes_list']), static fn($v) => $v !== ''));
        } elseif (is_array($off_raw['notes'] ?? null)) {
            $notesList = array_values(array_filter(array_map(static fn($v) => trim((string)$v), $off_raw['notes']), static fn($v) => $v !== ''));
        } elseif (!empty($off_raw['note_text']) && is_string($off_raw['note_text'])) {
            $notesList = [trim($off_raw['note_text'])];
        }

        $downloadCards = is_array($off_raw['download_cards'] ?? null)
            ? $off_raw['download_cards']
            : (is_array($off_raw['download_items'] ?? null) ? $off_raw['download_items'] : []);

        $offers_data = [
            'page_breadcrumb'     => $off_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $off_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $off_raw['hero_img'] ?? 'assets/img/education/servicesimg10.png',
            'hero_position'       => $off_raw['hero_position'] ?? 'center center',
            'main_title'          => $off_raw['main_title'] ?? '',
            'main_desc'           => $off_raw['main_desc'] ?? '',
            'note_title'          => $off_raw['note_title'] ?? $fallbackLabels['note_title'],
            'notes_list'          => $notesList,
            'note_text'           => $notesList[0] ?? (string)($off_raw['note_text'] ?? ''),
            'download_cards'      => $downloadCards,
        ];

        return [
            'offers_data' => $offers_data,
            'offers_page' => $offers_data,
        ];
    }
}
