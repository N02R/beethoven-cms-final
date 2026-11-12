<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use PDO;

class AuthController {

    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS    = 900; // 15 دقيقة حظر عند تجاوز المحاولات
    private const OTP_EXPIRY_SECONDS = 300; // 5 دقائق صلاحية رمز التحقق الثنائي
    private const MAX_OTP_ATTEMPTS   = 3;

    private function ensureSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
     * 1. عرض صفحة تسجيل الدخول
     */
    public function login(): void {
        $this->ensureSession();

        if (!empty($_SESSION['is_logged_in']) && in_array($_SESSION['role'] ?? '', ['admin', 'super_admin'], true)) {
            header("Location: /admin/dashboard");
            exit;
        }

        $error_msg = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        if (empty($_SESSION['login_csrf'])) {
            $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
        }

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
     * 2. معالجة بيانات الدخول وإطلاق مرحلة التحقق الثنائي (2FA)
     */
    public function authenticate(): void {
        $this->ensureSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/login");
            exit;
        }

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                  || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        // الحماية من التخمين العشوائي (Rate Limiting)
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

        // التحقق من CSRF Token
        $token        = (string)($_POST['csrf_token'] ?? '');
        $sessionToken = (string)($_SESSION['login_csrf'] ?? '');

        if ($token === '' || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
            $this->respond($isAjax, false, 'انتهت صلاحية جلسة الأمان، يرجى تحديث الصفحة والمحاولة مجدداً.', '/admin/login', 403);
        }

        // التحقق من الكابتشا الرياضية
        if (isset($_POST['captcha'])) {
            $userCaptcha     = trim((string)$_POST['captcha']);
            $expectedCaptcha = (string)($_SESSION['captcha_result'] ?? '');
            if ($expectedCaptcha === '' || !hash_equals($expectedCaptcha, $userCaptcha)) {
                $this->recordFailedAttempt();
                $this->respond($isAjax, false, 'إجابة التحقق الأمني (Captcha) غير صحيحة.', '/admin/login', 400);
            }
        }

        // تنقية المدخلات
        $email    = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190 || mb_strlen($password) > 255) {
            $this->respond($isAjax, false, 'يرجى إدخال بريد إلكتروني وكلمة مرور بصيغة صحيحة.', '/admin/login', 400);
        }

        // التحقق من قاعدة البيانات مع منع Timing Attack
        $adminData = null;
        $dummyHash = '$2y$12$eImiTXuWVxfM37uY4JANjQ.5k0Z6F6W8V2B7N9M1L3K5J7H9G2F4D';

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM admins WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            $storedHash = $admin['password'] ?? $dummyHash;
            $isPasswordValid = password_verify($password, $storedHash);

