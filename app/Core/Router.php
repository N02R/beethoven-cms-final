<?php

namespace App\Core;

class Router {
    protected $routes = [];

    // دالة لإضافة المسارات
    public function add($route, $action) {
        $this->routes[$route] = $action;
    }

    // دالة توجيه الطلب (التي استدعيناها في index.php)
    public function dispatch() {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // إزالة الجزء الأساسي من المسار إذا كان الموقع في مجلد فرعي
        $url = str_replace('/public', '', $url); 
        if ($url == '') $url = '/';

        if (array_key_exists($url, $this->routes)) {
            $this->callAction($this->routes[$url]);
        } else {
            http_response_code(404);
            echo "404 - الصفحة غير موجودة";
        }
    }

    // دالة لتنفيذ الـ Controller
    protected function callAction($action) {
        list($controllerName, $method) = explode('@', $action);
        $controllerClass = "App\\Controllers\\" . $controllerName;

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controller = new $controllerClass();
            $controller->$method();
        } else {
            throw new \Exception("Controller $controllerClass or method $method not found.");
        }
    }
}
