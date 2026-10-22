<?php
declare(strict_types=1);

namespace App\Core;

class Security {
    
    /**
     * توليد رمز حماية من تزوير الطلبات عبر المواقع (CSRF Token) بشكل آمن
     */
    public static function generateCsrfToken(): string {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            return $_SESSION['csrf_token'];
        }
        return '';
    }

    /**
     * التحقق من صحة رمز الـ CSRF المرسل بطريقة آمنة ضد هجمات التوقيت (Timing Attacks)
     */
    public static function verifyCsrfToken(?string $token): bool {
        if (session_status() !== PHP_SESSION_ACTIVE || empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * تنظيف المدخلات النصية العامة لمنع هجمات XSS وحقن النصوص الخبيثة
     */
    public static function sanitize(mixed $data): mixed {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        
        if (is_string($data)) {
            return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }

        return $data;
    }

    /**
     * تنظيف الروابط (URLs) للتحقق من سلامتها
     */
    public static function sanitizeUrl(string $url): string {
        $sanitized = filter_var(trim($url), FILTER_SANITIZE_URL);
        return filter_var($sanitized, FILTER_VALIDATE_URL) ? $sanitized : '';
    }
}
