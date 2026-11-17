<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class ArrivalModel {
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    /**
     * جلب وتجهيز بيانات صفحة الاستقبال والوصول من جدول pages مترجمة حسب اللغة النشطة
     */
    public static function getArrivalData(?string $lang = null): array {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Flughafentransfer, Transport, Unterkunft & Wohnen',
            ],
            'en' => [
                'page_breadcrumb' => 'Airport Reception, Transportation, Accommodation & Housing',
            ],
            'ar' => [
                'page_breadcrumb' => 'الإستقبال في المطار، المواصلات، الإقامة والسكن',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        // استجابة مباشرة من جدول pages الجديد
        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'arrival_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('ArrivalModel Error: ' . $e->getMessage());
        }

        // استخراج بيانات اللغة النشطة مع التراجع الآمن (de -> en -> ar)
        $arrival_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $arrival_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $arrival_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $arrival_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $arrival_raw = $pageRawData['ar'];
        } else {
            $arrival_raw = $pageRawData;
        }

        $arrival_data = [
            'page_breadcrumb'     => $arrival_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $arrival_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $arrival_raw['hero_img'] ?? 'assets/img/education/servicesimg9.png',
            'main_title'          => $arrival_raw['main_title'] ?? '',
            'main_desc'           => $arrival_raw['main_desc'] ?? '',
            'advice_title'        => $arrival_raw['advice_title'] ?? '',
            'advice_desc'         => $arrival_raw['advice_desc'] ?? '',
            'tips'                => is_array($arrival_raw['tips'] ?? null) ? $arrival_raw['tips'] : [],
            'note_title'          => $arrival_raw['note_title'] ?? '',
            'notes'               => is_array($arrival_raw['notes'] ?? null) ? $arrival_raw['notes'] : [],
        ];

        return [
            'arrival_data' => $arrival_data,
        ];
    }
}
