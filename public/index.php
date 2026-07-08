<?php
// public/index.php
session_start();

// استدعاء ملف التحميل التلقائي
require_once dirname(__DIR__) . '/app/autoload.php';

$url = $_SERVER['REQUEST_URI'];
$router = new \App\Core\Router();
$router->dispatch($url);
