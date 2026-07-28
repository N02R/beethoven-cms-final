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
use App\Controllers\GuideController;
use App\Controllers\ContactController;
use App\Controllers\Services\ArrivalController;
use App\Controllers\Services\BachelorPackageController;
use App\Controllers\Services\CheckController;
use App\Controllers\Services\CoursesController;
use App\Controllers\Services\CoverLetterController;
use App\Controllers\Services\CvController;
use App\Controllers\Services\EnglishLangController;
use App\Controllers\Services\FinancialController;
use App\Controllers\Services\FoundationController;
use App\Controllers\Services\GeneralVisaController;
use App\Controllers\Services\GermanLangController;
use App\Controllers\Services\OffersServiceController;
use App\Controllers\Services\LivingCostController;
use App\Controllers\Services\MotivationLetterController; // المتحكم المحدث

$router = new Router();

// تسجيل المسارات النظيفة والإنتاجية (Clean URLs)
$router->add('GET', '', [HomeController::class, 'index']);                 
$router->add('GET', 'home', [HomeController::class, 'index']);               
$router->add('GET', 'about', [AboutController::class, 'index']);             
$router->add('GET', 'education', [EducationController::class, 'index']);     
$router->add('GET', 'job', [JobController::class, 'index']);                 
$router->add('GET', 'guide', [GuideController::class, 'index']);             
$router->add('GET', 'contact', [ContactController::class, 'index']);         
$router->add('GET', 'edu-services/arrival', [ArrivalController::class, 'index']); 
$router->add('GET', 'services/german-language-courses', [ServiceController::class, 'germanCourses']);
$router->add('GET', 'edu-services/bachelor-package', [BachelorPackageController::class, 'index']);
$router->add('GET', 'edu-services/check', [CheckController::class, 'index']);
$router->add('GET', 'edu-services/courses', [CoursesController::class, 'index']);
$router->add('GET', 'edu-services/coverletter', [CoverLetterController::class, 'index']);
$router->add('GET', 'edu-services/cv', [CvController::class, 'index']);
$router->add('GET', 'edu-services/englishlang', [EnglishLangController::class, 'index']);
$router->add('GET', 'edu-services/financial', [FinancialController::class, 'index']);
$router->add('GET', 'edu-services/foundation', [FoundationController::class, 'index']);
$router->add('GET', 'edu-services/general', [GeneralVisaController::class, 'index']);
$router->add('GET', 'edu-services/germanlang', [GermanLangController::class, 'index']);
$router->add('GET', 'edu-services/health', [OffersServiceController::class, 'index']);
$router->add('GET', 'edu-services/living', [LivingCostController::class, 'index']);
$router->add('GET', 'edu-services/motivitionletter', [MotivationLetterController::class, 'index']); // مسار صفحة خطاب الدافع

// معالجة الـ URI الوارد بدقة تامة
$uri = $_GET['url'] ?? '';
if ($uri === '/' ) {
    $uri = '';
}

$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
