<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        // 1. السماح بالوصول للملفات
        define('ALLOWED_ACCESS', true);
        
        // 2. تحميل البيانات من ملف الـ JSON
        $config_file = ROOT . 'storage/announcement_config.json';
        $data = json_decode(file_get_contents($config_file), true);
        
        // 3. تحديد حالة الأدمن (لإظهار أزرار التعديل)
        $is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        
        // 4. استدعاء الواجهة
        require_once ROOT . 'public/pages/home.php';
    }
}
