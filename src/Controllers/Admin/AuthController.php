<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use App\Core\Security;
use PDO;

class AuthController
{
    // 1. عرض صفحة تسجيل الدخول
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // إذا كان مسجلاً مسبقاً، حوله للوحة التحكم فوراً
        if (isset($_SESSION['is_logged_in']) &&$_SESSION['is_logged_in'] === true) {
            header("Location: /admin/dashboard");
            exit;
        }

        $current_lang =$_SESSION['site_lang'] ?? 'de';
        require_once __DIR__ . '/../../Views/admin/login.php';
    }

    // 2. معالجة بيانات الدخول (الـ Login Process) وفق معايير الأمان والجودة
    public function authenticate(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password =$_POST['password'] ?? '';
        $token =$_POST['csrf_token'] ?? '';

        // التحقق الأمني من الـ CSRF
        if (!Security::verifyCsrfToken($token)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'انتهت صلاحية الجلسة، يرجى إعادة تحديث الصفحة.']);
            exit;
        }

        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'يرجى إدخال البريد الإلكتروني وكلمة المرور.']);
            exit;
        }

        try {
            $pdo = Database::getConnection();
            $stmt =$pdo->prepare("SELECT * FROM `admins` WHERE `email` = ? LIMIT 1");
            $stmt->execute([$email]);
            $admin =$stmt->fetch(PDO::FETCH_ASSOC);

            // التحقق من كلمة المرور المشفرة
            if ($admin && password_verify($password,$admin['password'])) {
                session_regenerate_id(true);

                // تعبئة جلسة الأدمن بنجاح مع تخزين الصلاحية الأمنية (Role)
                $_SESSION['is_logged_in'] = true;
                $_SESSION['admin_id']     =$admin['id'];
                $_SESSION['admin_name']   =$admin['name'] ?? 'Admin';
                $_SESSION['admin_email']  =$admin['email'];
                $_SESSION['role']         =$admin['role'] ?? 'admin'; // تخزين الصلاحية بأمان

                echo json_encode([
                    'success' => true, 
                    'message' => 'تم تسجيل الدخول بنجاح! جاري تحويلك لوحة التحكم...'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'
                ]);
            }
        } catch (\Throwable $e) {
            error_log("Login Error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'حدث خطأ في قاعدة البيانات، يرجى التحقق لاحقاً.'
            ]);
        }
        exit;
    }

    // 3. تسجيل الخروج
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();

        header("Location: /admin/login");
        exit;
    }
}
