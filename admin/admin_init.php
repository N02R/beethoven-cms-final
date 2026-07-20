<?php
// /admin/admin_init.php
// ملف مركزي موحد لإدارة الجلسات، الأمان، والصلاحيات

// 1. بدء الجلسة بأمان (استخدام الـ SessionManager إذا كان موجوداً أو الجلسة القياسية المشفرة)
if (session_status() === PHP_SESSION_NONE) {
    // إذا كنتِ تستخدمين كلاس الجلسات الآمن، استبدليها بـ App\Core\SessionManager::startSecureSession();
    session_start();
}

// 2. جدار الحماية الصارم (قاعدة موحدة لكل صفحات اللوحة والـ APIs)
$is_admin = isset($_SESSION['is_logged_in']) 
    && $_SESSION['is_logged_in'] === true 
    && isset($_SESSION['role']) 
    && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    // التحقق مما إذا كان الطلب قادماً عبر AJAX/API لترجع JSON بدلاً من تحويل صفحة
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || strpos($_SERVER['SCRIPT_NAME'], '/api/') !== false) {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized Access - مرفوض']);
        exit();
    }
    
    // التوجيه العادي لصفحة تسجيل الدخول إذا كان الطلب من المتصفح مباشرة
    header("Location: ../login.php");
    exit();
}
