<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class MedicalModel {
    /**
     * جلب وتجهيز بيانات صفحة التدريب الطبي (باقة التدريب الطبي)
     */
    public static function getMedicalData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات باستخدام المفتاح الصحيح المطابق بقاعدة البيانات
        $data = isset($settings['medical_packages_page']) ? json_decode($settings['medical_packages_page'], true) : [];

        // قيم افتراضية لضمان عمل الصفحة بسلاسة تامة وعدم وجود بيانات فارغة
        $default = [
            "page_breadcrumb" => "باقة التدريب الطبي",
            "page_breadcrumb_url" => "#",
            "hero_img" => "assets/img/job/servicesimg3.png",
            "hero_position" => "center center",
            "main_title" => "باقة التدريب الطبي الشاملة في ألمانيا",
            "main_desc" => "نوفر لك فرصة مميزة للحصول على تدريب طبي احترافي في المستشفيات والمراكز الطبية الألمانية، مع دعم كامل في كافة الإجراءات الإدارية والتنظيمية لتحقيق طموحك المهني.",
            "note_text" => "للاطلاع على تفاصيل الاتفاقية وشروط التسجيل، يرجى الاطلاع على الملف المرفق أدناه أو بالتواصل معنا.",
            "download_item" => [
                "title" => "عرض واتفاقية التدريب الطبي",
                "type" => "pdf",
                "sub" => "",
                "file" => "assets/files/medical_training_agreement.pdf"
            ]
        ];

        return array_merge($default, is_array($data) ? $data : []);
    }
}
