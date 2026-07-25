<?php
/**
 * Beethoven CMS - Secure Authentication Process (Updated)
 * معالجة تسجيل الدخول مع دعم CSRF, Captcha, والمعايير الأمنية
 */

// 1. استدعاء ملفات الأمان والاتصال
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db_connect.php';

// التأكد من أن الطلب تم عبر POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method Not Allowed");
}

// 2. التحقق من حماية الـ CSRF Token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['login_csrf']) || !hash_equals($_SESSION['login_csrf'], $_POST['csrf_token'])) {
    $_SESSION['login_error'] = "انتهت صلاحية الجلسة أو حدث خطأ في التحقق الأمن (CSRF). يرجى إعادة المحاولة.";
    header("Location: ../login.php");
    exit;
}

// 3. التحقق من الـ Math Captcha
$user_captcha = trim($_POST['captcha_answer'] ?? '');
if (!isset($_SESSION['captcha_result']) || intval($user_captcha) !== intval($_SESSION['captcha_result'])) {
    $_SESSION['login_error'] = "إجابة التحقق الأمني (Captcha) غير صحيحة.";
    header("Location: ../login.php");
    exit;
}
// إعادة توليد كابتشا جديدة بعد محاولة الدخول (سواء نجحت أو فشلت) لمنع إعادة الاستخدام
unset($_SESSION['captcha_result'], $_SESSION['captcha_num1'], $_SESSION['captcha_num2']);

// 4. استقبال وتنظيف المدخلات (ندعم المدخل سواء كان بريداً إلكترونياً أو اسم مستخدم)
$identity = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($identity) || empty($password)) {
    $_SESSION['login_error'] = "الرجاء إدخال اسم المستخدم/البريد وكلمة المرور.";
    header("Location: ../login.php");
    exit;
}

try {
    // 5. البحث عن المشرف بواسطة البريد الإلكتروني أو الاسم
    $stmt = $pdo->prepare("SELECT id, full_name, email, password_hash, role, is_active FROM admins WHERE email = ? OR full_name = ? LIMIT 1");
    $stmt->execute([$identity, $identity]);
    $admin = $stmt->fetch();

    // 6. التحقق من صحة البيانات وحالة الحساب
    if ($admin && $admin['is_active'] == 1 && password_verify($password, $admin['password_hash'])) {
        
        // إعادة توليد معرف الجلسة كلياً لمنع Session Fixation
        session_regenerate_id(true);

        // مسح رمز الـ CSRF القديم لأسباب أمنية
        unset($_SESSION['login_csrf']);

        // تخزين متغيرات الجلسة المتوافقة مع واجهتك
        $_SESSION['is_logged_in'] = true;
        $_SESSION['admin_id']     = $admin['id'];
        $_SESSION['admin_name']   = $admin['full_name'];
        $_SESSION['role']         = $admin['role']; // مثل 'admin' أو 'super_admin'

        // التوجيه إلى لوحة التحكم (تأكد من اسم ملف لوحة التحكم لديك، مثلا admin_dashboard.php أو dashboard.php)
        header("Location: ../admin_dashboard.php");
        exit;

    } else {
        // رسالة خطأ عامة
        $_SESSION['login_error'] = "بيانات الدخول غير صحيحة أو الحساب معطل.";
        header("Location: ../login.php");
        exit;
    }

} catch (\PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    $_SESSION['login_error'] = "حدث خطأ غير متوقع، يرجى المحاولة لاحقاً.";
    header("Location: ../login.php");
    exit;
}
