<?php
// app/autoload.php

spl_autoload_register(function ($class) {
    // يحول App\Core\Router إلى المسار الصحيح
    $path = str_replace('\\', '/', $class);
    // نحدد المسار الأساسي للمشروع
    $file = dirname(__DIR__) . '/' . $path . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
