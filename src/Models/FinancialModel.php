<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class FinancialModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getFinancialData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb'  => 'Finanzierungsnachweis & Sperrkonto',
                'importance_title' => 'Bedeutung des Finanzierungsnachweises',
                'options_title'    => 'Möglichkeiten des Finanzierungsnachweises für das Studium in Deutschland',
                'account_title'    => 'Das Sperrkonto',
            ],
            'en' => [
                'page_breadcrumb'  => 'Financial Guarantees & Blocked Bank Account',
                'importance_title' => 'Importance of Proof of Financial Means',
                'options_title'    => 'Financial Guarantee Options for Studying in Germany',
                'account_title'    => 'Blocked Bank Account',
            ],
            'ar' => [
                'page_breadcrumb'  => 'الضمانات المالية والحساب البنكي المغلق',
                'importance_title' => 'أهمية إثبات الضمان المالي',
                'options_title'    => 'خيارات الضمان المالي للدراسة في ألمانيا',
                'account_title'    => 'الحساب البنكي المغلق',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'financial_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('FinancialModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('FinancialModel Error: ' . $e->getMessage());
        }

        $fin_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $fin_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $fin_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $fin_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $fin_raw = $pageRawData['ar'];
        } else {
            $fin_raw = $pageRawData;
        }

        $financial_data = [
            'page_breadcrumb'     => $fin_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $fin_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $fin_raw['hero_img'] ?? 'assets/img/education/servicesimg7.png',
            'hero_position'       => $fin_raw['hero_position'] ?? 'center center',
            'main_title'          => $fin_raw['main_title'] ?? '',
            'main_desc'           => $fin_raw['main_desc'] ?? '',
            'importance_title'    => $fin_raw['importance_title'] ?? $fallbackLabels['importance_title'],
            'importance_desc'     => $fin_raw['importance_desc'] ?? '',
            'options_title'       => $fin_raw['options_title'] ?? $fallbackLabels['options_title'],
            'options_items'       => is_array($fin_raw['options_items'] ?? null) ? $fin_raw['options_items'] : [],
            'account_title'       => $fin_raw['account_title'] ?? $fallbackLabels['account_title'],
            'account_points'      => is_array($fin_raw['account_points'] ?? null) ? $fin_raw['account_points'] : [],
            'service_links'       => is_array($fin_raw['service_links'] ?? null) ? $fin_raw['service_links'] : [],
        ];

        return [
            'financial_data' => $financial_data,
            'financial_page' => $financial_data,
        ];
    }
}
