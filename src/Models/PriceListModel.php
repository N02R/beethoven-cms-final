<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class PriceListModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getPriceListData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Preisliste der Leistungen',
                'default_title'   => 'Allgemeine Preisliste',
            ],
            'en' => [
                'page_breadcrumb' => 'Services Price List',
                'default_title'   => 'General Price List',
            ],
            'ar' => [
                'page_breadcrumb' => 'قائمة أسعار الخدمات',
                'default_title'   => 'قائمة الأسعار العامة',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'pricelist_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('PriceListModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('PriceListModel Error: ' . $e->getMessage());
        }

        $price_raw = (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang]))
            ? $pageRawData[$activeLang]
            : [];

        $downloadItems = is_array($price_raw['download_items'] ?? null) ? $price_raw['download_items'] : [];
        if (empty($downloadItems) && is_array($price_raw['download_item'] ?? null) && !empty($price_raw['download_item'])) {
            $downloadItems = [$price_raw['download_item']];
        }

        $firstItem = is_array($downloadItems[0] ?? null)
            ? $downloadItems[0]
            : (is_array($price_raw['download_item'] ?? null) ? $price_raw['download_item'] : []);

        $pricelist_data = [
            'page_breadcrumb'     => $price_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $price_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $price_raw['hero_img'] ?? 'assets/img/education/servicesimg15.png',
            'hero_position'       => $price_raw['hero_position'] ?? 'center center',
            'main_title'          => $price_raw['main_title'] ?? '',
            'main_desc'           => $price_raw['main_desc'] ?? '',
            'download_item'       => $firstItem,
            'download_items'      => $downloadItems,
        ];

        return [
            'pricelist_data' => $pricelist_data,
            'pricelist_page' => $pricelist_data,
        ];
    }
}
