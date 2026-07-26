<?php
/**
 * auth_process.php - معالج تسجيل الدخول مع ربطه بقاعدة البيانات والتحقق الثنائي (2FA)
 */

define('ALLOWED_ACCESS', true);
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../includes/db.php';


// تعيين رأس الاستجابة
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

$input_identity = trim($_POST['username'] ?? ''); // مدخلات المستخدم (يمكن أن تكون البريد أو اسم المستخدم)
$password = $_POST['password'] ?? '';

if (empty($input_identity) || empty($password)) {
    $_SESSION['login_error'] = 'يرجى ملء كافة الحقول المطلوبة.';
    header("Location: ../login.php");
    exit;
}

try {
    // البحث عن المشرف في قاعدة البيانات إما عبر البريد الإلكتروني أو الاسم الكامل
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? OR full_name = ? LIMIT 1");
    $stmt->execute([$input_identity, $input_identity]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // التحقق من وجود المستخدم ومطابقة كلمة المرور المشفرة
    if ($admin && password_verify($password, $admin['password_hash'])) {
        
        // التأكد من أن الحساب مفعل
        if (isset($admin['is_active']) && (int)$admin['is_active'] === 0) {
            $_SESSION['login_error'] = 'هذا الحساب معطل، يرجى مراجعة الإدارة.';
            header("Location: ../login.php");
            exit;
        }

        // نجاح التحقق! توليد رمز التحقق الثنائي (2FA Code) مكون من 6 أرقام
        $code_2fa = rand(100000, 999999);
        
        $_SESSION['pending_2fa_user'] = $admin['email'];
        $_SESSION['2fa_code'] = $code_2fa;
        $_SESSION['2fa_expiry'] = time() + 300; // صلاحية الكود 5 دقائق
        $_SESSION['role'] = $admin['role']; // مثل super_admin أو admin
        $_SESSION['admin_id'] = $admin['id'];

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

} catch (\Exception $e) {
    error_log("Login Error: " . $e->getMessage());
    $_SESSION['login_error'] = 'حدث خطأ في النظام، يرجى المحاولة لاحقاً.';
    header("Location: ../login.php");
    exit;
}
