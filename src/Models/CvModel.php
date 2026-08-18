<?php
// src/Models/CvModel.php

class CvModel {
    
    public static function getCvData($db) {
        $stmt = $db->prepare("SELECT content_data FROM site_pages WHERE page_key = 'cv'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && !empty($result['content_data'])) {
            $decoded = json_decode($result['content_data'], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        
        // القيم الافتراضية في حال عدم وجود سجل مسبق
        return [
            'page_breadcrumb' => 'السيرة الذاتية CV',
            'page_breadcrumb_url' => '#',
            'hero_img' => 'assets/img/education/servicesimg2.jpg',
            'main_title' => 'السيرة الذاتية CV',
            'main_desc' => 'السيرة الذاتية هي بوابتك الأولى للقبول في الجامعات أو الحصول على فرص تدريب في ألمانيا.',
            'advice_title' => 'نصائح سريعة لكتابة CV فعّال',
            'advice_points' => [
                'استخدم تنسيقاً بسيطاً وواضحاً.',
                'اذكر بيانات الاتصال بوضوح.',
                'ركز على المهارات ذات الصلة.'
            ],
            'note_title' => 'ملاحظات هامة !!',
            'notes' => [
                'استخدم تنسيق PDF لضمان بقاء التنسيق ثابتاً.',
                'اجعل السيرة الذاتية مركزة على التخصص المطلوب.'
            ],
            'download_items' => [
                ['type' => 'pdf', 'title' => 'نموذج سيرة ذاتية احترافي', 'sub' => 'Example', 'file' => '#'],
                ['type' => 'word', 'title' => 'نموذج سيرة ذاتية احترافي', 'sub' => 'Example', 'file' => '#']
            ]
        ];
    }
}
