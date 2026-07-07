<?php
// استدعاء ملفات الـ Autoloader (أو تضمين الملفات يدوياً إذا لم نستخدم Composer بعد)
require_once '../app/Core/Router.php';
require_once '../app/Core/Database.php';
require_once '../app/Models/User.php';
require_once '../app/Controllers/AuthController.php';

// الحصول على المسار الحالي
$url = $_SERVER['REQUEST_URI'];

// تشغيل الـ Router
$router = new \App\Core\Router();
$router->dispatch($url);