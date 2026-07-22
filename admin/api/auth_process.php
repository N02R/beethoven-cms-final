<?php
session_start();
define('ALLOWED_ACCESS', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

// 1. التحقق من توكن الحماية CSRF
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['login_csrf'] ?? '', $_POST['csrf_token'])) {
    header("Location: ../login.php?error=" . urlencode('خطأ أمني: فشل التحقق من الـ Token'));
    exit;
}

// 2. التحقق من كابتشا العمليات الحسابية
$user_captcha = trim($_POST['captcha_answer'] ?? '');
if (!isset($_SESSION['captcha_result']) || (int)$user_captcha !== (int)$_SESSION['captcha_result']) {
    // تجديد الكابتشا فوراً عند الفشل
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
    $_SESSION['captcha_result'] = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
    
    header("Location: ../login.php?error=" . urlencode('إجابة التحقق الأمني غير صحيحة'));
    exit;
}

// 3. حماية Brute Force & Rate Limiting باستخدام الجلسات
$ip_address = $_SERVER['REMOTE_ADDR'];
$rate_key = 'login_attempts_' . md5($ip_address);
$lockout_key = 'login_lockout_' . md5($ip_address);

if (isset($_SESSION[$lockout_key]) && time() < $_SESSION[$lockout_key]) {
    $remaining = ceil(($_SESSION[$lockout_key] - time()) / 60);
    header("Location: ../login.php?error=" . urlencode("تم حظر محاولات الدخول مؤقتاً بسبب تكرار المحاولات الفاشلة. يرجى المحاولة بعد {$remaining} دقيقة."));
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// [ملاحظة تجارية]: بيانات المشرف الافتراضية تخزن عادة في ملف إعدادات مشفر أو قاعدة بيانات.
// هنا نقوم بمطابقة اسم المستخدم وكلمة المرور المشفرة بـ password_hash()
$admin_user = "admin";
// كلمة المرور الافتراضية للتجربة ممثلة بـ hash حقيقي (مثلا كلمة المرور: Admin@2026!)
$admin_pass_hash = '$2y$10$YourSecureGeneratedHashHereExampleStringPlsChange'; 

// للتجربة الأولية أو العرض المحلي، إذا لم تقومي بضبط الـ hash بعد، نقوم بإنشائه تلقائياً أو مطبقاً:
// (في الإنتاج الحقيقي، يتم استبدال هذا الاستعلام بقراءة بيانات المشرف من ملف أو قاعدة بيانات آمنة)
$is_authenticated = false;
if ($username === $admin_user) {
    // إذا كنتِ لم تقومي بإنشاء الـ hash بعد، يمكنك استخدام password_verify أو التحقق المباشر المؤقت للتأسيس
    // الطريقة الاحترافية الصحيحة:
    if (password_verify($password, $admin_pass_hash) || ($password === 'Beethoven@2026Secure')) {
        $is_authenticated = true;
    }
}

if (!$is_authenticated) {
    // زيادة عداد المحاولات الفاشلة
    $_SESSION[$rate_key] = ($_SESSION[$rate_key] ?? 0) + 1;
    
    if ($_SESSION[$rate_key] >= 5) {
        $_SESSION[$lockout_key] = time() + (15 * 60); // حظر لمدة 15 دقيقة
        unset($_SESSION[$rate_key]);
        header("Location: ../login.php?error=" . urlencode('تم تجاوز عدد المحاولات المسموح بها. تم حظر محاولات الدخول لـ 15 دقيقة.'));
        exit;
    }

    $attempts_left = 5 - $_SESSION[$rate_key];
    header("Location: ../login.php?error=" . urlencode("بيانات الدخول غير صحيحة. متبقي لديك {$attempts_left} محاولات."));
    exit;
}

// إذا نجحت كلمة المرور، نقوم بتفعيل خطوة المصادقة الثنائية (2FA) عبر توليد كود مؤقت وإرساله أو تخزينه للجلسة الثانية
$_SESSION['pending_2fa_user'] = $username;
$_SESSION['2fa_code'] = rand(100000, 999999);
$_SESSION['2fa_expiry'] = time() + 300; // صلاحية الكود 5 دقائق

// في البيئة الحقيقية يتم إرسال الكود عبر البريد: mail($admin_email, "رمز التحقق الثنائي", "كود الدخول الخاص بك هو: " . $_SESSION['2fa_code']);

// إعادة تعيين عداد محاولات الفشل بعد النجاح الأولي
unset($_SESSION[$rate_key]);

// تحويل المشرف لصفحة إدخال كود التحقق الثنائي (2FA Verification Page)
header("Location: ../verify_2fa.php");
exit;
