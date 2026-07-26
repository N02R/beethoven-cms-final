<?php
/**
 * auth_process.php - معالج تسجيل الدخول مع دعم التحقق الثنائي (2FA)
 */

define('ALLOWED_ACCESS', true);
require_once __DIR__ . '/init.php';

// تعيين رأس الاستجابة
header('Content-Type: application/json; charset=UTF-8'); // أو التوجيه المباشر حسب رغبتك، سنستخدم التوجيه المباشر هنا لصفحات الـ HTML

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

// التحقق من CSRF الخاص بتسجيل الدخول
$client_csrf = $_POST['csrf_token'] ?? '';
if (!isset($_SESSION['login_csrf']) || !hash_equals($_SESSION['login_csrf'], $client_csrf)) {
    $_SESSION['login_error'] = 'انتهت صلاحية رمز الأمان، يرجى المحاولة مرة أخرى.';
    header("Location: ../login.php");
    exit;
}

// التحقق من كابتشا الأرقام
$user_captcha = (int)($_POST['captcha_answer'] ?? 0);
if (!isset($_SESSION['captcha_result']) || $user_captcha !== $_SESSION['captcha_result']) {
    $_SESSION['login_error'] = 'إجابة التحقق الأمني غير صحيحة.';
    header("Location: ../login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// [مثال تجريبي للتحقق من بيانات المشرف - استبدلها لاحقاً بالتحقق عبر قاعدة البيانات $pdo]
$admin_user = 'admin';
$admin_pass_hash = password_hash('Admin@2026', PASSWORD_DEFAULT); // افتراض كلمة المرور

if ($username === $admin_user && password_verify($password, $admin_pass_hash)) {
    
    // نجاح التحقق من اسم المستخدم وكلمة المرور!
    // الآن نقوم بتوليد رمز التحقق الثنائي (2FA Code) مكون من 6 أرقام
    $code_2fa = rand(100000, 999999);
    
    $_SESSION['pending_2fa_user'] = $username;
    $_SESSION['2fa_code'] = $code_2fa;
    $_SESSION['2fa_expiry'] = time() + 300; // صلاحية الكود 5 دقائق فقط
    $_SESSION['role'] = 'admin'; // أو تحديد الصلاحية حسب قاعدة البيانات

    // تنظيف كابتشا وسجل الدخول المؤقت لمنع إعادة استخدامها
    unset($_SESSION['captcha_result'], $_SESSION['login_csrf']);

    // توجيه المشرف لصفحة إدخال رمز التحقق الثنائي
    header("Location: ../verify_2fa.php");
    exit;

} else {
    $_SESSION['login_error'] = 'اسم المستخدم أو كلمة المرور غير صحيحة.';
    header("Location: ../login.php");
    exit;
}
