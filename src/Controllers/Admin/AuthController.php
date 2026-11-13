<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use App\Core\Security;
use PDO;
use Throwable;

class AuthController
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS    = 900; // 15 دقيقة
    private const OTP_EXPIRY_SECONDS = 300; // 5 دقائق
    private const MAX_OTP_ATTEMPTS   = 3;

    private function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function getCurrentLang(): string
    {
        $supported = ['de', 'en', 'ar'];
        if (isset($_POST['ui_lang']) && in_array($_POST['ui_lang'], $supported, true)) {
            $_SESSION['admin_login_lang'] = $_POST['ui_lang'];
            $_SESSION['site_lang'] = $_POST['ui_lang'];
        }
        $lang = $_SESSION['admin_login_lang'] ?? ($_SESSION['site_lang'] ?? 'de');
        return in_array($lang, $supported, true) ? $lang : 'de';
    }

    private function generateCaptcha(): string
    {
        $num1 = random_int(1, 9);
        $num2 = random_int(1, 9);
        $_SESSION['captcha_num1']   = $num1;
        $_SESSION['captcha_num2']   = $num2;
        $_SESSION['captcha_result'] = (string)($num1 + $num2);
        return "{$num1} + {$num2} = ?";
    }

    private function msg(string $key, array $replace = []): string
    {
        $lang = $this->getCurrentLang();
        $messages = [
            'de' => [
                'lockout'        => 'Anmeldeversuche aus Sicherheitsgründen vorübergehend gesperrt. Bitte versuchen Sie es in {min} Minuten erneut.',
                'csrf_expired'   => 'Sicherheitssitzung abgelaufen. Bitte laden Sie die Seite neu.',
                'captcha_wrong'  => 'Die Antwort auf die Sicherheitsüberprüfung (Captcha) ist falsch.',
                'invalid_format' => 'Bitte geben Sie eine gültige E-Mail-Adresse und ein Passwort ein.',
                'server_error'   => 'Interner Serverfehler. Bitte versuchen Sie es später erneut.',
                '2fa_redirect'   => 'Zugangsdaten bestätigt. Weiterleitung zur Zwei-Faktor-Authentifizierung...',
                'bad_creds'      => 'E-Mail-Adresse oder Passwort ist ungültig.',
                '2fa_expired'    => 'Der Bestätigungscode ist abgelaufen. Bitte melden Sie sich erneut an.',
                '2fa_invalid'    => 'Bitte geben Sie einen gültigen 6-stelligen Code ein.',
                '2fa_wrong'      => 'Falscher Sicherheitscode. Verbleibende Versuche: {left}',
                '2fa_max'        => 'Maximale Anzahl an Versuchen überschritten. Bitte melden Sie sich erneut an.',
                'logout_success' => 'Sie wurden erfolgreich abgemeldet.',
                'mail_subject'   => 'Beethoven CMS - Ihr Sicherheitscode (2FA)',
                'mail_body'      => "Hallo {name},\n\nIhr Bestätigungscode für die Anmeldung lautet: {code}\nDieser Code ist 5 Minuten gültig.\n\nFalls Sie diese Anmeldung nicht angefordert haben, ändern Sie bitte umgehend Ihr Passwort."
            ],
            'en' => [
                'lockout'        => 'Login attempts temporarily suspended for security reasons. Please try again in {min} minutes.',
                'csrf_expired'   => 'Security session expired. Please refresh the page and try again.',
                'captcha_wrong'  => 'The security verification (Captcha) answer is incorrect.',
                'invalid_format' => 'Please enter a valid email address and password.',
                'server_error'   => 'Internal server error. Please try again later.',
                '2fa_redirect'   => 'Credentials verified. Redirecting to Two-Factor Authentication...',
                'bad_creds'      => 'Invalid email address or password.',
                '2fa_expired'    => 'Verification code has expired. Please log in again.',
                '2fa_invalid'    => 'Please enter a valid 6-digit code.',
                '2fa_wrong'      => 'Incorrect verification code. Remaining attempts: {left}',
                '2fa_max'        => 'Maximum verification attempts exceeded. Please log in again.',
                'logout_success' => 'You have been logged out securely.',
                'mail_subject'   => 'Beethoven CMS - Your Security Code (2FA)',
                'mail_body'      => "Hello {name},\n\nYour two-factor verification code is: {code}\nThis code is valid for 5 minutes.\n\nIf you did not request this login, please change your password immediately."
            ],
            'ar' => [
                'lockout'        => 'تم تعليق محاولات الدخول مؤقتاً لأسباب أمنية. يرجى المحاولة بعد {min} دقيقة.',
                'csrf_expired'   => 'انتهت صلاحية جلسة الأمان، يرجى تحديث الصفحة والمحاولة مجدداً.',
                'captcha_wrong'  => 'إجابة التحقق الأمني (Captcha) غير صحيحة.',
                'invalid_format' => 'يرجى إدخال بريد إلكتروني وكلمة مرور بصيغة صحيحة.',
                'server_error'   => 'حدث خطأ داخلي في الخادم، يرجى المحاولة لاحقاً.',
                '2fa_redirect'   => 'تم التحقق من بياناتك، جاري تحويلك لإدخال رمز الأمان (2FA)...',
                'bad_creds'      => 'بيانات الدخول غير صحيحة.',
                '2fa_expired'    => 'انتهت صلاحية رمز التحقق الثنائي، يرجى تسجيل الدخول مجدداً.',
                '2fa_invalid'    => 'يرجى إدخال رمز مكون من 6 أرقام صحيحة.',
                '2fa_wrong'      => 'رمز التحقق غير صحيح. المحاولات المتبقية: {left}',
                '2fa_max'        => 'تم تجاوز الحد الأقصى لمحاولات التحقق الثنائي. يرجى إعادة تسجيل الدخول.',
                'logout_success' => 'تم تسجيل الخروج بنجاح.',
                'mail_subject'   => 'Beethoven CMS - رمز التحقق الثنائي (2FA)',
                'mail_body'      => "مرحباً {name},\n\nرمز التحقق الثنائي الخاص بتسجيل الدخول إلى لوحة التحكم هو: {code}\nهذا الرمز صالح لمدة 5 دقائق فقط.\n\nإذا لم تطلب تسجيل الدخول، يرجى تغيير كلمة المرور فوراً."
            ]
        ];

        $text = $messages[$lang][$key] ?? $messages['de'][$key] ?? $key;
        foreach ($replace as $k => $v) {
            $text = str_replace('{' . $k . '}', (string)$v, $text);
        }
        return $text;
    }

    private function respond(
        bool $isAjax,
        bool $success,
        string $message,
        string $redirectUrl = '/admin/login',
        int $statusCode = 200,
        ?string $newCaptcha = null
    ): void {
        if ($isAjax) {
            http_response_code($statusCode);
            header('Content-Type: application/json; charset=utf-8');
            $payload = [
                'success'  => $success,
                'message'  => $message,
                'redirect' => $redirectUrl
            ];
            if ($newCaptcha !== null) {
                $payload['new_captcha'] = $newCaptcha;
            }
            echo json_encode($payload, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!$success) {
            $_SESSION['login_error'] = $message;
        }
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function login(): void
    {
        $this->ensureSession();

        if (!empty($_SESSION['is_logged_in']) && in_array($_SESSION['role'] ?? '', ['admin', 'super_admin'], true)) {
            header('Location: /admin/dashboard');
            exit;
        }

        $error_msg = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        // توليد رمز CSRF موحد وتجهيز الكابتشا
        $csrf_token = Security::generateCsrfToken();
        $this->generateCaptcha();

        $viewFile = __DIR__ . '/../../Views/admin/login.php';
        if (is_file($viewFile)) {
            require_once $viewFile;
        } else {
            http_response_code(404);
            echo 'Login View file not found.';
        }
    }

    public function authenticate(): void
    {
        $this->ensureSession();
        $this->getCurrentLang();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/login');
            exit;
        }

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string)$_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                  || (isset($_SERVER['HTTP_ACCEPT']) && str_contains((string)$_SERVER['HTTP_ACCEPT'], 'application/json'));

        // 1. التحقق من الحظر المؤقت
        $lockUntil = (int)($_SESSION['login_lock_until'] ?? 0);
        if ($lockUntil > time()) {
            $remainingMinutes = (int)ceil(($lockUntil - time()) / 60);
            $this->respond($isAjax, false, $this->msg('lockout', ['min' => $remainingMinutes]), '/admin/login', 429);
        }

        // 2. التحقق من رمز CSRF الموحد
        $headers = function_exists('getallheaders') ? array_change_key_case(getallheaders(), CASE_LOWER) : [];
        $token   = (string)($headers['x-csrf-token'] ?? $_POST['csrf_token'] ?? '');

        if (!Security::verifyCsrfToken($token)) {
            $this->respond($isAjax, false, $this->msg('csrf_expired'), '/admin/login', 403);
        }

        // 3. التحقق من الكابتشا الحسابية وتجديدها عند الخطأ
        if (isset($_POST['captcha'])) {
            $userCaptcha     = trim((string)$_POST['captcha']);
            $expectedCaptcha = (string)($_SESSION['captcha_result'] ?? '');
            if ($expectedCaptcha === '' || !hash_equals($expectedCaptcha, $userCaptcha)) {
                $this->recordFailedAttempt();
                $nextCaptcha = $this->generateCaptcha();
                $this->respond($isAjax, false, $this->msg('captcha_wrong'), '/admin/login', 400, $nextCaptcha);
            }
        }

        // 4. التحقق من صيغة البريد وكلمة المرور
        $email    = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190 || mb_strlen($password) > 255) {
            $nextCaptcha = $this->generateCaptcha();
            $this->respond($isAjax, false, $this->msg('invalid_format'), '/admin/login', 400, $nextCaptcha);
        }

        // 5. التحقق من قاعدة البيانات بزمن ثابت (Timing-Safe)
        $adminData = null;
        $dummyHash = '$2y$12$eImiTXuWVxfM37uY4JANjQ.5k0Z6F6W8V2B7N9M1L3K5J7H9G2F4D';

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM admins WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            $storedHash = is_array($admin) && !empty($admin['password']) ? (string)$admin['password'] : $dummyHash;
            $isPasswordValid = password_verify($password, $storedHash);

            if ($admin && $isPasswordValid) {
                if (password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare('UPDATE admins SET password = :pwd WHERE id = :id');
                    $updateStmt->execute(['pwd' => $newHash, 'id' => $admin['id']]);
                }

                $adminData = [
                    'role'  => $admin['role'] ?? 'admin',
                    'name'  => $admin['name'] ?? 'Admin',
                    'email' => $admin['email'],
                    'id'    => (int)$admin['id']
                ];
            }
        } catch (Throwable $e) {
            error_log('Critical Auth Database Error: ' . $e->getMessage());
            $this->respond($isAjax, false, $this->msg('server_error'), '/admin/login', 500);
        }

        // 6. نجاح التحقق الأولي والانتقال إلى المصادقة الثنائية (2FA)
        if ($adminData !== null) {
            $savedLang = $this->getCurrentLang();
            unset($_SESSION['login_attempts'], $_SESSION['login_lock_until'], $_SESSION['captcha_num1'], $_SESSION['captcha_num2'], $_SESSION['captcha_result']);

            session_regenerate_id(true);
            Security::rotateCsrfToken();

            $_SESSION['admin_login_lang'] = $savedLang;
            $_SESSION['site_lang']        = $savedLang;

            $otpCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $_SESSION['pending_2fa_admin'] = $adminData;
            $_SESSION['2fa_code_hash']     = hash('sha256', $otpCode);
            $_SESSION['2fa_expires_at']    = time() + self::OTP_EXPIRY_SECONDS;
            $_SESSION['2fa_attempts']      = 0;
            $_SESSION['2fa_csrf']          = bin2hex(random_bytes(32));
            $_SESSION['user_agent_hash']   = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');

            if (($_ENV['APP_ENV'] ?? 'production') !== 'production') {
                $_SESSION['2fa_code'] = $otpCode;
            } else {
                unset($_SESSION['2fa_code']);
                $this->send2faEmail($adminData['email'], $adminData['name'], $otpCode);
            }

            $this->respond($isAjax, true, $this->msg('2fa_redirect'), '/admin/verify-2fa', 200);
        }

        // 7. فشل تسجيل الدخول
        $this->recordFailedAttempt();
        $nextCaptcha = $this->generateCaptcha();
        $this->respond($isAjax, false, $this->msg('bad_creds'), '/admin/login', 401, $nextCaptcha);
    }

    public function show2fa(): void
    {
        $this->ensureSession();

        if (!empty($_SESSION['is_logged_in'])) {
            header('Location: /admin/dashboard');
            exit;
        }

        if (empty($_SESSION['pending_2fa_admin']) || empty($_SESSION['2fa_code_hash'])) {
            header('Location: /admin/login');
            exit;
        }

        if (time() > (int)($_SESSION['2fa_expires_at'] ?? 0)) {
            $this->clear2faSession();
            $_SESSION['login_error'] = $this->msg('2fa_expired');
            header('Location: /admin/login');
            exit;
        }

        if (empty($_SESSION['2fa_csrf'])) {
            $_SESSION['2fa_csrf'] = bin2hex(random_bytes(32));
        }

        $error_message = $_SESSION['2fa_error'] ?? '';
        unset($_SESSION['2fa_error']);

        $viewFile = __DIR__ . '/../../Views/admin/verify_2fa.php';
        if (is_file($viewFile)) {
            require_once $viewFile;
        } else {
            http_response_code(404);
            echo '2FA View file not found.';
        }
    }

    public function verify2fa(): void
    {
        $this->ensureSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/verify-2fa');
            exit;
        }

        if (empty($_SESSION['pending_2fa_admin']) || empty($_SESSION['2fa_code_hash'])) {
            header('Location: /admin/login');
            exit;
        }

        $token        = (string)($_POST['csrf_token'] ?? '');
        $sessionToken = (string)($_SESSION['2fa_csrf'] ?? '');
        if ($token === '' || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
            $_SESSION['2fa_error'] = $this->msg('csrf_expired');
            header('Location: /admin/verify-2fa');
            exit;
        }

        if (time() > (int)($_SESSION['2fa_expires_at'] ?? 0)) {
            $this->clear2faSession();
            $_SESSION['login_error'] = $this->msg('2fa_expired');
            header('Location: /admin/login');
            exit;
        }

        $currentAgentHash = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');
        if (!empty($_SESSION['user_agent_hash']) && !hash_equals($_SESSION['user_agent_hash'], $currentAgentHash)) {
            $this->clear2faSession();
            header('Location: /admin/login');
            exit;
        }

        $inputCode = trim((string)($_POST['verification_code'] ?? ''));
        if (!preg_match('/^[0-9]{6}$/', $inputCode)) {
            $_SESSION['2fa_error'] = $this->msg('2fa_invalid');
            header('Location: /admin/verify-2fa');
            exit;
        }

        $inputHash = hash('sha256', $inputCode);
        if (hash_equals((string)$_SESSION['2fa_code_hash'], $inputHash)) {
            $adminData = $_SESSION['pending_2fa_admin'];
            $savedLang = $this->getCurrentLang();
            $this->clear2faSession();

            session_regenerate_id(true);
            Security::rotateCsrfToken();

            $_SESSION['is_logged_in']     = true;
            $_SESSION['role']             = $adminData['role'];
            $_SESSION['admin_name']       = $adminData['name'];
            $_SESSION['admin_id']         = $adminData['id'];
            $_SESSION['admin_login_lang'] = $savedLang;
            $_SESSION['site_lang']        = $savedLang;
            $_SESSION['user_agent_hash']  = $currentAgentHash;
            $_SESSION['LAST_ACTIVITY']    = time();

            header('Location: /admin/dashboard');
            exit;
        }

        $attempts = (int)($_SESSION['2fa_attempts'] ?? 0) + 1;
        $_SESSION['2fa_attempts'] = $attempts;

        if ($attempts >= self::MAX_OTP_ATTEMPTS) {
            $this->clear2faSession();
            $_SESSION['login_error'] = $this->msg('2fa_max');
            header('Location: /admin/login');
            exit;
        }

        $_SESSION['2fa_error'] = $this->msg('2fa_wrong', ['left' => (self::MAX_OTP_ATTEMPTS - $attempts)]);
        header('Location: /admin/verify-2fa');
        exit;
    }

    private function clear2faSession(): void
    {
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

    private function send2faEmail(string $toEmail, string $adminName, string $otpCode): void
    {
        $fromEmail = $_ENV['MAIL_FROM_ADDRESS'] ?? ('no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'beethoven-services.de'));
        $subject   = $this->msg('mail_subject');
        $body      = $this->msg('mail_body', ['name' => $adminName, 'code' => $otpCode]);

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'From: Beethoven CMS <' . $fromEmail . '>',
            'X-Mailer: PHP/' . phpversion()
        ];

        @mail($toEmail, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
    }

    private function recordFailedAttempt(): void
    {
        $attempts = (int)($_SESSION['login_attempts'] ?? 0) + 1;
        $_SESSION['login_attempts'] = $attempts;

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $_SESSION['login_lock_until'] = time() + self::LOCKOUT_SECONDS;
            $_SESSION['login_attempts'] = 0;
        }
    }

    public function logout(): void
    {
        $this->ensureSession();
        $logoutText = $this->msg('logout_success');

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();

        header('Location: /admin/login?message=' . urlencode($logoutText));
        exit;
    }
}
