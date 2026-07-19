<?php
namespace App\Core;

class Auth {
    public static function checkRole(array $allowedRoles): bool {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['role'])) return false;
        
        // التحقق مما إذا كان دور المستخدم ضمن الأدوار المسموح لها
        return in_array($_SESSION['role'], $allowedRoles);
    }
}
