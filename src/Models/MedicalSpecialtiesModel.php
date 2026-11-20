<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class MedicalSpecialtiesModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getMedicalSpecialtiesData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Medizinische Fachrichtungen',
            ],
            'en' => [
                'page_breadcrumb' => 'Medical Specialties',
            ],
            'ar' => [
                'page_breadcrumb' => 'التخصصات الطبية',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'medical_specialties_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('MedicalSpecialtiesModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('MedicalSpecialtiesModel Error: ' . $e->getMessage());
        }

        $spec_raw = (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang]))
            ? $pageRawData[$activeLang]
            : [];

        $downloadItems = is_array($spec_raw['download_items'] ?? null) ? $spec_raw['download_items'] : [];
        if (empty($downloadItems) && is_array($spec_raw['download_item'] ?? null)) {
            $downloadItems = [$spec_raw['download_item']];
        }

        $medical_spec_data = [
            'page_breadcrumb'     => $spec_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $spec_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $spec_raw['hero_img'] ?? 'assets/img/job/servicesimg1.png',
            'hero_position'       => $spec_raw['hero_position'] ?? 'center center',
            'main_title'          => $spec_raw['main_title'] ?? '',
            'main_desc'           => $spec_raw['main_desc'] ?? '',
            'download_items'      => $downloadItems,
        ];

        return [
            'medical_spec_data'        => $medical_spec_data,
            'medical_specialties_page' => $medical_spec_data,
        ];
    }
}
