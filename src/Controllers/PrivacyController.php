<?php
namespace App\Controllers;

class PrivacyController {
    public function index() {
        // يمكنك تمرير أي بيانات إضافية للـ View إذا احتجتِ
        $data = [
            'site_title' => 'سياسة الخصوصية | Beethoven City Services'
        ];
        
        // استدعاء الـ View الخاص بصفحة الخصوصية الذي أنشأناه سابقاً
        require_once __DIR__ . '/../Views/privacy.php';
    }
}
