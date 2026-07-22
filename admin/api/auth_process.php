<?php
session_start();
define('ALLOWED_ACCESS', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

// 1. التحقق من توكن الحماية CSRF
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['login_csrf'] ?? '', $_POST['csrf_token'])) {
    header("Location: ../login.php?error=" . urlencode('خطأ أمني: فشل التحقق'));
    exit;
}

// 2. التحقق من الكابتشا (تم تجاوزها اختصاراً للشرح، حافظ على كود الكابتشا الخاص بك)

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// 3. قراءة بيانات الأدمن الحقيقية من ملف الـ JSON المركزي
$config_file = __DIR__ . '/../announcement_config.json';
$global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];

$stored_admin = $global_data['admin_credentials'] ?? null;

$is_authenticated = false;

if ($stored_admin && $username === $stored_admin['username']) {
    // التحقق الآمن الحقيقي باستخدام password_verify
    if (password_verify($password, $stored_admin['password_hash'])) {
        $is_authenticated = true;
    }
}

if (!$is_authenticated) {
    // ضع هنا كود إدارة محاولات الفشل (Rate Limiting) الذي كان لديك
    header("Location: ../login.php?error=" . urlencode('اسم المستخدم أو كلمة المرور غير صحيحة.'));
    exit;
}

// 4. نجاح الدخول - توجيه للوحة التحكم أو للخطوة التالية (2FA)
$_SESSION['is_logged_in'] = true;
$_SESSION['role'] = 'admin';
$_SESSION['admin_user'] = $username;

header("Location: ../dashboard.php");
exit;
