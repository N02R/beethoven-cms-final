<?php
declare(strict_types=1);

namespace App\Models;

class ContactModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    /**
     * جلب وتجهيز بيانات صفحة "تواصل معنا" مع دعم مصفوفة بطاقات التواصل الديناميكية والتوافق مع البيانات القديمة
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

        // 2. معلومات التواصل الديناميكية (Contact Info Items)
        $contact_info_raw = $getLangData('contact_info', []);
        $contact_items = self::normalizeContactItems($contact_info_raw);

        $contact_info = [
            'title' => (string)($contact_info_raw['title'] ?? $fallbackLabels['info_title']),
            'desc'  => (string)($contact_info_raw['desc'] ?? ''),
            'items' => $contact_items,
        ];

        // 3. قسم الواتساب (WhatsApp Section)
        $whatsapp_raw = $getLangData('whatsapp_section', []);
        $whatsapp_section = [
            'text'     => (string)($whatsapp_raw['text'] ?? $fallbackLabels['whatsapp_text']),
            'url'      => (string)($whatsapp_raw['url'] ?? 'https://wa.me/49123456789'),
            'btn_text' => (string)($whatsapp_raw['btn_text'] ?? $fallbackLabels['whatsapp_btn']),
        ];

        return [
            'contact_hero'       => $contact_hero,
            'contact_hero_img'   => $contact_hero['img'],
            'contact_info'       => $contact_info,
            'contact_info_title' => $contact_info['title'],
            'contact_info_desc'  => $contact_info['desc'],
            'contact_info_items' => $contact_info['items'],
            'whatsapp_section'   => $whatsapp_section,
            'whatsapp_text'      => $whatsapp_section['text'],
            'whatsapp_url'       => $whatsapp_section['url'],
            'whatsapp_btn_txt'   => $whatsapp_section['btn_text'],
        ];
    }

    /**
     * توحيد عناصر التواصل سواء كانت مخزنة كمصفوفة items حديثة أو حقول قديمة (address/email/phone)
     */
    public static function normalizeContactItems(array $rawInfo): array
    {
        if (isset($rawInfo['items']) && is_array($rawInfo['items'])) {
            $normalized = [];
            foreach ($rawInfo['items'] as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $text = trim((string)($item['text'] ?? ($item['value'] ?? '')));
                $url  = trim((string)($item['url'] ?? ''));
                $icon = trim((string)($item['icon'] ?? ($item['img'] ?? 'assets/img/Location.svg')));
                $isLtr = !empty($item['is_ltr']) && ($item['is_ltr'] === true || $item['is_ltr'] === '1' || $item['is_ltr'] === 1);

                $normalized[] = [
                    'text'   => $text,
                    'url'    => $url,
                    'icon'   => $icon !== '' ? $icon : 'assets/img/Location.svg',
                    'is_ltr' => $isLtr,
                ];
            }
            return $normalized;
        }

        // ترحيل تلقائي من الصيغة القديمة (address / email / phone) إن وُجدت
        $legacyItems = [];

        $addr = trim((string)($rawInfo['address'] ?? ''));
        if ($addr !== '' || !empty($rawInfo['address_icon'])) {
            $legacyItems[] = [
                'text'   => $addr !== '' ? $addr : 'Rheinweg 140, 53129 Bonn, Germany',
                'url'    => '',
                'icon'   => (string)($rawInfo['address_icon'] ?? 'assets/img/Location.svg'),
                'is_ltr' => false,
            ];
        }

        $email = trim((string)($rawInfo['email'] ?? ''));
        if ($email !== '' || !empty($rawInfo['email_icon'])) {
            $cleanEmail = $email !== '' ? $email : 'info@beethoven-services.de';
            $legacyItems[] = [
                'text'   => $cleanEmail,
                'url'    => 'mailto:' . $cleanEmail,
                'icon'   => (string)($rawInfo['email_icon'] ?? 'assets/img/Mail.svg'),
                'is_ltr' => true,
            ];
        }

        $phone = trim((string)($rawInfo['phone'] ?? ''));
        if ($phone !== '' || !empty($rawInfo['phone_icon'])) {
            $cleanPhone = $phone !== '' ? $phone : '+49 123 456789';
            $telDigits  = preg_replace('/[^0-9+]/', '', $cleanPhone) ?? '';
            $legacyItems[] = [
                'text'   => $cleanPhone,
                'url'    => $telDigits !== '' ? ('tel:' . $telDigits) : '',
                'icon'   => (string)($rawInfo['phone_icon'] ?? 'assets/img/Call.svg'),
                'is_ltr' => true,
            ];
        }

        if (!empty($legacyItems)) {
            return $legacyItems;
        }

        // قيم افتراضية نظيفة في حال كانت قاعدة البيانات فارغة تماماً
        return [
            [
                'text'   => 'Rheinweg 140, 53129 Bonn, Germany',
                'url'    => '',
                'icon'   => 'assets/img/Location.svg',
                'is_ltr' => false,
            ],
            [
                'text'   => 'info@beethoven-services.de',
                'url'    => 'mailto:info@beethoven-services.de',
                'icon'   => 'assets/img/Mail.svg',
                'is_ltr' => true,
            ],
            [
                'text'   => '+49 123 456789',
                'url'    => 'tel:+49123456789',
                'icon'   => 'assets/img/Call.svg',
                'is_ltr' => true,
            ],
        ];
    }
}
