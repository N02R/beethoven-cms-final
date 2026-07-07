<?php
require_once '../vendor/autoload.php'; // سنحتاج لتنظيم الـ autoload لاحقاً
$router = new \App\Core\Router();
$router->dispatch($_SERVER['REQUEST_URI']);