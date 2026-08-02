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
        
        if (!empty($segments[0]) && in_array($segments[0], ['de', 'en', 'ar'], true)) {
            $lang = array_shift($segments);
            $uri = implode('/', $segments);
        }

        $uri = trim($uri, '/');

        // ==========================================
        // 🔍 سطر التشخيص المؤقت (قم بإلغاء التعليق لرؤية المسار المستلم)
        // ==========================================
        // echo "<div style='background:#222; color:#0ff; padding:10px; font-family:monospace; z-index:99999; position:relative;'>الرابط المستلم بعد التنقية: [<strong>{$uri}</strong>] | الطريقة: [<strong>{$method}</strong>]</div>";

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
                } else {
                    echo "<div style='color:red; padding:20px; font-weight:bold;'>خطأ: الدالة (Method) <code>{$action}</code> غير موجودة داخل الكلاس <code>{$controllerClass}</code></div>";
                    exit;
                }
            } else {
                echo "<div style='color:red; padding:20px; font-weight:bold;'>خطأ: الكلاس (Controller) <code>{$controllerClass}</code> غير موجود أو مساره خاطئ!</div>";
                exit;
            }
        }

        // إذا لم يتم العثور على المسار، اطبع لنا قائمة الروابط المسجلة لتعرف ما الذي يبحث عنه النظام
        echo "<div style='background:#ffe6e6; color:#900; padding:20px; font-family:sans-serif; direction:rtl;'>";
        echo "<h3>❌ خطأ 404: المسار غير مسجل في الروتر!</h3>";
        echo "<p>الرابط الذي تحاول طلبه بعد التنقية هو: <code><strong>" . htmlspecialchars($uri) . "</strong></code></p>";
        echo "<p>طريقة الطلب (Method): <code><strong>" . htmlspecialchars($method) . "</strong></code></p>";
        echo "<hr>";
        echo "<p>تأكد أن الرابط مسجل تماماً في ملف <code>public/index.php</code> بنفس الصيغة.</p>";
        echo "</div>";
        exit;
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
