<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use App\Core\Security;
use PDO;

class AuthController
{
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['is_logged_in']) &&$_SESSION['is_logged_in'] === true) {
            header("Location: index.php?url=admin/dashboard");
            exit;
        }

        $current_lang = $_SESSION['site_lang'] ?? 'de';$path_prefix = '';

        require_once __DIR__ . '/../../Views/admin/login.php';
    }

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

        if (!Security::verifyCsrfToken($token)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'انتهت صلاحية الجلسة، يرجى إعادة المحاولة.']);
            exit;
        }

        if (empty($email) \vert{}\vert{} empty($password)) {
            echo json_encode(['success' => false, 'message' => 'يرجى إدخال البريد الإلكتروني وكلمة المرور.']);
            exit;
        }

        try {
            $pdo = Database::getConnection();
            $stmt =$pdo->prepare("SELECT * FROM `admins` WHERE `email` = ? LIMIT 1");
            $stmt->execute([$email]);
            $admin =$stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password,$admin['password'])) {
                session_regenerate_id(true);

                $_SESSION['is_logged_in'] = true;
                $_SESSION['admin_id']     = $admin['id'];$_SESSION['admin_name']   = $admin['name'] ?? 'Admin';$_SESSION['admin_email']  = $admin['email'];$_SESSION['role']         = 'admin';

                echo json_encode([
                    'success' => true, 
                    'message' => 'تم تسجيل الدخول بنجاح، جاري تحويلك لوحة التحكم...'
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
                'message' => 'حدث خطأ في النظام، يرجى المحاولة لاحقاً.'
            ]);
        }
        exit;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header("Location: index.php?url=home");
        exit;
    }
}
