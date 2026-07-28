<?php
declare(strict_types=1);

namespace App\Controllers;

class HomeController {
    public function index(string $lang = 'de'): void {
        // حماية مخرجات اللغة المعروضة
        $lang = htmlspecialchars($lang, ENT_QUOTES, 'UTF-8');
        
        // مؤقتاً سنقوم بعرض رسالة ترحيبية تؤكد نجاح نظام التوجيه واللغات
        echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
        echo "<h1>Beethoven CMS - Production Environment</h1>";
        echo "<p>Aktuelle Sprache (Current Language): <strong>" . strtoupper($lang) . "</strong></p>";
        echo "<p style='color: green;'>Router & Multilingual Engine successfully initialized.</p>";
        echo "</div>";
    }
}
