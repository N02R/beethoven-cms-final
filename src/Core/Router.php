<?php
declare(strict_types=1);

namespace App\Core;

class Router {
    private array $routes = [];

    public function add(string $method, string $path, array $controllerAction): void {
        $path = trim($path, '/');
        $this->routes[strtoupper($method)][$path] = $controllerAction;
    }

    public function dispatch(string $uri, string $method): void {
        // تنقية وتوحيد المسار (حروف صغيرة، إزالة الشرطات الزائدة)
        $uri = strtolower(trim(parse_url($uri, PHP_URL_PATH) ?? '', '/'));
        $method = strtoupper($method);

        // استخراج بادئة اللغة إن وجدت (مثال: /de/services/... -> de)
        $segments = explode('/', $uri);
        $lang = 'de'; // اللغة الافتراضية للشركة الألمانية
        
        if (!empty($segments[0]) && in_array($segments[0], ['de', 'en', 'ar'], true)) {
            $lang = array_shift($segments);
            $uri = implode('/', $segments);
        }

        // مطابقة المسارات
        if (isset($this->routes[$method][$uri])) {
            [$controllerClass, $action] = $this->routes[$method][$uri];
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $action)) {
                    $controller->$action($lang);
                    return;
                }
            }
            $this->sendError(500, "Internal Server Error");
            return;
        }

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
