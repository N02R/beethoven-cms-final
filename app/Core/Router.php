// داخل public/Core/Router.php

public function dispatch($url) {
    $url = parse_url($url, PHP_URL_PATH);
    
    // 1. مسار تسجيل دخول المدير
    if ($url === '/admin-login') {
        (new \App\Controllers\AuthController())->login(); // عرض صفحة الدخول
    } 
    // 2. مسار معالجة تسجيل الدخول
    elseif ($url === '/admin-process') {
        (new \App\Controllers\AuthController())->processLogin();
    }
    // 3. مسار حفظ التعديلات (حماية: لا يمكن الحفظ إلا إذا كان مديراً)
    elseif ($url === '/admin/save-all') {
        if (!isset($_SESSION['is_admin'])) { die('غير مصرح لك'); }
        (new \App\Controllers\DashboardController())->saveAll();
    }
    // ... باقي المسارات (dashboard, logout) ...
    elseif ($url === '/') {
        require_once '../templates/home.php';
    } else {
        // إذا لم يكن هناك مسار مطابق
        http_response_code(404);
        echo "404 - الصفحة غير موجودة";
    }
}
