<?php
namespace App\Controllers;

use App\Core\Database;

class ContentController {
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            
            // استقبال البيانات من الـ JS
            // المفتاح الذي نرسله يكون بصيغة "section_key"
            foreach ($_POST as $key => $value) {
                // نقوم بتقسيم الاسم لنعرف القسم والحقل
                $parts = explode('_', $key);
                $section = $parts[0];
                $field = $parts[1];
                
                // حفظ البيانات في قاعدة البيانات
                $db->updateContent('home', $section, $field, $value);
            }
            
            echo json_encode(['status' => 'success']);
        }
    }
}
