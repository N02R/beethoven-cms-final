<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class FinancialModel {
    /**
     * جلب وتجهيز بيانات صفحة الضمانات المالية والحساب البنكي المغلق
     */
    public static function getFinancialData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات تحت مفتاح 'financial_page'
        $data = isset($settings['financial_page']) ? json_decode($settings['financial_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
