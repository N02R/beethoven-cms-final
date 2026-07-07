<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = User::authenticate($_POST['username'], $_POST['password']);
            
            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header('Location: /dashboard'); // تحويل للأدمن بعد الدخول
                exit;
            } else {
                echo "اسم المستخدم أو كلمة المرور غير صحيحة!";
            }
        }
        // هنا سنقوم لاحقاً باستدعاء صفحة العرض (View)
        require_once '../views/login.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}