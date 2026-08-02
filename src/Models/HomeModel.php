
<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class HomeModel {
    /**
     * جلب وتجهيز بيانات أقسام الصفحة الرئيسية الخاصة فقط
     */
    public static function getHomeData(): array {
        // جلب كافة الإعدادات باستخدام المودل المركزي SiteModel
        $settings = SiteModel::getSettings();
        
        // إرجاع مصفوفة منسقة تحتوي على أقسام الصفحة الرئيسية فقط (لأن الهيدر والفوتر أصبحا يُجلبان عبر getGlobalData)
        return [
            'hero'                  => isset($settings['hero']) ? json_decode($settings['hero'], true) : [],
            'services_section_title'=> $settings['services_section_title'] ?? '',
            'services_section_desc' => $settings['services_section_desc'] ?? '',
            'services'              => isset($settings['services']) ? json_decode($settings['services'], true) : [],
            'choose_title'          => $settings['choose_title'] ?? '',
            'choose_section_desc'   => $settings['choose_section_desc'] ?? '',
            'choose_items'          => isset($settings['choose_items']) ? json_decode($settings['choose_items'], true) : [],
            'reviews_title'         => $settings['reviews_title'] ?? '',
            'reviews_items'         => isset($settings['reviews_items']) ? json_decode($settings['reviews_items'], true) : [],
            'guide_title'           => $settings['guide_title'] ?? '',
            'guide_desc'            => $settings['guide_desc'] ?? '',
            'guide_items'           => isset($settings['guide_items']) ? json_decode($settings['guide_items'], true) : [],
            'faq_title'             => $settings['faq_title'] ?? '',
            'faq_items'             => isset($settings['faq_items']) ? json_decode($settings['faq_items'], true) : [],
        ];
    }
}