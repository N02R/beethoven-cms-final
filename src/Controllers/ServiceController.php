<?php
declare(strict_types=1);

namespace App\Controllers;

class ServiceController {
    
    public function germanCourses(string $lang = 'de'): void {
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');
        
        // استخدام المسار المحدث بعد نقل مجلد edu-services إلى داخل public
        $fileToInclude = realpath(__DIR__ . '/../../public/edu-services/germanlang.php');
        
        if ($fileToInclude && file_exists($fileToInclude)) {
            require_once $fileToInclude;
            exit;
        } else {
            http_response_code(404);
            echo "<h1>404 - File Not Found</h1>";
            echo "<p>Attempted path: " . htmlspecialchars(__DIR__ . '/../../public/edu-services/germanlang.php', ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }
}
