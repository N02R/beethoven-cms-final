<?php
declare(strict_types=1);

namespace App\Core;

use ReflectionMethod;
use RuntimeException;
use Throwable;

class Router
{
    private array $routes = [];
    private array $supportedLangs = ['de', 'en', 'ar'];

    public function add(string $method, string $path, array $controllerAction): void
    {
        $cleanPath = trim($path, '/');
        $this->routes[strtoupper($method)][$cleanPath] = $controllerAction;
    }

    public function dispatch(string $uri, string $method): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $rawPath  = parse_url($uri, PHP_URL_PATH) ?? '/';
        $cleanUri = trim($rawPath, '/');
        $method   = strtoupper($method);

        $segments = ($cleanUri === '') ? [] : explode('/', $cleanUri);

        // 1. تحديد اللغة بأمان وفق القائمة البيضاء المعتمدة
        $sessionLang = $_SESSION['site_lang'] ?? 'de';
        $lang = in_array($sessionLang, $this->supportedLangs, true) ? $sessionLang : 'de';

        // 2. التقاط بادئة اللغة من الرابط إن وجدت وتحديث الجلسة
        if (!empty($segments[0]) && in_array(strtolower($segments[0]), $this->supportedLangs, true)) {
            $lang = strtolower(array_shift($segments));
            $_SESSION['site_lang'] = $lang;
        }

        $routeUri = trim(implode('/', $segments), '/');
        $lowerRouteUri = strtolower($routeUri);

        // 3. مطابقة المسارات الثابتة أولاً (Case-Insensitive للمسار)
        foreach ($this->routes[$method] ?? [] as $routePath => [$controllerClass, $action]) {
            if (!str_contains($routePath, '{') && strtolower($routePath) === $lowerRouteUri) {
                $this->executeController($controllerClass, $action, $lang, []);
                return;
            }
        }

        // 4. دعم المسارات الديناميكية مع الحفاظ على القيمة الأصلية للمعاملات
        foreach ($this->routes[$method] ?? [] as $routePath => [$controllerClass, $action]) {
            if (!str_contains($routePath, '{')) {
                continue;
            }

            $pattern = preg_replace('/\{([a-zA-Z0-9_-]+)\}/', '([a-zA-Z0-9_-]+)', $routePath);
            $pattern = '#^' . $pattern . '$#i';

            if (preg_match($pattern, $routeUri, $matches)) {
                array_shift($matches); // إزالة المطابقة الكاملة والإبقاء على المعاملات
                $this->executeController($controllerClass, $action, $lang, $matches);
                return;
            }
        }

        $this->sendError(404, 'Page Not Found');
    }

    private function executeController(string $controllerClass, string $action, string $lang, array $params): void
    {
        try {
            if (!class_exists($controllerClass)) {
                throw new RuntimeException("Controller class {$controllerClass} not found");
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $action) || str_starts_with($action, '__')) {
                throw new RuntimeException("Method {$action} not found or invalid in controller {$controllerClass}");
            }

            $reflection = new ReflectionMethod($controller, $action);
            if (!$reflection->isPublic()) {
                throw new RuntimeException("Method {$action} in {$controllerClass} is not public");
            }

            $parameters = $reflection->getParameters();
            $paramCount = count($parameters);

            if ($paramCount === 0) {
                $controller->$action();
                return;
            }

            // فحص ذكي لأول معامل: هل هو مخصص للغة ($lang / $langCode) أم معامل مسار ($id / $slug)؟
            $firstParamName = strtolower($parameters[0]->getName());
            $expectsLang = in_array($firstParamName, ['lang', 'langcode', 'language', 'locale'], true);

            $callParams = $expectsLang ? array_merge([$lang], $params) : $params;
            $callParams = array_slice($callParams, 0, $paramCount);

            call_user_func_array([$controller, $action], $callParams);
        } catch (Throwable $e) {
            error_log('Router Execution Error: ' . $e->getMessage());
            $this->sendError(500, 'Internal Server Error');
        }
    }

    private function sendError(int $code, string $message): void
    {
        http_response_code($code);
        $errorPage = __DIR__ . "/../Views/errors/{$code}.php";
        if (is_file($errorPage)) {
            require_once $errorPage;
        } else {
            echo "<h1>{$code} - " . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</h1>";
        }
        exit;
    }
}
