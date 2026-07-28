<?php
declare(strict_types=1);

namespace App\Controllers;

class ServiceController {
    
    public function germanCourses(string $lang = 'de'): void {
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');
        
        // استخدام المسار المطلق من جذر المشروع بناءً على __DIR__ (الصعود مرتين فقط للوصول للجذر)
        // __DIR__ هي src/Controllers، إذن صعود مستويين يصل بنا إلى جذر المشروع beethoven-cms-final/
        $fileToInclude = realpath(__DIR__ . '/../../edu-services/germanlang.php');
        
        if ($fileToInclude && file_exists($fileToInclude)) {
            require_once $fileToInclude;
            exit;
        } else {
            http_response_code(404);
            echo "<h1>404 - File Not Found</h1>";
            echo "<p>Attempted path: " . htmlspecialchars(__DIR__ . '/../../edu-services/germanlang.php', ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }
}
