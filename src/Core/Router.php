<?php
declare(strict_types=1);

namespace App\Core;

use ReflectionMethod;

class Router {
    private array $routes = [];

    public function add(string $method, string $path, array $controllerAction): void {
        $path = strtolower(trim($path, '/'));
        $this->routes[strtoupper($method)][$path] = $controllerAction;
    }

    public function dispatch(string $uri, string $method): void {
        $uri = strtolower(trim(parse_url($uri, PHP_URL_PATH) ?? '', '/'));
        $method = strtoupper($method);

        $segments = explode('/', $uri);
        $lang = 'de';
        
        // التحقق من لغة الموقع في بداية الرابط
        if (!empty($segments[0]) && in_array($segments[0], ['de', 'en', 'ar'], true)) {
            $lang = array_shift($segments);
            $uri = implode('/', $segments);
        }

        $uri = trim($uri, '/');

        // ==========================================
        // 🔍 مطابقة المسارات الثابتة أولاً
        // ==========================================
        if (isset($this->routes[$method][$uri])) {
            [$controllerClass, $action] = $this->routes[$method][$uri];
            $this->executeController($controllerClass, $action, $lang, []);
            return;
        }

        // ==========================================
        // 🔍 دعم المسارات الديناميكية (الروابط الفرعية مثل الحزم والخدمات)
        // ==========================================
        foreach ($this->routes[$method] ?? [] as $routePath => [$controllerClass, $action]) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_-]+)\}/', '([a-zA-Z0-9_-]+)', $routePath);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // إزالة المطابقة الكاملة
                $this->executeController($controllerClass, $action, $lang, $matches);
                return;
            }
        }

        // إذا لم يتم العثور على المسار، عرض صفحة 404
        $this->sendError(404, "Page Not Found");
    }

    private function executeController(string $controllerClass, string $action, string $lang, array $params): void {
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $action)) {
                $reflection = new ReflectionMethod($controller, $action);
                $paramCount = $reflection->getNumberOfParameters();

                if ($paramCount > 0) {
                    $callParams = array_merge([$lang], $params);
                    $callParams = array_slice($callParams, 0, $paramCount);
                    call_user_func_array([$controller, $action], $callParams);
                } else {
                    $controller->$action();
                }
                return;
            } else {
                echo "<div style='color:red; padding:20px; font-weight:bold;'>خطأ: الدالة (Method) <code>{$action}</code> غير موجودة داخل الكلاس <code>{$controllerClass}</code></div>";
                exit;
            }
        } else {
            echo "<div style='color:red; padding:20px; font-weight:bold;'>خطأ: الكلاس (Controller) <code>{$controllerClass}</code> غير موجود أو مساره خاطئ!</div>";
            exit;
        }
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
