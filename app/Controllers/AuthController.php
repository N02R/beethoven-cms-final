<?php
namespace App\Controllers;

class AuthController {
    public function login() {
        // عرض صفحة الدخول (تأكدي من أن الملف موجود في admin/login.php)
        require_once dirname(__DIR__, 2) . '/admin/login.php';
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['password'] ?? '') === '1234') { 
            $_SESSION['is_admin'] = true;
            // التحويل إلى لوحة التحكم بدلاً من الرئيسية
            header('Location: /dashboard');
            exit;
        } else {
            echo "كلمة المرور خاطئة! <a href='/admin-login'>حاول مرة أخرى</a>";
        }
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: /admin-login');
        exit;
    }
}
