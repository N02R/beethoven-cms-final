<?php
// src/Models/CoverLetterModel.php

class CoverLetterModel {
    
    public static function getCoverLetterData($db) {
        $stmt = $db->prepare("SELECT content_data FROM site_pages WHERE page_key = 'coverletter'");
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
            'page_breadcrumb' => 'خطاب الطلب',
            'page_breadcrumb_url' => '#',
            'hero_img' => 'assets/img/education/servicesimg1.jpg',
            'main_title' => 'رسالة تعريف/خطاب طلب احترافي',
            'main_desc' => '',
            'advice_title' => 'النقاط التي يجب مراعاتها',
            'advice_points' => [],
            'note_title' => 'ملاحظات هامة !!',
            'notes' => [],
            'download_items' => []
        ];
    }
}
