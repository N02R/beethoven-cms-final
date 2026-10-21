<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class DashboardController {
    
    public function index(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // التحقق من أن الأدمن مسجل دخوله
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            header("Location: /admin/login");
            exit;
        }

        require_once __DIR__ . '/../../Views/admin/dashboard.php';
    }
}
