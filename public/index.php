<?php
declare(strict_types=1);

// تفعيل رؤوس الأمان الأوروبية (Security Headers)
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// تسجيل Autoloader الشامل
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';
    $configDir = __DIR__ . '/../config/';

    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relativeClass = substr($class, strlen($prefix));
        
        // التحقق إن كان الكلاس تابعاً لإعدادات التكوين أو النواة والمتحكمات
        if (strpos($relativeClass, 'Config\\') === 0) {
            $file = $configDir . str_replace(['Config\\', '\\'], ['', '/'], $relativeClass) . '.php';
        } else {
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        }

        if (file_exists($file)) {
            require_once $file;
        }
    }
});

require_once __DIR__ . '/../src/Core/Router.php';

use App\Core\Router;
use App\Controllers\HomeController;

$router = new Router();

// تعريف المسارات الأساسية مع دعم اللغات
$router->add('GET', '', [HomeController::class, 'index']);
$router->add('GET', 'home', [HomeController::class, 'index']);

$uri = $_GET['url'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
