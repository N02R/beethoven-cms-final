<?php
declare(strict_types=1);

namespace App\Models;

class AboutModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    /**
     * جلب وتجهيز بيانات صفحة "من نحن" والأقسام التابعة لها وفق المعيار الهندسي المعتمد
     */
    public static function getAboutData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'services_title' => 'Unsere Dienstleistungen',
                'team_title'     => 'Unser Team',
                'partners_title' => 'Unsere Partner in und außerhalb Deutschlands',
            ],
            'en' => [
                'services_title' => 'Our Featured Services',
                'team_title'     => 'Our Team',
                'partners_title' => 'Our Partners Inside and Outside Germany',
            ],
            'ar' => [
                'services_title' => 'خدماتنا المميزة',
                'team_title'     => 'فريق العمل',
                'partners_title' => 'شركاؤنا داخل وخارج ألمانيا',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $settings = SiteModel::getSettings();

        // دالة موحدة لاستخراج القسم باللغة المطلوبة من الكاش المركزي مع تراجع آمن (de -> en -> ar)
        $getLangData = static function (string $key, array $default = []) use ($settings, $activeLang): array {
            if (!isset($settings[$key])) {
                return $default;
            }

            $raw = $settings[$key];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;

            if (!is_array($decoded)) {
                return $default;
            }

            if (isset($decoded[$activeLang]) && is_array($decoded[$activeLang])) {
                return $decoded[$activeLang];
            }
            if (isset($decoded['de']) && is_array($decoded['de'])) {
                return $decoded['de'];
            }
            if (isset($decoded['en']) && is_array($decoded['en'])) {
                return $decoded['en'];
            }
            if (isset($decoded['ar']) && is_array($decoded['ar'])) {
                return $decoded['ar'];
            }

            return $decoded;
        };

        // 1. قسم من نحن الرئيسي (About Section)
        $about_raw = $getLangData('about_section', []);

        // 2. قسم خدمات صفحة من نحن المستقل (About Services)
        $about_services_raw = $getLangData('about_services', []);
        $about_services = [
            'section_title'    => $about_services_raw['section_title'] ?? ($about_services_raw['title'] ?? $fallbackLabels['services_title']),
            'section_subtitle' => $about_services_raw['section_subtitle'] ?? ($about_services_raw['desc'] ?? ''),
            'items'            => is_array($about_services_raw['items'] ?? null) ? $about_services_raw['items'] : [],
        ];

        // 3. قسم فريق العمل (Team)
        $team_raw = $getLangData('team', []);
        $legacyTeamItems = [];
        if (isset($settings['team_items'])) {
            $decodedTeam = is_string($settings['team_items']) ? json_decode($settings['team_items'], true) : $settings['team_items'];
            if (is_array($decodedTeam)) {
                $legacyTeamItems = $decodedTeam;
            }
        }

        $team_items = is_array($team_raw['items'] ?? null)
            ? $team_raw['items']
            : (!isset($team_raw['title']) && !isset($team_raw['desc']) && !empty($team_raw) ? $team_raw : $legacyTeamItems);

        $team_data = [
            'title' => $team_raw['title'] ?? ($team_raw['team_title'] ?? ($settings['team_title'] ?? $fallbackLabels['team_title'])),
            'desc'  => $team_raw['desc'] ?? ($team_raw['team_desc'] ?? ($settings['team_desc'] ?? '')),
            'items' => is_array($team_items) ? $team_items : [],
        ];

        // 4. قسم العدادات والإحصائيات (About Counts)
        $counts_raw = $getLangData('about_counts', []);
        $counts_items = is_array($counts_raw['items'] ?? null)
            ? $counts_raw['items']
            : (!isset($counts_raw['items']) && !empty($counts_raw) ? $counts_raw : []);

        $about_counts = [
            'items' => is_array($counts_items) ? $counts_items : [],
        ];

        // 5. قسم الشركاء (Partners)
        $partners_raw = $getLangData('partners', []);
        $legacyPartnerItems = [];
        if (isset($settings['partners_items'])) {
            $decodedPartners = is_string($settings['partners_items']) ? json_decode($settings['partners_items'], true) : $settings['partners_items'];
            if (is_array($decodedPartners)) {
                $legacyPartnerItems = $decodedPartners;
            }
        }

        $partners_items = is_array($partners_raw['items'] ?? null)
            ? $partners_raw['items']
            : (!isset($partners_raw['title']) && !empty($partners_raw) ? $partners_raw : $legacyPartnerItems);

        $partners_data = [
            'title' => $partners_raw['title'] ?? ($partners_raw['partners_title'] ?? ($settings['partners_title'] ?? $fallbackLabels['partners_title'])),
            'items' => is_array($partners_items) ? $partners_items : [],
        ];

        return [
            'about_section'    => $about_raw,
            'about_services'   => $about_services,
            'team'             => $team_data,
            'team_section'     => $team_data,
            'team_title'       => $team_data['title'],
            'team_desc'        => $team_data['desc'],
            'team_items'       => $team_data['items'],
            'about_counts'     => $about_counts,
            'partners'         => $partners_data,
            'partners_section' => $partners_data,
            'partners_title'   => $partners_data['title'],
            'partners_items'   => $partners_data['items'],
        ];
    }
}
