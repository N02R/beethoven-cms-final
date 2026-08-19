<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class CvModel {
    
    /**
     * جلب بيانات صفحة السيرة الذاتية من الإعدادات المركزية
     */
    public static function getCvData(): array {
        $settings = SiteModel::getSettings();
        
        $cvData = isset($settings['cv_page']) ? json_decode($settings['cv_page'], true) : [];
        
        // القيم الافتراضية في حال عدم وجود سجل مسبق
        return array_merge([
            'page_breadcrumb'     => 'السيرة الذاتية CV',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg2.jpg',
            'main_title'          => 'السيرة الذاتية CV',
            'main_desc'           => 'السيرة الذاتية هي بوابتك الأولى للقبول في الجامعات أو الحصول على فرص تدريب في ألمانيا.',
            'advice_title'        => 'نصائح سريعة لكتابة CV فعّال',
            'advice_points'       => [
                'استخدم تنسيقاً بسيطاً وواضحاً.',
                'اذكر بيانات الاتصال بوضوح.',
                'ركز على المهارات ذات الصلة.'
            ],
            'note_title'          => 'ملاحظات هامة !!',
            'notes'               => [
                'استخدم تنسيق PDF لضمان بقاء التنسيق ثابتاً.',
                'اجعل السيرة الذاتية مركزة على التخصص المطلوب.'
            ],
            'download_items'      => [
                ['type' => 'pdf', 'title' => 'نموذج سيرة ذاتية احترافي', 'sub' => 'Example', 'file' => '#'],
                ['type' => 'word', 'title' => 'نموذج سيرة ذاتية احترافي', 'sub' => 'Example', 'file' => '#']
            ]
        ], is_array($cvData) ? $cvData : []);
    }
}
