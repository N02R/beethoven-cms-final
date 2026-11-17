<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class BachelorPackageModel {
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getBachelorData(?string $lang = null): array {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'BCS Bachelor Package',
                'password_label'  => 'Passwort:',
                'btn_text'        => 'Eingeben',
            ],
            'en' => [
                'page_breadcrumb' => 'BCS Bachelor Package',
                'password_label'  => 'Password:',
                'btn_text'        => 'Enter',
            ],
            'ar' => [
                'page_breadcrumb' => 'BCS Bachelor Package',
                'password_label'  => 'كلمة المرور:',
                'btn_text'        => 'ادخال',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'bachelor_package_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {}

        $bachelor_raw = [];
        if (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang])) {
            $bachelor_raw = $pageRawData[$activeLang];
        } elseif (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
            $bachelor_raw = $pageRawData['de'];
        } elseif (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
            $bachelor_raw = $pageRawData['en'];
        } elseif (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
            $bachelor_raw = $pageRawData['ar'];
        } else {
            $bachelor_raw = $pageRawData;
        }

        $bachelor_data = [
            'page_breadcrumb'     => $bachelor_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $bachelor_raw['page_breadcrumb_url'] ?? '#',
            'main_title'          => $bachelor_raw['main_title'] ?? '',
            'main_desc'           => $bachelor_raw['main_desc'] ?? '',
            'password_label'      => $bachelor_raw['password_label'] ?? $fallbackLabels['password_label'],
            'btn_text'            => $bachelor_raw['btn_text'] ?? $fallbackLabels['btn_text'],
            'btn_url'             => $bachelor_raw['btn_url'] ?? '#',
        ];

        return [
            'bachelor_data' => $bachelor_data,
        ];
    }
}
