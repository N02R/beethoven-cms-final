<?php
namespace App\Core;

class SessionManager {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            // إعدادات أمنية للجلسة
            session_set_cookie_params([
                'lifetime' => 86400,
                'path' => '/',
                'httponly' => true, // منع الوصول للجلسة عبر JavaScript (حماية من XSS)
                'secure' => false,  // اجعليها true إذا كنتِ تستخدمين HTTPS
                'samesite' => 'Strict'
            ]);
            session_start();
            
            // حماية ضد Session Fixation
            if (!isset($_SESSION['initiated'])) {
                session_regenerate_id(true);
                $_SESSION['initiated'] = true;
            }
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy() {
        session_destroy();
    }
}
