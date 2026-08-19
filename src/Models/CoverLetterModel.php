<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class CoverLetterModel {
    
    /**
     * جلب بيانات صفحة خطاب الطلب من الإعدادات المركزية
     */
    public static function getCoverLetterData(): array {
        $settings = SiteModel::getSettings();
        
        $coverData = isset($settings['coverletter_page']) ? json_decode($settings['coverletter_page'], true) : [];
        
        // القيم الافتراضية في حال عدم وجود سجل مسبق
        return array_merge([
            'page_breadcrumb'     => 'خطاب الطلب',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg1.jpg',
            'main_title'          => 'رسالة تعريف/خطاب طلب احترافي يدعم طلبك، أياً كان هدفك أو وجهتك',
            'main_desc'           => '',
            'advice_title'        => 'النقاط التي يجب مراعاتها عند كتابة رسالة التعريف',
            'advice_points'       => [],
            'note_title'          => 'ملاحظات هامة !!',
            'notes'               => [],
            'download_items'      => []
        ], is_array($coverData) ? $coverData : []);
    }
}
