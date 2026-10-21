<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use PDO;

class AuthController {

    public function login(): void {
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // إذا كان مسجلاً مسبقاً، وجهه للوحة التحكم
        if (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            header("Location: index.php?url=admin/dashboard");
            exit;
        }

        $error_msg = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        if (empty($_SESSION['login_csrf'])) {
            $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
        }

        // كابتشا رياضية بسيطة
        if (empty($_SESSION['captcha_num1'])) {
            $_SESSION['captcha_num1'] = rand(1, 5);
            $_SESSION['captcha_num2'] = rand(1, 5);
            $_SESSION['captcha_result'] = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
        }

        $data = [
            'error_msg'    => $error_msg,
            'csrf_token'   => $_SESSION['login_csrf'],
            'captcha_num1' => $_SESSION['captcha_num1'],
            'captcha_num2' => $_SESSION['captcha_num2'],
        ];

        $root_path = realpath(__DIR__ . '/../../../');
        $view_file = $root_path . '/src/Views/admin/login.php';

        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "Login View file not found.";
        }
    }

    public function authenticate(): void {
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?url=admin/login");
            exit;
        }

        // 1. التحقق من CSRF
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['login_csrf'] ?? '', $token)) {
            $_SESSION['login_error'] = 'انتهت صلاحية الجلسة، يرجى إعادة المحاولة.';
            header("Location: index.php?url=admin/login");
            exit;
        }

        // 2. التحقق من الكابتشا
        $captcha_answer = isset($_POST['captcha_answer']) ? (int)$_POST['captcha_answer'] : null;
        if ($captcha_answer !== ($_SESSION['captcha_result'] ?? null)) {
            $_SESSION['login_error'] = 'إجابة التحقق الأمني غير صحيحة.';
            $_SESSION['captcha_num1'] = rand(1, 5);
            $_SESSION['captcha_num2'] = rand(1, 5);
            $_SESSION['captcha_result'] = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
            header("Location: index.php?url=admin/login");
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'يرجى إدخال اسم المستخدم وكلمة المرور.';
            header("Location: index.php?url=admin/login");
            exit;
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username LIMIT 1");
            $stmt->execute(['email' => $username, 'username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // مطابقة كلمة المرور مباشرة
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['is_logged_in'] = true;
                $_SESSION['role']         = $user['role'] ?? 'super_admin';
                $_SESSION['admin_name']   = $user['username'] ?? 'المشرف';
                $_SESSION['admin_id']     = $user['id'];

                session_regenerate_id(true);
                unset($_SESSION['captcha_num1'], $_SESSION['captcha_num2'], $_SESSION['captcha_result'], $_SESSION['login_csrf']);

                header("Location: index.php?url=admin/dashboard");
                exit;
            }
        } catch (\Throwable $e) {
            error_log("Login Exception: " . $e->getMessage());
        }

        $_SESSION['login_error'] = 'بيانات الدخول غير صحيحة.';
        header("Location: index.php?url=admin/login");
        exit;
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?url=admin/login");
        exit;
    }
}
