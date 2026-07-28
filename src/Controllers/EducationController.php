<?php
declare(strict_types=1);

namespace App\Controllers;

class EducationController {
    
    public function index(string $lang = 'de'): void {
        // يمكنكِ لاحقاً جلب البيانات من القاعدة أو ملفات الكونفجريشن بحسب اللغة ($lang)
        $data = []; 
        
        // المتغيرات الخاصة بلوحة التحكم (يمكن ربطها بنظام الجلسات لاحقاً)
        $is_admin = $_SESSION['is_admin'] ?? false;

        // تحديد مسار الـ View الخاص بصفحة التعليم العالي
        $viewFile = __DIR__ . '/../Views/education.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            http_response_code(404);
            echo "<h1>404 - View Not Found</h1>";
        }
    }
}
