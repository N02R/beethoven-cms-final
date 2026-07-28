<?php
declare(strict_types=1);

namespace App\Controllers;

class ServiceController {
    
    public function germanCourses(string $lang = 'de'): void {
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');
        
        // المسار الدقيق من src/Controllers/ إلى مجلد edu-services في جذر المشروع
        $fileToInclude = __DIR__ . '/../../../edu-services/germanlang.php';
        
        if (file_exists($fileToInclude)) {
            require_once $fileToInclude;
        } else {
            http_response_code(404);
            echo "<h1>404 - Service File Not Found</h1>";
            echo "<p style='color: gray;'>Checked path: " . htmlspecialchars($fileToInclude, ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }
}
