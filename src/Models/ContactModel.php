<?php
declare(strict_types=1);

namespace App\Models;

class ContactModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    /**
     * جلب وتجهيز بيانات صفحة "تواصل معنا" والأقسام التابعة لها مترجمة ومجهزة بالكامل وفق المعيار الهندسي المعتمد
     */
    public static function getContactData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'info_title'    => 'Kontaktinformationen',
                'whatsapp_text' => 'Möchten Sie eine sofortige Beratung per WhatsApp erhalten? Sprechen Sie jetzt direkt mit unseren Experten.',
                'whatsapp_btn'  => 'Über WhatsApp kontaktieren',
            ],
            'en' => [
                'info_title'    => 'Contact Information',
                'whatsapp_text' => 'Would you like an instant consultation via WhatsApp? Speak directly with our experts now.',
                'whatsapp_btn'  => 'Contact via WhatsApp',
            ],
            'ar' => [
                'info_title'    => 'معلومات التواصل',
                'whatsapp_text' => 'هل ترغب في الحصول على استشارة فورية عبر الواتساب؟ تحدث مباشرة مع خبرائنا الآن.',
                'whatsapp_btn'  => 'تواصل عبر الواتساب',
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

        // 1. قسم هيرو صفحة التواصل (Contact Hero)
        $contact_hero_raw = $getLangData('contact_hero', []);
        $contact_hero_img = (string)($contact_hero_raw['img'] ?? ($settings['contact_hero_img'] ?? 'assets/img/contacthero.png'));
        $contact_hero = [
            'img' => $contact_hero_img,
        ];

        // 2. معلومات التواصل والأيقونات (Contact Info)
        $contact_info_raw = $getLangData('contact_info', []);
        $contact_info = [
            'title'        => (string)($contact_info_raw['title'] ?? $fallbackLabels['info_title']),
            'desc'         => (string)($contact_info_raw['desc'] ?? ''),
            'address'      => (string)($contact_info_raw['address'] ?? 'Rheinweg 140, 53129 Bonn, Germany'),
            'email'        => (string)($contact_info_raw['email'] ?? 'info@beethoven-services.de'),
            'phone'        => (string)($contact_info_raw['phone'] ?? '+49 123 456789'),
            'address_icon' => (string)($contact_info_raw['address_icon'] ?? 'assets/img/Location.svg'),
            'email_icon'   => (string)($contact_info_raw['email_icon'] ?? 'assets/img/Mail.svg'),
            'phone_icon'   => (string)($contact_info_raw['phone_icon'] ?? 'assets/img/Call.svg'),
        ];

        // 3. قسم الواتساب (WhatsApp Section)
        $whatsapp_raw = $getLangData('whatsapp_section', []);
        $whatsapp_section = [
            'text'     => (string)($whatsapp_raw['text'] ?? $fallbackLabels['whatsapp_text']),
            'url'      => (string)($whatsapp_raw['url'] ?? 'https://wa.me/49123456789'),
            'btn_text' => (string)($whatsapp_raw['btn_text'] ?? $fallbackLabels['whatsapp_btn']),
        ];

        return [
            'contact_hero'         => $contact_hero,
            'contact_hero_img'     => $contact_hero['img'],
            'contact_info'         => $contact_info,
            'contact_info_title'   => $contact_info['title'],
            'contact_info_desc'    => $contact_info['desc'],
            'contact_address'      => $contact_info['address'],
            'contact_address_icon' => $contact_info['address_icon'],
            'contact_email'        => $contact_info['email'],
            'contact_email_icon'   => $contact_info['email_icon'],
            'contact_phone'        => $contact_info['phone'],
            'contact_phone_icon'   => $contact_info['phone_icon'],
            'whatsapp_section'     => $whatsapp_section,
            'whatsapp_text'        => $whatsapp_section['text'],
            'whatsapp_url'         => $whatsapp_section['url'],
            'whatsapp_btn_txt'     => $whatsapp_section['btn_text'],
        ];
    }
}
