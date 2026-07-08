<?php
// app/autoload.php

spl_autoload_register(function ($class) {
    // تحويل مسار الكلاس (مثلاً App\Core\Router) إلى مسار ملف فعلي
    // تحويل \ إلى /
    $path = str_replace('\\', '/', $class);
    $file = dirname(__DIR__) . '/' . $path . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
