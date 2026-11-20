<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class JobAgreementModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public static function getJobAgreementsData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Vereinbarungen zur Jobsuche',
                'note_title'      => 'Hinweis !!',
            ],
            'en' => [
                'page_breadcrumb' => 'Job Search Agreements',
                'note_title'      => 'Note !!',
            ],
            'ar' => [
                'page_breadcrumb' => 'اتفاقيات البحث عن عمل',
                'note_title'      => 'ملاحظة !!',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $pageRawData = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = 'job_agreements_page' LIMIT 1");
            $stmt->execute();
            $dbRaw = $stmt->fetchColumn();

            if (is_string($dbRaw) && $dbRaw !== '') {
                $decoded = json_decode($dbRaw, true);
                if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                    error_log('JobAgreementModel JSON Decode Error: ' . json_last_error_msg());
                } elseif (is_array($decoded)) {
                    $pageRawData = $decoded;
                }
            }
        } catch (\Throwable $e) {
            error_log('JobAgreementModel Error: ' . $e->getMessage());
        }

        $agr_raw = (isset($pageRawData[$activeLang]) && is_array($pageRawData[$activeLang]))
            ? $pageRawData[$activeLang]
            : [];

        $downloadItems = is_array($agr_raw['download_items'] ?? null) ? $agr_raw['download_items'] : [];
        if (empty($downloadItems) && is_array($agr_raw['download_item'] ?? null)) {
            $downloadItems = [$agr_raw['download_item']];
        }

        $job_agreements_data = [
            'page_breadcrumb'     => $agr_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'],
            'page_breadcrumb_url' => $agr_raw['page_breadcrumb_url'] ?? '#',
            'hero_img'            => $agr_raw['hero_img'] ?? 'assets/img/job/servicesimg4.png',
            'hero_position'       => $agr_raw['hero_position'] ?? 'center center',
            'main_title'          => $agr_raw['main_title'] ?? '',
            'main_desc'           => $agr_raw['main_desc'] ?? '',
            'note_title'          => $agr_raw['note_title'] ?? $fallbackLabels['note_title'],
            'note_text'           => $agr_raw['note_text'] ?? '',
            'download_items'      => $downloadItems,
        ];

        return [
            'job_agreements_data' => $job_agreements_data,
            'job_agreements_page' => $job_agreements_data,
        ];
    }
}
