<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/**
 * Beethoven CMS - Entry Point (public/index.php)
 * إدارة الجلسات الآمنة ورؤوس الأمان والتوجيه المركزي للمشروع
 */

// 1. تطبيق معايير الأمان لإعدادات الجلسة (Secure Session Cookie Setup)
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    
    session_set_cookie_params([
        'lifetime' => 0,                    // تنتهي الجلسة بإغلاق المتصفح
        'path'     => '/',
        'domain'   => '',                   
        'secure'   => $isSecure,            // HTTPS حصرياً عند توفره
        'httponly' => true,                 // حماية من هجمات XSS
        'samesite' => 'Strict'              // حماية صارمة ضد هجمات CSRF
    ]);

    session_start();

    // حماية من تثبيت الجلسة (Session Fixation): تجديد ID الجلسة كل 30 دقيقة
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }
}

// 2. تفعيل رؤوس الأمان الشاملة (Security Headers)
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// 3. تسجيل Autoloader الشامل للنظام
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relativeClass = substr($class, strlen($prefix));

        // معالجة كلاسات التهيئة وقواعد البيانات (Config & Database)
        if (strpos($relativeClass, 'Config\\') === 0) {
            $file = __DIR__ . '/../' . str_replace(['Config\\', '\\'], ['database/', '/'], $relativeClass) . '.php';
            if (!file_exists($file)) {
                $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            }
        } else {
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        }

        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// 4. تضمين ملف الدوال المساعدة العامة (Helpers)
require_once __DIR__ . '/../src/Core/helpers.php';
require_once __DIR__ . '/../src/Core/Router.php';

use App\Core\Router;

// Controllers الخاصة بالواجهة الأمامية
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
use App\Controllers\Services\MotivationLetterController;
use App\Controllers\Services\OffersPageController;
use App\Controllers\Services\ServiceCostController;
use App\Controllers\Services\MedicalPackageController;
use App\Controllers\Services\JobSearchAgreementController;
use App\Controllers\Services\MedicalSpecialtiesController;
use App\Controllers\Services\AusbildungPackageController;
use App\Controllers\Guide\GuideBlog1Controller;
use App\Controllers\Guide\GuideBlog2Controller;
use App\Controllers\Guide\GuideBlog3Controller;

// Controllers الخاصة بلوحة التحكم (Admin)
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\ConfigController;
use App\Controllers\Admin\UploadController;
use App\Controllers\Admin\AuthController;

$router = new Router();

// ==========================================
// 1. مسارات الواجهة الأمامية (Public Routes)
// ==========================================
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
$router->add('GET', 'edu-services/motivitionletter', [MotivationLetterController::class, 'index']);
$router->add('GET', 'edu-services/pakeges', [OffersPageController::class, 'index']);
$router->add('GET', 'edu-services/services-cost', [ServiceCostController::class, 'index']);
$router->add('GET', 'job-services/medical-pakeges', [MedicalPackageController::class, 'index']);
$router->add('GET', 'job-services/medical-traning', [JobSearchAgreementController::class, 'index']);
$router->add('GET', 'job-services/medical', [MedicalSpecialtiesController::class, 'index']);
$router->add('GET', 'job-services/vocational', [AusbildungPackageController::class, 'index']);
$router->add('GET', 'guide/guide-blog1', [GuideBlog1Controller::class, 'index']);
$router->add('GET', 'guide/guide-blog2', [GuideBlog2Controller::class, 'index']);
$router->add('GET', 'guide/guide-blog3', [GuideBlog3Controller::class, 'index']);

// ==========================================
// 2. مسارات لوحة التحكم (Admin Routes)
// ==========================================
// ==========================================
// 2. مسارات لوحة التحكم (Admin Routes)
// ==========================================
$router->add('GET', 'admin/login', [AuthController::class, 'login']);
$router->add('POST', 'admin/login/process', [AuthController::class, 'authenticate']);
$router->add('GET', 'admin/dashboard', [DashboardController::class, 'index']);
$router->add('GET', 'admin/settings', [SettingsController::class, 'index']);
$router->add('POST', 'admin/settings/save', [SettingsController::class, 'save']);
$router->add('POST', 'admin/upload-image', [UploadController::class, 'uploadImage']);
$router->add('GET', 'admin/logout', [DashboardController::class, 'logout']);
$router->add('GET', 'admin/verify-2fa', [AuthController::class, 'show2fa']);
$router->add('POST', 'admin/verify-2fa', [AuthController::class, 'verify2fa']);


// ==========================================
// 3. معالجة الـ URI والـ Dispatch
// ==========================================
$uri = $_GET['url'] ?? '';
if ($uri === '/') {
    $uri = '';
}

$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
