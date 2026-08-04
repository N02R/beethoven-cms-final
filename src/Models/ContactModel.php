<?php
declare(strict_types=1);

namespace App\Models;

class ContactModel {
    /**
     * جلب إعدادات صفحة التواصل من قاعدة البيانات عبر SiteModel
     */
    public static function getContactData(): array {
        $settings = SiteModel::getSettings();

        return [
            'contact_hero_img'     => $settings['contact_hero_img'] ?? 'assets/img/contact us/contacthero.png',
            'contact_address'      => $settings['contact_address'] ?? 'Rheinweg 140 ,53129 Bonn,Germany',
            'contact_address_icon' => $settings['contact_address_icon'] ?? 'assets/img/Location.svg',
            'contact_email'        => $settings['contact_email'] ?? 'info@Beethoven-City-Services.com',
            'contact_email_icon'   => $settings['contact_email_icon'] ?? 'assets/img/Mail.svg',
            'contact_phone'        => $settings['contact_phone'] ?? '666-230-71 176 (0) 49+',
            'contact_phone_icon'   => $settings['contact_phone_icon'] ?? 'assets/img/Call.svg',
            'whatsapp_text'        => $settings['whatsapp_text'] ?? 'نحن في Beethoven City نؤمن أن التواصل المباشر هو الأفضل.. لذلك نوفر لك قنوات تواصل واضحة وآمنة بدون أي نماذج أو جمع بيانات',
            'whatsapp_url'         => $settings['whatsapp_url'] ?? 'https://wa.me/4917671230666',
            'whatsapp_btn_txt'     => $settings['whatsapp_btn_txt'] ?? 'تواصل معنا عبر واتساب',
        ];
    }
}
