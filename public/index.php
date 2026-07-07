<?php
// استدعاء ملفات الـ Core
require_once '../app/Core/Router.php';
require_once '../app/Core/Database.php';

// استدعاء ملفات الـ Models
require_once '../app/Models/User.php';
require_once '../app/Models/Page.php'; // <--- أضيفي هذا السطر

// استدعاء ملفات الـ Controllers
require_once '../app/Controllers/AuthController.php';
require_once '../app/Controllers/DashboardController.php';

$url = $_SERVER['REQUEST_URI'];
$router = new \App\Core\Router();
$router->dispatch($url);
