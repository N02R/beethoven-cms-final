<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class OffersModel {
    /**
     * جلب وتجهيز بيانات صفحة العروض والاتفاقيات
     */
    public static function getOffersData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات من الإعدادات المركزية
        $data = isset($settings['offers_page']) ? json_decode($settings['offers_page'], true) : [];

        // القيم الافتراضية في حال كانت البيانات فارغة أو غير موجودة
        $default_data = [
            'page_breadcrumb'     => 'العروض والاتفاقيات',
            'page_breadcrumb_url' => '#',
            'hero_img'            => 'assets/img/education/servicesimg10.png',
            'hero_position'       => 'center center',
            'main_title'          => 'العروض والاتفاقيات',
            'main_desc'           => 'كل عرضٍ (حالة) له تكلفة الخدمة الخاصة به حيث أن كل عرض يتضمن خدمات مختلفة وبذلك يتطلب إجراءات ومراسلات وجهود مختلفة. للحصول على فكرةٍ عامة عن العرض الخاص بك وتكلفة الخدمات الخاصة به، تجد أدناه العروض الأكثر طلباً (مثال لكل عرض).',
            'note_title'          => 'ملاحظات هامة !!',
            'note_text'           => 'جميع العروض والاتفاقيات تكتب وتملأ باللغة الإنجليزية، للإستفسار عن أي بند أو شرح أي معلومات، لا تتردد <a href="contact.php" class="fw-bold" style="color: #66aeee; text-decoration: none;">بالتواصل معنا</a>.',
            'download_cards'      => [
                [
                    'title' => 'بكالوريوس',
                    'file'  => 'assets/files/BCS-bachelor.pdf',
                    'sub'   => 'حزمة واتفاقية البكالوريوس',
                    'active'=> false
                ],
                [
                    'title' => 'الماجستير',
                    'file'  => 'assets/files/BCS-master.pdf',
                    'sub'   => 'حزمة واتفاقية الماجستير',
                    'active'=> true
                ],
                [
                    'title' => 'الدكتوراه',
                    'file'  => 'assets/files/BCS-phd.pdf',
                    'sub'   => 'حزمة واتفاقية الدكتوراه',
                    'active'=> false
                ]
            ]
        ];

        // دمج البيانات المحفوظة مع الافتراضية لضمان عدم وجود مفاتيح ناقصة
        return array_merge($default_data, is_array($data) ? $data : []);
    }
}
