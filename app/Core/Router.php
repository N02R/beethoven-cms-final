<?php
namespace App\Core;

class Router {
    private array $routes = [];

    public function __construct() {
        // تعريف المسارات المسموح بها (أمان من نوع Whitelist)
        $this->routes = [
            '/' => 'home',
            '/about' => 'aboutus',
            '/education' => 'education',
            '/job' => 'job',
            '/contact' => 'contact'
        ];
    }

    public function dispatch(string $url) {
        // إزالة الاستعلامات (?page=...) لترتيب المسار
        $path = parse_url($url, PHP_URL_PATH);
        
        // التحقق إذا كان المسار ضمن القائمة المعتمدة
        if (array_key_exists($path, $this->routes)) {
            $page = $this->routes[$path];
            $this->render($page);
        } else {
            $this->render404();
        }
    }

    private function render(string $page) {
        $file = __DIR__ . '/../../public/' . $page . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            $this->render404();
        }
    }

    private function render404() {
        http_response_code(404);
        echo "<h1>404 - الصفحة غير موجودة</h1>";
    }
}
