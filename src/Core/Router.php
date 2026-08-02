<?php
declare(strict_types=1);

namespace App\Core;

use ReflectionMethod;

class Router {
    private array $routes = [];

    public function add(string $method, string $path, array $controllerAction): void {
        // توحيد نمط تخزين المسارات بإزالة الشرطات الزائدة وتحويلها لحروف صغيرة
        $path = strtolower(trim($path, '/'));
        $this->routes[strtoupper($method)][$path] = $controllerAction;
    }

    public function dispatch(string $uri, string $method): void {
        // تنقية وتوحيد المسار القادم من الـ URL
        $uri = strtolower(trim(parse_url($uri, PHP_URL_PATH) ?? '', '/'));
        $method = strtoupper($method);

        // استخراج بادئة اللغة إن وجدت (مثال: de/about -> de)
        $segments = explode('/', $uri);
        $lang = 'de'; // اللغة الافتراضية
        
        if (!empty($segments[0]) && in_array($segments[0], ['de', 'en', 'ar'], true)) {
            $lang = array_shift($segments);
            $uri = implode('/', $segments);
        }

        // إزالة أي شرطات مائلة في البداية أو النهاية بعد فصل اللغة
        $uri = trim($uri, '/');

        // مطابقة المسارات بشكل دقيق
        if (isset($this->routes[$method][$uri])) {
            [$controllerClass, $action] = $this->routes[$method][$uri];
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $action)) {
                    $reflection = new ReflectionMethod($controller, $action);
                    if ($reflection->getNumberOfParameters() > 0) {
                        $controller->$action($lang);
                    } else {
                        $controller->$action();
                    }
                    return;
                }
            }
            $this->sendError(500, "Internal Server Error");
            return;
        }

        // في حال لم يتم العثور على المسار، إرجاع خطأ 404
        $this->sendError(404, "Page Not Found");
    }

    private function sendError(int $code, string $message): void {
        http_response_code($code);
        $errorPage = __DIR__ . "/../Views/errors/{$code}.php";
        if (file_exists($errorPage)) {
            require_once $errorPage;
        } else {
            echo "<h1>{$code} - {$message}</h1>";
        }
        exit;
    }
}
