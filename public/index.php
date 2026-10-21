<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Entry Point (public/index.php)
 * النسخة الإنتاجية الآمنة المتوافقة مع معايير الجودة والأمان الألمانية ومعالجة ملف البيئة .env
 */

// 1. دالة قراءة ملف البيئة .env وتحميله إلى مصفوفة $_ENV بسلامة تامة
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // تخطي التعليقات التي تبدأ بعلامة #
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        // تقسيم السطر إلى مفتاح وقيمة بناءً على علامة التساوي =
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $name = trim($parts[0]);
            $value = trim($parts[1], "\"' \t\n\r\0\x0B"); // تنظيف القيم من علامات التنصيص والفراغات

            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// 2. تحديد بيئة العمل بناءً على ما تم قراءته من ملف .env
$appEnv = $_ENV['APP_ENV'] ?? 'local'; 

if ($appEnv === 'production') {
    // إيقاف إظهار الأخطاء للمستخدم نهائياً في السيرفر الحقيقي (حماية من تسريب البيانات)
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
} else {
    // تفعيل عرض الأخطاء فقط أثناء التطوير المحلي
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}

// 3. تطبيق معايير الأمان الصارمة للجلسات (Secure Session Cookie Setup & DSGVO Compliance)
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    
    session_set_cookie_params([
        'lifetime' => 0,                    // تنتهي الجلسة بإغلاق المتصفح
        'path'     => '/',
        'domain'   => '',                   
        'secure'   => $isSecure,            // تفعيل HTTPS إلزامي عند توفره
        'httponly' => true,                 // منع الوصول عبر جافاسكريبت (حماية XSS)
        'samesite' => 'Strict'              // حماية صارمة جداً ضد هجمات CSRF
    ]);

    session_start();

    // حماية من تثبيت الجلسة (Session Fixation): تجديد ID الجلسة دورياً كل 30 دقيقة
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }
}

// 4. تفعيل رؤوس الأمان الشاملة (Security Headers) لحماية المتصفح
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.gstatic.com 'unsafe-inline' 'unsafe-eval'; img-src 'self' data:;");

// 5. تحسين وتسجيل Autoloader الموحد للنظام
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relativeClass = substr($class, strlen($prefix));
        
        // مسار موحد ونظيف لجميع كلاسات المشروع دون ازدواجية
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// 6. تضمين ملف الدوال المساعدة الأساسية والراوتر
require_once __DIR__ . '/../src/Core/helpers.php';
require_once __DIR__ . '/../src/Core/Router.php';

use App\Core\Router;

// استدعاء المتحكمات (Controllers)
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
use App\Controllers\Services\OffersController;
use App\Controllers\Services\LivingCostController;
use App\Controllers\Services\MotivationController;
use App\Controllers\Services\ServiceCostController;
use App\Controllers\Services\MedicalPackageController;
use App\Controllers\Services\MedicalSpecialtiesController;
use App\Controllers\Services\AusbildungPackageController;
use App\Controllers\Services\HealthController;
use App\Controllers\Services\JobAgreementsController;
use App\Controllers\Guide\GuideBlog1Controller;
use App\Controllers\Guide\GuideBlog2Controller;
use App\Controllers\Guide\GuideBlog3Controller;
use App\Controllers\MediaController;
use App\Controllers\LegalController;
use App\Controllers\ConsultationController;
// متحكمات لوحة التحكم والأدمن
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\UploadController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\AnnouncementController;

$router = new Router();

// ==========================================
// أوبشن 1: مسارات الواجهة الأمامية والخدمات العامة
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
$router->add('GET', 'edu-services/health', [HealthController::class, 'index']);
$router->add('GET', 'edu-services/living', [LivingCostController::class, 'index']);
$router->add('GET', 'edu-services/motivitionletter', [MotivationController::class, 'index']);
$router->add('GET', 'edu-services/pakeges', [OffersController::class, 'index']);
$router->add('GET', 'edu-services/services-cost', [ServiceCostController::class, 'index']);
$router->add('GET', 'edu-services/medical-pakeges', [MedicalPackageController::class, 'index']);
$router->add('GET', 'edu-services/medical-traning', [JobAgreementsController::class, 'index']);
$router->add('GET', 'edu-services/medical', [MedicalSpecialtiesController::class, 'index']);
$router->add('GET', 'edu-services/vocational', [AusbildungPackageController::class, 'index']);

// مسار استقبال طلبات الاستشارة (متاح للجميع POST)
$router->add('POST', 'send-consult', [ConsultationController::class, 'store']);

// مسارات الأدلة والمقالات
$router->add('GET', 'guide/guide-blog1', [GuideBlog1Controller::class, 'index']);
$router->add('GET', 'guide/guide-blog2', [GuideBlog2Controller::class, 'index']);
$router->add('GET', 'guide/guide-blog3', [GuideBlog3Controller::class, 'index']);

// مسارات الصفحات القانونية وتغيير اللغة
$router->add('GET', 'impressum', [LegalController::class, 'impressum']);
$router->add('GET', 'datenschutz', [LegalController::class, 'datenschutz']);
$router->add('GET', 'switch-lang', [HomeController::class, 'switchLang']);
$router->add('GET', 'media/view', [MediaController::class, 'serve']);

// ==========================================
// أوبشن 2: مسارات لوحة التحكم والإدارة (Admin Routes)
// ==========================================
$router->add('GET', 'admin/login', [AuthController::class, 'login']);
$router->add('POST', 'admin/login/process', [AuthController::class, 'authenticate']);
$router->add('GET', 'admin/dashboard', [DashboardController::class, 'index']);
$router->add('POST', 'admin/upload', [UploadController::class, 'upload']);
$router->add('GET', 'admin/logout', [DashboardController::class, 'logout']);
$router->add('GET', 'admin/verify-2fa', [AuthController::class, 'show2fa']);
$router->add('POST', 'admin/verify-2fa', [AuthController::class, 'verify2fa']);
$router->add('POST', 'admin/announcement/save', [AnnouncementController::class, 'save']);
$router->add('POST', 'admin/settings/save', [SettingsController::class, 'save']);

// ==========================================
// 7. معالجة الـ URI واللغات بذكاء هندسي
// ==========================================
if (isset($_GET['url'])) {
    $uri = '/' . trim($_GET['url'], '/');
} else {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = '/' . trim(parse_url($uri, PHP_URL_PATH) ?? '', '/');
    
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
        $uri = substr($uri, strlen($scriptName));
    }
}

// استخراج بادئة اللغة إن وجدت (de, en, ar)
$uriSegments = explode('/', trim($uri, '/'));
$currentLang = 'de'; // الافتراضي للشركة الألمانية

if (!empty($uriSegments[0]) && in_array($uriSegments[0], ['ar', 'en', 'de'], true)) {
    $currentLang = $uriSegments[0];
    array_shift($uriSegments); 
    $uri = '/' . implode('/', $uriSegments);
}

// حفظ اللغة في الجلسة للاستخدام العام
$_SESSION['site_lang'] = $currentLang;

$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($uri, $method);
