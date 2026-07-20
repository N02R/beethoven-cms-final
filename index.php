<?php

/**
 * ملف المدخل الرئيسي (Entry Point)
 */

// 1. تحديد المسارات الأساسية
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT . 'app' . DIRECTORY_SEPARATOR);

// 2. تفعيل تقرير الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 3. استدعاء الأوتولودر
require_once APP_PATH . 'Core/Autoloader.php';

// 4. استخدام فضاءات الأسماء
use App\Core\SessionManager;
use App\Core\Router;

// 5. بدء الجلسة بشكل آمن
SessionManager::start();

// 6. تشغيل النظام
try {
    $router = new Router();
    
    // تعريف المسارات (يمكنك إضافة أي عدد تريدينه هنا)
    $router->add('/', 'HomeController@index');
    $router->add('/about', 'PageController@about');

    // تنفيذ الطلب
    $router->dispatch();

} catch (\Exception $e) {
    // معالجة الأخطاء
    echo "<h1>حدث خطأ في النظام</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
