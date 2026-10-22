<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use PDO;

class AuthController {

    private function ensureSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * عرض صفحة تسجيل الدخول
     */
    public function login(): void {
        $this->ensureSession();

        // إذا كان المشرف مسجلاً دخوله مسبقاً، يتم توجيهه للوحة التحكم مباشرة
        if (!empty($_SESSION['is_logged_in']) && in_array($_SESSION['role'] ?? '', ['admin', 'super_admin'], true)) {
            header("Location: /admin/dashboard");
            exit;
        }

        $error_msg = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        // توليد رمز CSRF إن لم يكن موجوداً
        if (empty($_SESSION['login_csrf'])) {
            $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
        }

        // توليد الكابتشا الرياضية إن لم تكن موجودة
        if (empty($_SESSION['captcha_num1'])) {
            $_SESSION['captcha_num1'] = rand(1, 9);
            $_SESSION['captcha_num2'] = rand(1, 9);
            $_SESSION['captcha_result'] = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
        }

        // تحميل ملف الـ View عبر دالة مساعدة أو مسار نظيف
        $viewFile = __DIR__ . '/../../Views/admin/login.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            http_response_code(404);
            echo "Login View file not found.";
        }
    }

    /**
     * معالجة نموذج تسجيل الدخول (Form Submission عبر AJAX أو Form العادي)
     */
    public function authenticate(): void {
        $this->ensureSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/login");
            exit;
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' 
                  || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        // 1. التحقق من CSRF Token
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['login_csrf'] ?? '', $token)) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'رمز الحماية غير صالح (CSRF Invalid)']);
                exit;
            }
            $_SESSION['login_error'] = 'رمز الحماية غير صالح (CSRF Invalid)';
            header("Location: /admin/login");
            exit;
        }

        // 2. تنقية المدخلات
        $email = trim($_POST['email'] ?? ''); 
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'يرجى إدخال بريد إلكتروني وكلمة مرور صحيحة']);
                exit;
            }
            $_SESSION['login_error'] = 'يرجى إدخال بريد إلكتروني وكلمة مرور صحيحة';
            header("Location: /admin/login");
            exit;
        }

        // 3. التحقق عبر قاعدة البيانات
        $adminData = null;

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM admins WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $adminData = [
                    'role' => $admin['role'] ?? 'admin',
                    'name' => $admin['name'] ?? 'المشرف',
                    'id'   => $admin['id']
                ];
            }
        } catch (\Throwable $e) {
            error_log("Auth Error: " . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'حدث خطأ في النظام، يرجى المحاولة لاحقاً']);
                exit;
            }
            $_SESSION['login_error'] = 'حدث خطأ في النظام، يرجى المحاولة لاحقاً';
            header("Location: /admin/login");
            exit;
        }

        // 4. حفظ الجلسة والاستجابة عند النجاح
        if ($adminData !== null) {
            $_SESSION['is_logged_in'] = true;
            $_SESSION['role']         = $adminData['role'];
            $_SESSION['admin_name']   = $adminData['name'];
            $_SESSION['admin_id']     = $adminData['id'];

            session_regenerate_id(true);
            unset($_SESSION['login_csrf'], $_SESSION['captcha_num1'], $_SESSION['captcha_num2']);

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'message' => 'تم تسجيل الدخول بنجاح، جاري تحويلك للوحة التحكم...']);
                exit;
            }

            header("Location: /admin/dashboard");
            exit;
        }

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة']);
            exit;
        }

        $_SESSION['login_error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
        header("Location: /admin/login");
        exit;
    }

    /**
     * تسجيل الخروج
     */
    public function logout(): void {
        $this->ensureSession();

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header("Location: /admin/login?message=" . urlencode('تم تسجيل الخروج بنجاح.'));
        exit;
    }
}
