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
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
    $_SESSION['captcha_result'] = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
    
    header("Location: ../login.php?error=" . urlencode('إجابة التحقق الأمني غير صحيحة'));
    exit;
}

// 3. حماية Brute Force & Rate Limiting ضد محاولات التخمين
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

// 4. قراءة بيانات المشرف الحقيقية من ملف الـ JSON المركزي
$config_file = __DIR__ . '/../announcement_config.json';
$global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];

$stored_admin = $global_data['admin_credentials'] ?? null;
$is_authenticated = false;

// 5. التحقق الحقيقي والآمن من اسم المستخدم وكلمة المرور المشفرة
if ($stored_admin && $username === $stored_admin['username']) {
    if (password_verify($password, $stored_admin['password_hash'])) {
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

// 6. نجاح المصادقة الأولية - تهيئة الجلسة للتنقل أو خطوة التحقق الثنائي (2FA)
$_SESSION['is_logged_in'] = true;
$_SESSION['role'] = 'admin';
$_SESSION['admin_user'] = $username;

// إعادة تعيين عداد المحاولات الفاشلة بعد النجاح
unset($_SESSION[$rate_key]);

// التوجيه المباشر للوحة التحكم (أو لصفحة الـ 2FA إذا رغبتِ بتفعيلها)
header("Location: ../dashboard.php");
exit;
