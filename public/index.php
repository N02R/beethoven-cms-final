<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ... باقي الكود


// public/index.php
session_start();

// استدعاء ملف التحميل التلقائي
require_once dirname(__DIR__) . '/app/autoload.php';

$url = $_SERVER['REQUEST_URI'];
$router = new \App\Core\Router();
$router->dispatch($url);
