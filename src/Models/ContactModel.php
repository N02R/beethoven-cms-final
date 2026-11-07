<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class ContactModel {
    /**
     * جلب وتجهيز بيانات صفحة "تواصل معنا" والأقسام التابعة لها مترجمة ومجهزة بالكامل حسب لغة الزائر الحالية
     */
    public static function getContactData(?string $lang = null): array {
        $lang = $lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de'));
        $settings = SiteModel::getSettings();
        
        // دالة استخراج وتوطين البيانات حسب لغة الزائر بالمعيار الذهبي الموحد
        $getLangData = function(string $key, array $default = []) use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $raw = $settings[$key];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            
            if (!is_array($decoded)) {
                return $default;
            }

            if (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de'])) {
                return $decoded[$lang] ?? ($decoded['de'] ?? ($decoded['ar'] ?? $default));
            }
            
            return $decoded;
        };

        // 1. قسم هيرو صفحة التواصل (Contact Hero)
        $contact_hero = $getLangData('contact_hero', []);
        $contact_hero_img = $contact_hero['img'] ?? ($settings['contact_hero_img'] ?? 'assets/img/contacthero.png');

        // 2. معلومات التواصل والأيقونات (Contact Info)
        $contact_info = $getLangData('contact_info', [
            'title'        => 'معلومات التواصل',
            'desc'         => '',
            'address'      => 'Rheinweg 140 ,53129 Bonn,Germany',
            'email'        => 'info@beethoven-services.de',
            'phone'        => '+49 123 456789',
            'address_icon' => 'assets/img/Location.svg',
            'email_icon'   => 'assets/img/Mail.svg',
            'phone_icon'   => 'assets/img/Call.svg'
        ]);

        // 3. قسم الواتساب (WhatsApp Section)
        $whatsapp_data = $getLangData('whatsapp_section', [
            'text'     => 'هل ترغب في الحصول على استشارة فورية عبر الواتساب؟ تحدث مباشرة مع خبرائنا الآن.',
            'url'      => 'https://wa.me/49123456789',
            'btn_text' => 'تواصل عبر الواتساب'
        ]);

        // إرجاع مصفوفة منسقة وشاملة تغطي كافة متغيرات صفحة التواصل بمرونة تامة
        return [
            'contact_hero'         => $contact_hero,
            'contact_hero_img'     => $contact_hero_img,
            'contact_info_title'   => $contact_info['title'] ?? 'معلومات التواصل',
            'contact_info_desc'    => $contact_info['desc'] ?? '',
            'contact_address'      => $contact_info['address'] ?? '',
            'contact_address_icon' => $contact_info['address_icon'] ?? 'assets/img/Location.svg',
            'contact_email'        => $contact_info['email'] ?? '',
            'contact_email_icon'   => $contact_info['email_icon'] ?? 'assets/img/Mail.svg',
            'contact_phone'        => $contact_info['phone'] ?? '',
            'contact_phone_icon'   => $contact_info['phone_icon'] ?? 'assets/img/Call.svg',
            'whatsapp_text'        => $whatsapp_data['text'] ?? '',
            'whatsapp_url'         => $whatsapp_data['url'] ?? '#',
            'whatsapp_btn_txt'     => $whatsapp_data['btn_text'] ?? 'تواصل عبر الواتساب',
        ];
    }
}
