<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Entry Point (public/index.php)
 * مُحصّن وفق معايير الأمان الصارمة (BSI / OWASP / DSGVO Production Ready)
 */

// 1. تعريف ثابت الدخول الشرعي للنظام وإزالة ترويسة إصدار PHP
define('ALLOWED_ACCESS', true);
header_remove('X-Powered-By');
date_default_timezone_set('Europe/Berlin');

// 2. قراءة ملف البيئة .env وتحميله بأمان
$envFile = __DIR__ . '/../.env';
if (is_file($envFile) && is_readable($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }

            $parts = explode('=', $trimmed, 2);
            if (count($parts) === 2) {
                $name  = trim($parts[0]);
                $value = trim($parts[1], "\"' \t\n\r\0\x0B");

                if ($name !== '' && !array_key_exists($name, $_ENV)) {
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                    putenv("$name=$value");
                }
            }
        }
    }
}

// 3. ضبط عرض الأخطاء بناءً على بيئة العمل
$appEnv = $_ENV['APP_ENV'] ?? 'production';

if ($appEnv === 'production') {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}

// 4. تطبيق معايير الأمان الصارمة للجلسات
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    session_start();

    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } elseif (time() - (int)$_SESSION['CREATED'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }
}

// 5. تفعيل رؤوس الأمان المتقدمة المتوافقة مع معايير BSI و DSGVO الألمانية
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()');

if ($isSecure) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

header(
    "Content-Security-Policy: " .
    "default-src 'self'; " .
    "script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com 'unsafe-inline'; " .
    "style-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com 'unsafe-inline'; " .
    "font-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com data:; " .
    "img-src 'self' data: https:; " .
    "frame-src 'self' https://www.youtube-nocookie.com; " .
    "object-src 'none'; " .
    "base-uri 'self'; " .
    "form-action 'self';"
);

// 6. تسجيل Autoloader الموحد للنظام
spl_autoload_register(function (string $class): void {
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (is_file($file)) {
            require_once $file;
        }
    }
});

// 7. تضمين ملف الدوال المساعدة (بحرف H كبير للتوافق مع خوادم Linux) والراوتر
require_once __DIR__ . '/../src/Core/Helpers.php';
require_once __DIR__ . '/../src/Core/Router.php';

use App\Core\Router;

// 8. إنشاء كائن الراوتر وتسجيل المسارات
$router = new Router();

$router->add('GET', '', [\App\Controllers\HomeController::class, 'index']);
$router->add('GET', 'home', [\App\Controllers\HomeController::class, 'index']);
$router->add('GET', 'about', [\App\Controllers\AboutController::class, 'index']);
$router->add('GET', 'education', [\App\Controllers\EducationController::class, 'index']);
$router->add('GET', 'job', [\App\Controllers\JobController::class, 'index']);
$router->add('GET', 'guide', [\App\Controllers\GuideController::class, 'index']);
$router->add('GET', 'contact', [\App\Controllers\ContactController::class, 'index']);
$router->add('GET', 'edu-services/arrival', [\App\Controllers\Services\ArrivalController::class, 'index']);
$router->add('GET', 'services/german-language-courses', [\App\Controllers\ServiceController::class, 'germanCourses']);
$router->add('GET', 'edu-services/bachelor-package', [\App\Controllers\Services\BachelorPackageController::class, 'index']);
$router->add('GET', 'edu-services/check', [\App\Controllers\Services\CheckController::class, 'index']);
$router->add('GET', 'edu-services/courses', [\App\Controllers\Services\CoursesController::class, 'index']);
$router->add('GET', 'edu-services/coverletter', [\App\Controllers\Services\CoverLetterController::class, 'index']);
$router->add('GET', 'edu-services/cv', [\App\Controllers\Services\CvController::class, 'index']);
$router->add('GET', 'edu-services/englishlang', [\App\Controllers\Services\EnglishLangController::class, 'index']);
$router->add('GET', 'edu-services/financial', [\App\Controllers\Services\FinancialController::class, 'index']);
$router->add('GET', 'edu-services/foundation', [\App\Controllers\Services\FoundationController::class, 'index']);
$router->add('GET', 'edu-services/general', [\App\Controllers\Services\GeneralVisaController::class, 'index']);
$router->add('GET', 'edu-services/germanlang', [\App\Controllers\Services\GermanLangController::class, 'index']);
$router->add('GET', 'edu-services/health', [\App\Controllers\Services\HealthController::class, 'index']);
$router->add('GET', 'edu-services/living', [\App\Controllers\Services\LivingCostController::class, 'index']);
$router->add('GET', 'edu-services/motivitionletter', [\App\Controllers\Services\MotivationController::class, 'index']);
$router->add('GET', 'edu-services/pakeges', [\App\Controllers\Services\OffersController::class, 'index']);
$router->add('GET', 'edu-services/services-cost', [\App\Controllers\Services\ServiceCostController::class, 'index']);
$router->add('GET', 'edu-services/medical-pakeges', [\App\Controllers\Services\MedicalPackageController::class, 'index']);
$router->add('GET', 'edu-services/medical-traning', [\App\Controllers\Services\JobAgreementsController::class, 'index']);
$router->add('GET', 'edu-services/medical', [\App\Controllers\Services\MedicalSpecialtiesController::class, 'index']);
$router->add('GET', 'edu-services/vocational', [\App\Controllers\Services\AusbildungPackageController::class, 'index']);

