<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class AboutModel {
    /**
     * جلب وتجهيز بيانات أقسام صفحة "عن الشركة" (About) المتوافقة مع لوحة التحكم
     */
    public static function getAboutData(): array {
        // جلب كافة الإعدادات باستخدام المودل المركزي SiteModel
        $settings = SiteModel::getSettings();
        
        // تجهيز بيانات القسم الرئيسي "من نحن" وتجميعها في مصفوفة فرعية تتطابق مع $ab في الـ View
        $about_section = [
            'title'       => $settings['about_title'] ?? 'من نحن',
            'desc'        => $settings['about_desc'] ?? '',
            'btn_text'    => $settings['about_btn_text'] ?? 'قراءة المزيد',
            'btn_url'     => $settings['about_btn_url'] ?? '#',
            'main_img'    => $settings['about_main_img'] ?? '',
            'sub_img'     => $settings['about_sub_img'] ?? '',
            'vision_title'=> $settings['vision_title'] ?? 'رؤية الشركة',
            'vision_desc' => $settings['vision_desc'] ?? '',
            'vision_icon' => $settings['vision_icon'] ?? '',
            'message_title'=> $settings['message_title'] ?? 'رسالة الشركة',
            'message_desc' => $settings['message_desc'] ?? '',
            'message_icon' => $settings['message_icon'] ?? '',
        ];

        // إرجاع مصفوفة منسقة تحتوي على كافة المفاتيح التي تستخدمها نوافذ لوحة التحكم وعرض الصفحة
        return [
            'about'          => $about_section,
            'team_title'     => $settings['team_title'] ?? 'فريق العمل',
            'team_desc'      => $settings['team_desc'] ?? '',
            'team_members'   => isset($settings['team_members']) ? json_decode($settings['team_members'], true) : [],
            'about_counts'   => isset($settings['about_counts']) ? json_decode($settings['about_counts'], true) : [],
            'partners_title' => $settings['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا',
            'partners_items' => isset($settings['partners_items']) ? json_decode($settings['partners_items'], true) : [],
        ];
    }
}
