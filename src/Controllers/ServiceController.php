<?php
declare(strict_types=1);

namespace App\Controllers;

class ServiceController {
    
    public function germanCourses(string $lang = 'de'): void {
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');
        
        // المسار الدقيق للوصول إلى ملف germanlang.php في جذر المشروع
        $fileToInclude = __DIR__ . '/../../../edu-services/germanlang.php';
        
        if (file_exists($fileToInclude)) {
            // تضمين الملف الفعلي الأصلي لديك
            require_once $fileToInclude;
            exit; // إيقاف التنفيذ لضمان عرض صفحتك الأصلية وحدها دون تداخل
        } else {
            http_response_code(404);
            echo "<h1>404 - File Not Found</h1>";
            echo "<p>Path checked: " . htmlspecialchars($fileToInclude, ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }
}