$router->add('POST', 'send-consult', [\App\Controllers\ConsultationController::class, 'store']);

$router->add('GET', 'guide/guide-blog1', [\App\Controllers\Guide\GuideBlog1Controller::class, 'index']);
$router->add('GET', 'guide/guide-blog2', [\App\Controllers\Guide\GuideBlog2Controller::class, 'index']);
$router->add('GET', 'guide/guide-blog3', [\App\Controllers\Guide\GuideBlog3Controller::class, 'index']);

$router->add('GET', 'impressum', [\App\Controllers\LegalController::class, 'impressum']);
$router->add('GET', 'datenschutz', [\App\Controllers\LegalController::class, 'datenschutz']);
$router->add('GET', 'switch-lang', [\App\Controllers\HomeController::class, 'switchLang']);
$router->add('GET', 'media/view', [\App\Controllers\MediaController::class, 'serve']);

// مسارات الإدارة
$router->add('GET', 'admin/login', [\App\Controllers\Admin\AuthController::class, 'login']);
$router->add('POST', 'admin/login/process', [\App\Controllers\Admin\AuthController::class, 'authenticate']);
$router->add('GET', 'admin/dashboard', [\App\Controllers\Admin\DashboardController::class, 'index']);
$router->add('POST', 'admin/upload', [\App\Controllers\Admin\UploadController::class, 'upload']);
$router->add('GET', 'admin/logout', [\App\Controllers\Admin\DashboardController::class, 'logout']);
$router->add('GET', 'admin/verify-2fa', [\App\Controllers\Admin\AuthController::class, 'show2fa']);
$router->add('POST', 'admin/verify-2fa', [\App\Controllers\Admin\AuthController::class, 'verify2fa']);
$router->add('POST', 'admin/announcement/save', [\App\Controllers\Admin\AnnouncementController::class, 'save']);
$router->add('POST', 'admin/settings/save', [\App\Controllers\Admin\SettingsController::class, 'save']);

// 9. استخراج الـ URI النظيف وتمريره للراوتر المركزي
try {
    if (isset($_GET['url']) && is_string($_GET['url'])) {
        $uri = '/' . trim($_GET['url'], '/');
    } else {
        $rawUri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = '/' . trim((string)(parse_url($rawUri, PHP_URL_PATH) ?? ''), '/');

        $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptName !== '/' && $scriptName !== '\\' && $scriptName !== '.' && str_starts_with($uri, $scriptName)) {
            $uri = '/' . ltrim(substr($uri, strlen($scriptName)), '/');
        }
    }

    // إزالة بادئة /public إن وجدت في الرابط
    $uri = preg_replace('#^/public(/|$)#i', '/', $uri);
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    $router->dispatch($uri, $method);
} catch (\Throwable $e) {
    error_log('Router Dispatch Error: ' . $e->getMessage());
    http_response_code(500);
    if ($appEnv !== 'production') {
        echo 'خطأ في معالجة المسار: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    } else {
        echo 'عذراً، حدث خطأ في النظام.';
    }
}
