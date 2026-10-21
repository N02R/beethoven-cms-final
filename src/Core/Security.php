<?php
declare(strict_types=1);

namespace App\Core;

class Security {
    
    /**
     * توليد رمز حماية من تزوير الطلبات عبر المواقع (CSRF Token)
     */
    public static function generateCsrfToken(): string {
        // التأكد من أن الجلسة نشطة وآمنة
        if (session_status() === PHP_SESSION_ACTIVE && empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'] ?? '';
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
}
