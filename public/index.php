<?php
// public/index.php

// 1. تحميل النظام
require_once __DIR__ . '/../app/Core/Autoloader.php';

// 2. استخدام الخدمات
use App\Services\ConfigService;

try {
    $configService = new ConfigService();
    $data = $configService->getData();
    
    // هنا نقوم بتمرير البيانات للـ Router أو للـ Views
    // ...
    
} catch (\Exception $e) {
    // في الإنتاج، لا نظهر تفاصيل الخطأ للعميل
    error_log($e->getMessage());
    die("عذراً، حدث خطأ تقني في النظام. يرجى المحاولة لاحقاً.");
}
