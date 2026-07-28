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
use App\Controllers\ServiceController;
use App\Controllers\AboutController;
use App\Controllers\EducationController;
use App\Controllers\JobController;
use App\Controllers\GuideController; // أضفنا متحكم الدليل الشامل

$router = new Router();

// تسجيل المسارات النظيفة والإنتاجية (Clean URLs)
$router->add('GET', '', [HomeController::class, 'index']);             // للمسار الجذر (الصفحة الرئيسية)
$router->add('GET', 'home', [HomeController::class, 'index']);           // لمسار home
$router->add('GET', 'about', [AboutController::class, 'index']);         // لمسار صفحة من نحن
$router->add('GET', 'education', [EducationController::class, 'index']); // لمسار صفحة التعليم العالي
$router->add('GET', 'job', [JobController::class, 'index']);             // لمسار صفحة التدريب المهني
$router->add('GET', 'guide', [GuideController::class, 'index']);         // لمسار صفحة الدليل الشامل
$router->add('GET', 'services/german-language-courses', [ServiceController::class, 'germanCourses']);

// معالجة الـ URI الوارد بدقة تامة
$uri = $_GET['url'] ?? '';
if ($uri === '/' ) {
    $uri = '';
}

$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
