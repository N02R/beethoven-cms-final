<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use PDO;

class AuthController {

    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS    = 900; // 15 دقيقة حظر مؤقت عند تجاوز المحاولات

    private function ensureSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function getRedirectBase(): string {
        $lang = $_SESSION['site_lang'] ?? 'de';
        return in_array($lang, ['ar', 'en', 'de'], true) ? "/{$lang}" : '';
    }

    private function respond(bool $isAjax, bool $success, string $message, string $redirectUrl = '/admin/login', int $statusCode = 200): void {
        if ($isAjax) {
            http_response_code($statusCode);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success'  => $success,
                'message'  => $message,
                'redirect' => $redirectUrl
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!$success) {
            $_SESSION['login_error'] = $message;
        }
        header("Location: " . $redirectUrl);
        exit;
    }

    /**
     * عرض صفحة تسجيل الدخول
     */
    public function login(): void {
        $this->ensureSession();

        if (!empty($_SESSION['is_logged_in']) && in_array($_SESSION['role'] ?? '', ['admin', 'super_admin'], true)) {
            header("Location: /admin/dashboard");
            exit;
        }

        $error_msg = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        // توليد CSRF Token آمن
        if (empty($_SESSION['login_csrf'])) {
            $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
        }

        // توليد كابتشا رياضية جديدة عند كل تحميل للصفحة
        $num1 = random_int(1, 9);
        $num2 = random_int(1, 9);
        $_SESSION['captcha_num1']   = $num1;
        $_SESSION['captcha_num2']   = $num2;
        $_SESSION['captcha_result'] = $num1 + $num2;

        $viewFile = __DIR__ . '/../../Views/admin/login.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            http_response_code(404);
            echo "Login View file not found.";
        }
    }

    /**
     * معالجة نموذج تسجيل الدخول بأعلى معايير الأمان
     */
    public function authenticate(): void {
        $this->ensureSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/login");
            exit;
        }

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                  || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        // 1. الحماية من هجمات التخمين (Brute Force / Rate Limiting)
        $lockUntil = (int)($_SESSION['login_lock_until'] ?? 0);
        if ($lockUntil > time()) {
            $remainingMinutes = (int)ceil(($lockUntil - time()) / 60);
            $this->respond(
                $isAjax,
                false,
                "تم تعليق محاولات الدخول مؤقتاً لأسباب أمنية. يرجى المحاولة بعد {$remainingMinutes} دقيقة.",
                '/admin/login',
                429
            );
        }

        // 2. التحقق الصارم من CSRF Token (دون تسريب أي قيمة)
        $token        = (string)($_POST['csrf_token'] ?? '');
        $sessionToken = (string)($_SESSION['login_csrf'] ?? '');

        if ($token === '' || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
            $this->respond($isAjax, false, 'انتهت صلاحية جلسة الأمان، يرجى تحديث الصفحة والمحاولة مجدداً.', '/admin/login', 403);
        }

        // 3. التحقق من الكابتشا الرياضية (إن تم إرسالها من النموذج)
        if (isset($_POST['captcha'])) {
            $userCaptcha     = trim((string)$_POST['captcha']);
            $expectedCaptcha = (string)($_SESSION['captcha_result'] ?? '');
            if ($expectedCaptcha === '' || !hash_equals($expectedCaptcha, $userCaptcha)) {
                $this->recordFailedAttempt();
                $this->respond($isAjax, false, 'إجابة التحقق الأمني (Captcha) غير صحيحة.', '/admin/login', 400);
            }
        }

        // 4. تنقية المدخلات والتحقق من الصيغة
        $email    = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? ''); // لا نستخدم trim لكلمة المرور حتى لا نغير المسافات المقصودة

        if ($email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190 || mb_strlen($password) > 255) {
            $this->respond($isAjax, false, 'يرجى إدخال بريد إلكتروني وكلمة مرور بصيغة صحيحة.', '/admin/login', 400);
        }

        // 5. التحقق من قاعدة البيانات مع منع هجمات التوقيت (Timing Attack Mitigation)
        $adminData = null;
        // تجزئة وهمية ثابتة لتوحيد زمن المعالجة إذا كان البريد غير موجود
        $dummyHash = '$2y$12$eImiTXuWVxfM37uY4JANjQ.5k0Z6F6W8V2B7N9M1L3K5J7H9G2F4D';

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM admins WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            $storedHash = $admin['password'] ?? $dummyHash;
            $isPasswordValid = password_verify($password, $storedHash);

            if ($admin && $isPasswordValid) {
                // تحديث خوارزمية التشفير تلقائياً إذا تم ترقية معيار PHP مستقبلاً
                if (password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare("UPDATE admins SET password = :pwd WHERE id = :id");
                    $updateStmt->execute(['pwd' => $newHash, 'id' => $admin['id']]);
                }

                $adminData = [
                    'role' => $admin['role'] ?? 'admin',
                    'name' => $admin['name'] ?? 'المشرف',
                    'id'   => (int)$admin['id']
                ];
            }
        } catch (\Throwable $e) {
            error_log("Critical Auth Database Error: " . $e->getMessage());
            $this->respond($isAjax, false, 'حدث خطأ داخلي في الخادم، يرجى المحاولة لاحقاً.', '/admin/login', 500);
        }

        // 6. نجاح المصادقة وتأسيس الجلسة المؤمّنة
        if ($adminData !== null) {
            // تصفير عداد المحاولات الفاشلة
            unset($_SESSION['login_attempts'], $_SESSION['login_lock_until']);

            // تجديد معرّف الجلسة فوراً لمنع Session Fixation
            session_regenerate_id(true);

            $_SESSION['is_logged_in']    = true;
            $_SESSION['role']            = $adminData['role'];
            $_SESSION['admin_name']      = $adminData['name'];
            $_SESSION['admin_id']        = $adminData['id'];
            $_SESSION['user_agent_hash'] = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');
            $_SESSION['LAST_ACTIVITY']   = time();

            unset($_SESSION['login_csrf'], $_SESSION['captcha_num1'], $_SESSION['captcha_num2'], $_SESSION['captcha_result']);

            $this->respond(
                $isAjax,
                true,
                'تم تسجيل الدخول بنجاح، جاري تحويلك للوحة التحكم...',
                '/admin/dashboard',
                200
            );
        }

        // 7. تسجيل المحاولة الفاشلة وإرجاع رسالة موحدة غير كاشفة
        $this->recordFailedAttempt();
        $this->respond($isAjax, false, 'بيانات الدخول غير صحيحة.', '/admin/login', 401);
    }

    private function recordFailedAttempt(): void {
        $attempts = (int)($_SESSION['login_attempts'] ?? 0) + 1;
        $_SESSION['login_attempts'] = $attempts;

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $_SESSION['login_lock_until'] = time() + self::LOCKOUT_SECONDS;
            $_SESSION['login_attempts'] = 0;
        }
    }

    /**
     * تسجيل الخروج الآمن
     */
    public function logout(): void {
        $this->ensureSession();

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();

        header("Location: /admin/login?message=" . urlencode('تم تسجيل الخروج بنجاح.'));
        exit;
    }
}
