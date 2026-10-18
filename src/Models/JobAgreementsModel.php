<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class JobAgreementsModel {
    /**
     * جلب وتجهيز بيانات صفحة اتفاقيات العمل والبحث عن عمل
     */
    public static function getJobAgreementsData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات العامة للموقع
        $data = isset($settings['job_agreements_page']) ? json_decode($settings['job_agreements_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
