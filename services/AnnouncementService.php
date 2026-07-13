<?php
// services/AnnouncementService.php

class AnnouncementService {
    private $db;

    // محاكاة مؤقتة لقاعدة البيانات لحين ربط PDO الفعلي لتجنب توقف السيرفر المدمج
    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    public function getActiveAnnouncement() {
        $now = date('Y-m-d H:i:s');
        
        // عند ربط قاعدة البيانات الفعلي، يتم تفعيل الاستعلام التالي:
        /*
        $stmt = $this->db->prepare("SELECT * FROM announcements 
            WHERE status = 'Published' 
            AND (start_date IS NULL OR start_date <= :now) 
            AND (end_date IS NULL OR end_date >= :now2) 
            LIMIT 1");
        $stmt->execute(['now' => $now, 'now2' => $now]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        */

        // محاكاة إعلان نصي نشط حالياً للمعاينة (تغيير نوعه لـ image للاختبار)
        return [
            'type' => 'text',
            'status' => 'Published',
            'announcement_text' => 'مرحباً بكم في بيتهوفن سيتي! احصل على استشارتك المجانية اليوم.',
            'link' => 'https://wa.me/4917671230666',
            'open_new_tab' => 1,
            'bg_color' => '#f8f9fa',
            'text_color' => '#0d6efd',
            'font_size' => 14
        ];
    }

    public function renderHeaderAnnouncement() {
        $ad = $this->getActiveAnnouncement();
        if (!$ad || $ad['status'] !== 'Published') {
            return '';
        }

        $target = $ad['open_new_tab'] ? 'target="_blank" rel="noopener"' : '';
        $hasLink = !empty($ad['link']);

        // بداية الحاوية المنسقة برمجياً لعدم تشويه الهيدر
        $html = '<div class="announcement-wrapper w-100 text-center px-2" style="max-width: 500px; margin: 0 auto;">';

        if ($ad['type'] === 'text') {
            $style = "background-color: {$ad['bg_color']}; color: {$ad['text_color']}; font-size: {$ad['font_size']}px; padding: 6px 12px; border-radius: 6px; display: inline-block; width: 100%; font-weight: bold; text-decoration: none;";
            
            if ($hasLink) {
                $html .= "<a href='{$ad['link']}' {$target} style='{$style}'>" . htmlspecialchars($ad['announcement_text']) . "</a>";
            } else {
                $html .= "<span style='{$style}'>" . htmlspecialchars($ad['announcement_text']) . "</span>";
            }
        } 
        
        else if ($ad['type'] === 'image') {
            $imgStyle = "max-height: 60px; object-fit: contain; width: 100%; border-radius: 4px;";
            if ($hasLink) {
                $html .= "<a href='{$ad['link']}' {$target}><img src='{$ad['image_path']}' alt='" . htmlspecialchars($ad['alt_text']) . "' style='{$imgStyle}'></a>";
            } else {
                $html .= "<img src='{$ad['image_path']}' alt='" . htmlspecialchars($ad['alt_text']) . "' style='{$imgStyle}'>";
            }
        }

        $html .= '</div>';
        return $html;
    }
}
