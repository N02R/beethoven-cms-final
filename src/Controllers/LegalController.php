<?php
declare(strict_types=1);

namespace App\Controllers;

class LegalController {
    public function impressum(): void {
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }
        
        // البحث عن الملف داخل مجلد partials مباشرة
        $file = __DIR__ . '/../Views/partials/impressum.php';
        
        if (file_exists($file)) {
            require_once $file;
        } else {
            http_response_code(404);
            echo "Impressum view file not found.";
        }
    }

    public function datenschutz(): void {
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }
        
        // البحث عن الملف داخل مجلد partials مباشرة
        $file = __DIR__ . '/../Views/partials/datenschutz.php';
        
        if (file_exists($file)) {
            require_once $file;
        } else {
            http_response_code(404);
            echo "Datenschutz view file not found.";
        }
    }
}