            if ($admin && $isPasswordValid) {
                if (password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare("UPDATE admins SET password = :pwd WHERE id = :id");
                    $updateStmt->execute(['pwd' => $newHash, 'id' => $admin['id']]);
                }

                $adminData = [
                    'role'  => $admin['role'] ?? 'admin',
                    'name'  => $admin['name'] ?? 'المشرف',
                    'email' => $admin['email'],
                    'id'    => (int)$admin['id']
                ];
            }
        } catch (\Throwable $e) {
            error_log("Critical Auth Database Error: " . $e->getMessage());
            $this->respond($isAjax, false, 'حدث خطأ داخلي في الخادم، يرجى المحاولة لاحقاً.', '/admin/login', 500);
        }

        // نجاح التحقق الأولي -> الانتقال لمرحلة 2FA
        if ($adminData !== null) {
            unset($_SESSION['login_attempts'], $_SESSION['login_lock_until'], $_SESSION['login_csrf'], $_SESSION['captcha_num1'], $_SESSION['captcha_num2'], $_SESSION['captcha_result']);

            session_regenerate_id(true);

            // توليد كود 2FA مكون من 6 أرقام مشفرة
            $otpCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $_SESSION['pending_2fa_admin'] = $adminData;
            $_SESSION['2fa_code_hash']     = hash('sha256', $otpCode);
            $_SESSION['2fa_expires_at']    = time() + self::OTP_EXPIRY_SECONDS;
            $_SESSION['2fa_attempts']      = 0;
            $_SESSION['2fa_csrf']          = bin2hex(random_bytes(32));
            $_SESSION['user_agent_hash']   = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');

            // إتاحة الكود في بيئة التطوير المحلية فقط
            if (($_ENV['APP_ENV'] ?? 'production') !== 'production') {
                $_SESSION['2fa_code'] = $otpCode;
            } else {
                unset($_SESSION['2fa_code']);
                $this->send2faEmail($adminData['email'], $adminData['name'], $otpCode);
            }

            $this->respond(
                $isAjax,
                true,
                'تم التحقق من بياناتك، جاري تحويلك لإدخال رمز الأمان (2FA)...',
                '/admin/verify-2fa',
                200
            );
        }

        $this->recordFailedAttempt();
        $this->respond($isAjax, false, 'بيانات الدخول غير صحيحة.', '/admin/login', 401);
    }

    /**
     * 3. عرض صفحة التحقق الثنائي (2FA)
     */
    public function show2fa(): void {
        $this->ensureSession();

        if (!empty($_SESSION['is_logged_in'])) {
            header("Location: /admin/dashboard");
            exit;
        }

        // منع الوصول المباشر دون اجتياز الخطوة الأولى
        if (empty($_SESSION['pending_2fa_admin']) || empty($_SESSION['2fa_code_hash'])) {
            header("Location: /admin/login");
            exit;
        }

        // التحقق من انتهاء صلاحية الرمز
        if (time() > (int)($_SESSION['2fa_expires_at'] ?? 0)) {
            $this->clear2faSession();
            $_SESSION['login_error'] = 'انتهت صلاحية رمز التحقق الثنائي، يرجى تسجيل الدخول مجدداً.';
            header("Location: /admin/login");
            exit;
        }

        if (empty($_SESSION['2fa_csrf'])) {
            $_SESSION['2fa_csrf'] = bin2hex(random_bytes(32));
        }

        $error_message = $_SESSION['2fa_error'] ?? '';
        unset($_SESSION['2fa_error']);

        $viewFile = __DIR__ . '/../../Views/admin/verify_2fa.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            http_response_code(404);
            echo "2FA View file not found.";
        }
    }

    /**
     * 4. التحقق من رمز 2FA وتفعيل جلسة المشرف الكاملة
     */
    public function verify2fa(): void {
        $this->ensureSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin/verify-2fa");
            exit;
        }

        if (empty($_SESSION['pending_2fa_admin']) || empty($_SESSION['2fa_code_hash'])) {
            header("Location: /admin/login");
            exit;
        }

        // التحقق من CSRF
        $token        = (string)($_POST['csrf_token'] ?? '');
        $sessionToken = (string)($_SESSION['2fa_csrf'] ?? '');
        if ($token === '' || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
            $_SESSION['2fa_error'] = 'انتهت صلاحية الجلسة الأمنية، يرجى المحاولة مجدداً.';
            header("Location: /admin/verify-2fa");
            exit;
        }

        // التحقق من مهلة الصلاحية (5 دقائق)
        if (time() > (int)($_SESSION['2fa_expires_at'] ?? 0)) {
            $this->clear2faSession();
            $_SESSION['login_error'] = 'انتهت صلاحية رمز التحقق، يرجى تسجيل الدخول مرة أخرى.';
            header("Location: /admin/login");
            exit;
        }

        // التحقق من تطابق بصمة المتصفح
        $currentAgentHash = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');
        if (!empty($_SESSION['user_agent_hash']) && !hash_equals($_SESSION['user_agent_hash'], $currentAgentHash)) {
            $this->clear2faSession();
            header("Location: /admin/login");
            exit;
        }

        $inputCode = trim((string)($_POST['verification_code'] ?? ''));
        if (!preg_match('/^[0-9]{6}$/', $inputCode)) {
            $_SESSION['2fa_error'] = 'يرجى إدخال رمز مكون من 6 أرقام صحيحة.';
            header("Location: /admin/verify-2fa");
            exit;
        }

        $inputHash = hash('sha256', $inputCode);
        if (hash_equals((string)$_SESSION['2fa_code_hash'], $inputHash)) {
            $adminData = $_SESSION['pending_2fa_admin'];
            $this->clear2faSession();

            // تجديد الجلسة وتأسيس صلاحيات المشرف الكاملة
            session_regenerate_id(true);
            $_SESSION['is_logged_in']    = true;
            $_SESSION['role']            = $adminData['role'];
            $_SESSION['admin_name']      = $adminData['name'];
            $_SESSION['admin_id']        = $adminData['id'];
            $_SESSION['user_agent_hash'] = $currentAgentHash;
            $_SESSION['LAST_ACTIVITY']   = time();

            header("Location: /admin/dashboard");
            exit;
        }

        // تسجيل محاولة 2FA فاشلة
        $attempts = (int)($_SESSION['2fa_attempts'] ?? 0) + 1;
        $_SESSION['2fa_attempts'] = $attempts;

        if ($attempts >= self::MAX_OTP_ATTEMPTS) {
            $this->clear2faSession();
            $_SESSION['login_error'] = 'تم تجاوز الحد الأقصى لمحاولات التحقق الثنائي. يرجى إعادة تسجيل الدخول.';
            header("Location: /admin/login");
            exit;
        }

        $_SESSION['2fa_error'] = 'رمز التحقق غير صحيح. المحاولات المتبقية: ' . (self::MAX_OTP_ATTEMPTS - $attempts);
        header("Location: /admin/verify-2fa");
        exit;
    }

    private function clear2faSession(): void {
        unset(
            $_SESSION['pending_2fa_admin'],
            $_SESSION['2fa_code_hash'],
            $_SESSION['2fa_expires_at'],
            $_SESSION['2fa_attempts'],
            $_SESSION['2fa_csrf'],
            $_SESSION['2fa_code'],
            $_SESSION['2fa_error']
        );
    }

    private function send2faEmail(string $toEmail, string $adminName, string $otpCode): void {
        $fromEmail = $_ENV['MAIL_FROM_ADDRESS'] ?? ('no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'beethoven-services.de'));
        $subject   = 'Beethoven CMS - رمز التحقق الثنائي (2FA)';
        $body      = "مرحباً {$adminName},\n\n"
                   . "رمز التحقق الثنائي الخاص بتسجيل الدخول إلى لوحة التحكم هو: {$otpCode}\n"
                   . "هذا الرمز صالح لمدة 5 دقائق فقط.\n\n"
                   . "إذا لم تطلب تسجيل الدخول، يرجى تجاهل هذه الرسالة وتغيير كلمة المرور فوراً.";

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'From: Beethoven CMS <' . $fromEmail . '>',
            'X-Mailer: PHP/' . phpversion()
        ];

        @mail($toEmail, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
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
     * 5. تسجيل الخروج الآمن
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
