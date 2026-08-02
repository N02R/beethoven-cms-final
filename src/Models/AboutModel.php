<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class AboutModel {
    /**
     * جلب وتجهيز بيانات صفحة "من نحن" والأقسام التابعة لها
     */
    public static function getAboutData(): array {
        // جلب كافة الإعدادات باستخدام المودل المركزي
        $settings = SiteModel::getSettings();
        
        // إرجاع مصفوفة منسقة تحتوي على أقسام صفحة من نحن فقط
        return [
            'about'         => isset($settings['about_section']) ? json_decode($settings['about_section'], true) : [],
            'team_title'    => $settings['team_title'] ?? 'فريق العمل',
            'team_desc'     => $settings['team_desc'] ?? '',
            'team_members'  => isset($settings['team_items']) ? json_decode($settings['team_items'], true) : [],
            'about_counts'  => isset($settings['about_counts']) ? json_decode($settings['about_counts'], true) : [],
            'partners_title'=> $settings['partners_title'] ?? '',
            'partners_items'=> isset($settings['partners_items']) ? json_decode($settings['partners_items'], true) : [],
        ];
    }
}
