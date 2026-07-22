<?php
session_start();
define('ALLOWED_ACCESS', true);

// إذا لم يكن هناك عملية تسجيل دخول معلقة بانتظار الـ 2FA، يتم إرجاعه لصفحة الدخول
if (!isset($_SESSION['pending_2fa_user']) || !isset($_SESSION['2fa_code'])) {
    header("Location: login.php");
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = trim($_POST['verification_code'] ?? '');
    
    // التحقق من صلاحية الوقت (Expiration Check)
    if (time() > ($_SESSION['2fa_expiry'] ?? 0)) {
        unset($_SESSION['pending_2fa_user'], $_SESSION['2fa_code'], $_SESSION['2fa_expiry']);
        header("Location: login.php?error=" . urlencode('انتهت صلاحية رمز التحقق الثنائي. يرجى تسجيل الدخول مرة أخرى.'));
        exit;
    }

    // مطابقة الكود المدخل مع الكود المخزن في الجلسة
    if (hash_equals((string)$_SESSION['2fa_code'], (string)$entered_code)) {
        // نجاح المصادقة الثنائية بالكامل! تفعيل صلاحيات المشرف
        $_SESSION['is_logged_in'] = true;
        $_SESSION['role'] = 'admin';
        $_SESSION['admin_username'] = $_SESSION['pending_2fa_user'];

        // تنظيف جلسات المصادقة المؤقتة
        unset($_SESSION['pending_2fa_user'], $_SESSION['2fa_code'], $_SESSION['2fa_expiry']);

        // تجديد معرف الجلسة لمنع هجمات Session Fixation
        session_regenerate_id(true);

        // التوجيه للوحة التحكم الرئيسية
        header("Location: dashboard.php");
        exit;
    } else {
        $error_message = 'رمز التحقق الثنائي غير صحيح. يرجى المحاولة مرة أخرى.';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق الثنائي (2FA) - Beethoven City Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .login-card { max-width: 420px; margin: 100px auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .login-header { background: #1e293b; color: #fff; padding: 25px; border-radius: 12px 12px 0 0; text-align: center; }
        .btn-primary-custom { background: #66aeee; border: none; color: #fff; font-weight: 600; padding: 12px; width: 100%; border-radius: 6px; }
        .btn-primary-custom:hover { background: #509ad7; }
    </style>
</head>
<body>

<div class="container">
    <div class="login-card">
        <div class="login-header">
            <h4 class="mb-1"><i class="bi bi-shield-check text-info"></i> التحقق الثنائي (2FA)</h4>
            <p class="text-muted small mb-0">أدخل رمز الأمان المرسل إلى بريدك الإلكتروني</p>
        </div>
        <div class="p-4">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger py-2 small text-center"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <!-- تلميح محلي للمطور (في النظام الحقيقي يتم حذفه واعتماد البريد الإلكتروني أو التطبيق) -->
            <div class="alert alert-info py-2 small mb-3">
                <i class="bi bi-info-circle"></i> [وضع التجريب]: الكود المؤقت الخاص بك هو: <strong><?php echo $_SESSION['2fa_code']; ?></strong>
            </div>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small">رمز التحقق المكون من 6 أرقام</label>
                    <input type="text" class="form-control text-center fs-4 tracking-widest" name="verification_code" maxlength="6" required autofocus autocomplete="one-time-code">
                </div>

                <button type="submit" class="btn btn-primary-custom mt-2">تحكيد وتسجيل الدخول</button>
            </form>
            
            <div class="text-center mt-3">
                <a href="login.php" class="text-muted small text-decoration-none"><i class="bi bi-arrow-right"></i> العودة لتسجيل الدخول</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
