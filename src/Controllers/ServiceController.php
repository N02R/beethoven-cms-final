<?php
declare(strict_types=1);

namespace App\Controllers;

class ServiceController {
    
    // عرض صفحة كورسات اللغة الألمانية وربطها بالملف الأصلي لديكِ
    public function germanCourses(string $lang = 'de'): void {
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');
        
        // تحديد مسار الملف الفعلي بناءً على هيكلة مشروعك الحالية
        $fileToInclude = __DIR__ . '/../../edu-services/germanlang.php';
        
        if (file_exists($fileToInclude)) {
            // تمرير اللغة للملف إن أمكن أو تضمينه مباشرة
            require_once $fileToInclude;
        } else {
            http_response_code(404);
            echo "<h1>404 - Service File Not Found</h1>";
        }
    }
}
