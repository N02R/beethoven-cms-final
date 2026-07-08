<?php
namespace App\Controllers;

class AuthController {
    public function login() {
        // عرض صفحة الدخول
        require_once dirname(__DIR__, 2) . '/admin/login.php';
    }

    public function processLogin() {
        if ($_POST['password'] === '1234') { 
            $_SESSION['is_admin'] = true;
            header('Location: /');
        } else {
            echo "كلمة المرور خاطئة!";
        }
        exit;
    }
}
