<?php
declare(strict_types=1);

namespace App\Core;

class Security
{
    /**
     * ضمان تفعيل الجلسة قبل التعامل مع رموز الحماية
     */
    private static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }

    /**
     * توليد رمز حماية من تزوير الطلبات عبر المواقع (CSRF Token) بشكل آمن
     */
    public static function generateCsrfToken(): string
    {
        self::ensureSession();

        if (session_status() === PHP_SESSION_ACTIVE) {
            if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            return $_SESSION['csrf_token'];
        }

        return '';
    }

    /**
     * تدوير وتجديد رمز CSRF (يُستدعى بعد تسجيل الدخول أو تغيير الصلاحيات)
     */
    public static function rotateCsrfToken(): string
    {
        self::ensureSession();

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            return $_SESSION['csrf_token'];
        }

        return '';
    }

    /**
     * التحقق من صحة رمز الـ CSRF المرسل بطريقة مقاومة لهجمات التوقيت (Timing Attacks)
     */
    public static function verifyCsrfToken(?string $token): bool
    {
        self::ensureSession();

        if (
            session_status() !== PHP_SESSION_ACTIVE
            || empty($_SESSION['csrf_token'])
            || !is_string($_SESSION['csrf_token'])
            || empty($token)
        ) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * تنظيف المدخلات النصية العامة لمنع هجمات XSS
     */
    public static function sanitize(mixed $data): mixed
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }

        if (is_string($data)) {
            return htmlspecialchars(trim($data), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        return $data;
    }

    /**
     * دالة مختصرة وآمنة لطباعة النصوص في ملفات العرض (Views)
     */
    public static function e(mixed $value): string
    {
        if (is_scalar($value)) {
            return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
        return '';
    }

    /**
     * التحقق الصارم من الروابط الخارجية (http / https فقط) لمنع حقن javascript: و file://
     */
    public static function sanitizeUrl(string $url): string
    {
        $trimmed = trim($url);
        if ($trimmed === '') {
            return '';
        }

        $sanitized = filter_var($trimmed, FILTER_SANITIZE_URL);
        if (!is_string($sanitized) || filter_var($sanitized, FILTER_VALIDATE_URL) === false) {
            return '';
        }

        $scheme = strtolower((string)parse_url($sanitized, PHP_URL_SCHEME));
        return in_array($scheme, ['http', 'https'], true) ? $sanitized : '';
    }

    /**
     * تنقية الروابط المرنة (روابط داخلية نسبية، خارجية، بريد إلكتروني، وهاتف) مع حظر البروتوكولات الخطرة
     */
    public static function sanitizeHref(?string $rawUrl, string $default = '#'): string
    {
        $url = trim((string)($rawUrl ?? ''));
        if ($url === '' || $url === '#') {
            return $default;
        }

        if (preg_match('/^(javascript|data|vbscript|file):/i', $url)) {
            return $default;
        }

        if (preg_match('/^(mailto:|tel:|\/|#)/i', $url)) {
            return $url;
        }

        $validExternal = self::sanitizeUrl($url);
        return $validExternal !== '' ? $validExternal : $default;
    }
}
