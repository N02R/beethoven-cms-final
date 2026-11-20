<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class MedicalPackageModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getMedicalData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Medizinisches Praktikum-Paket',
                'note_title'      => 'Wichtige Hinweise !!',
            ],
            'en' => [
                'page_breadcrumb' => 'Medical Training Package',
                'note_title'      => 'Important Notes !!',
            ],
            'ar' => [
                'page_breadcrumb' => 'باقة التدريب الطبي',
                'note_title'      => 'ملاحظات هامة !!',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'medical_packages_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('MedicalPackageModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('MedicalPackageModel Error: ' . $e->getMessage());
        }

        $med_raw = (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang]))
            ? $pageRawData[$activeLang]
            : [];

        $notesList = [];
        if (is_array($med_raw['notes_list'] ?? null)) {
            $notesList = array_values(array_filter(array_map(static fn($v) => trim((string)$v), $med_raw['notes_list']), static fn($v) => $v !== ''));
        } elseif (is_array($med_raw['notes'] ?? null)) {
            $notesList = array_values(array_filter(array_map(static fn($v) => trim((string)$v), $med_raw['notes']), static fn($v) => $v !== ''));
        } elseif (!empty($med_raw['note_text']) && is_string($med_raw['note_text'])) {
            $notesList = [trim($med_raw['note_text'])];
        }

        $downloadItems = is_array($med_raw['download_items'] ?? null) ? $med_raw['download_items'] : [];
        if (empty($downloadItems) && is_array($med_raw['download_item'] ?? null)) {
            $downloadItems = [$med_raw['download_item']];
        }

        $medical_data = [
            'page_breadcrumb'     => $med_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $med_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $med_raw['hero_img'] ?? 'assets/img/job/servicesimg3.png',
            'hero_position'       => $med_raw['hero_position'] ?? 'center center',
            'main_title'          => $med_raw['main_title'] ?? '',
            'main_desc'           => $med_raw['main_desc'] ?? '',
            'note_title'          => $med_raw['note_title'] ?? $fallbackLabels['note_title'],
            'notes_list'          => $notesList,
            'note_text'           => $notesList[0] ?? (string)($med_raw['note_text'] ?? ''),
            'download_items'      => $downloadItems,
        ];

        return [
            'medical_data'          => $medical_data,
            'medical_packages_page' => $medical_data,
        ];
    }
}
