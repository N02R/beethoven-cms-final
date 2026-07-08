<?php
// public/index.php

// 1. بدء الجلسة فوراً لضمان عمل الـ Session في كامل الموقع
session_start();

// 2. تحديد المجلد الرئيسي للمشروع للوصول للملفات بمرونة
$root = dirname(__DIR__);

// 3. استدعاء ملفات الـ Core
require_once $root . '/app/Core/Router.php';
require_once $root . '/app/Core/Database.php';
require_once $root . '/app/Core/CMS.php'; // أضفنا CMS هنا لأنه ضروري

// 4. استدعاء ملفات الـ Models
require_once $root . '/app/Models/User.php';
require_once $root . '/app/Models/Page.php';

// 5. استدعاء ملفات الـ Controllers
require_once $root . '/app/Controllers/AuthController.php';
require_once $root . '/app/Controllers/DashboardController.php';

// 6. تشغيل الـ Router
// التأكد من استخدام المسار المطلق وتمرير $root للراوتر إذا كان يحتاجه
$url = $_SERVER['REQUEST_URI'];
$router = new \App\Core\Router();

// 7. توجيه الطلب
$router->dispatch($url);
