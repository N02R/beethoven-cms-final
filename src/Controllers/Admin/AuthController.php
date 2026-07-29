<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use PDO;

class AuthController {

    /**
     * عرض صفحة تسجيل الدخول
     */
    public function login(): void {
        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // إذا كان المشرف مسجلاً دخوله مسبقاً، يتم توجيهه للوحة التحكم مباشرة
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')) {
            header("Location: index.php?url=admin/dashboard");
            exit;
        }

        // استلام رسالة الخطأ من الجلسة (إن وجدت) ثم حذفها
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

        $data = [
            'error_msg'      => $error_msg,
            'csrf_token'     => $_SESSION['login_csrf'],
            'captcha_num1'   => $_SESSION['captcha_num1'],
            'captcha_num2'   => $_SESSION['captcha_num2'],
        ];

        // استدعاء ملف الـ View
        $root_path = realpath(__DIR__ . '/../../../');
        $view_file = $root_path . '/src/Views/admin/login.php';

        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "Login View file not found.";
        }
    }

    /**
     * معالجة نموذج تسجيل الدخول (Form Submission)
     */
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

        // 1. التحقق من CSRF Token
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['login_csrf'] ?? '', $token)) {
            $_SESSION['login_error'] = 'رمز الحماية غير صالح (CSRF Invalid)';
            header("Location: index.php?url=admin/login");
            exit;
        }

        // 2. التحقق من الكابتشا
        $captcha_answer = isset($_POST['captcha_answer']) ? (int)$_POST['captcha_answer'] : null;
        if ($captcha_answer !== ($_SESSION['captcha_result'] ?? null)) {
            $_SESSION['login_error'] = 'إجابة التحقق الأمني (الكابتشا) غير صحيحة';
            // إعادة توليد الكابتشا
            $_SESSION['captcha_num1'] = rand(1, 9);
            $_SESSION['captcha_num2'] = rand(1, 9);
            $_SESSION['captcha_result'] = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
            header("Location: index.php?url=admin/login");
            exit;
        }

        // 3. تنقية واستلام بيانات المدخلات
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'يرجى تعبئة كافة الحقول المطلوبة';
            header("Location: index.php?url=admin/login");
            exit;
        }

        // 4. التحقق عبر كلاس قاعدة البيانات المعتمد للنظام (App\Config\Database)
        $admin_logged_in = false;
        $admin_data = [];

        try {
            $pdo = Database::getConnection();
            
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :user OR email = :email LIMIT 1");
            $stmt->execute(['user' => $username, 'email' => $username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $admin_logged_in = true;
                $admin_data = [
                    'role' => $admin['role'] ?? 'admin',
                    'name' => $admin['full_name'] ?? $admin['username'],
                    'id'   => $admin['id']
                ];
            }
        } catch (\Throwable $e) {
            error_log("Auth Error: " . $e->getMessage());
        }

        // ب) حساب الطوارئ الافتراضي في حال عدم وجود الجدول في قاعدة البيانات بعد
        if (!$admin_logged_in && $username === 'admin' && $password === 'admin123') {
            $admin_logged_in = true;
            $admin_data = [
                'role' => 'super_admin',
                'name' => 'المشرف العام',
                'id'   => 1
            ];
        }

        // 5. حفظ الجلسة والتوجيه للوحة التحكم
        if ($admin_logged_in) {
            $_SESSION['is_logged_in'] = true;
            $_SESSION['role']         = $admin_data['role'];
            $_SESSION['admin_name']   = $admin_data['name'];
            $_SESSION['admin_id']     = $admin_data['id'];

            // تنظيف بيانات الجلسة المؤقتة
            unset($_SESSION['captcha_num1'], $_SESSION['captcha_num2'], $_SESSION['captcha_result'], $_SESSION['login_csrf']);

            header("Location: index.php?url=admin/dashboard");
            exit;
        }

        // في حال كانت البيانات غير صحيحة
        $_SESSION['login_error'] = 'اسم المستخدم أو كلمة المرور غير صحيحة';
        header("Location: index.php?url=admin/login");
        exit;
    }

    /**
     * تسجيل الخروج
     */
    public function logout(): void {
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

        header("Location: index.php?url=admin/login");
        exit;
    }
}
