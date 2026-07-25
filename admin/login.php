<?php
/**
 * Beethoven CMS - Secure Login Page
 */
require_once __DIR__ . '/api/secure_session.php';
define('ALLOWED_ACCESS', true);

// إذا كان المشرف مسجلاً دخوله مسبقاً، يتم توجيهه للوحة التحكم مباشرة
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')) {
    header("Location: admin_dashboard.php");
    exit;
}

// استلام رسالة الخطأ من الجلسة (إن وجدت) ثم حذفها لئلا تبقى ظاهرة
$error_msg = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

// توليد رمز CSRF إن لم يكن موجوداً
if (empty($_SESSION['login_csrf'])) {
    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
}

// توليد أرقام التحقق الأمني (Math Captcha) إن لم تكن موجودة
if (empty($_SESSION['captcha_num1'])) {
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
    $_SESSION['captcha_result'] = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرفين - Beethoven City Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .login-card { max-width: 420px; margin: 80px auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .login-header { background: #1e293b; color: #fff; padding: 25px; border-radius: 12px 12px 0 0; text-align: center; }
        .btn-primary-custom { background: #66aeee; border: none; color: #fff; font-weight: 600; padding: 12px; width: 100%; border-radius: 6px; }
        .btn-primary-custom:hover { background: #509ad7; }
    </style>
</head>
<body>

<div class="container">
    <div class="login-card">
        <div class="login-header">
            <h4 class="mb-1"><i class="bi bi-shield-lock-fill text-info"></i> بوابة الإدارة الآمنة</h4>
            <p class="text-muted small mb-0">نظام إدارة المحتوى التجاري (CMS)</p>
        </div>
        <div class="p-4">
            
            <!-- عرض رسائل الخطأ بأمان تام -->
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger py-2 small text-center mb-3">
                    <?php echo htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form action="api/auth_process.php" method="POST">
                <!-- حماية CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['login_csrf']; ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold small">اسم المستخدم أو البريد الإلكتروني</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="username" required autocomplete="username">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control" name="password" required autocomplete="current-password">
                    </div>
                </div>

                <!-- حماية Captcha الرياضية -->
                <div class="mb-3 bg-light p-3 rounded border">
                    <label class="form-label fw-bold small">التحقق الأمني: كم الناتج <?php echo $_SESSION['captcha_num1']; ?> + <?php echo $_SESSION['captcha_num2']; ?>؟</label>
                    <input type="number" class="form-control" name="captcha_answer" required placeholder="أدخل الناتج الرقمي">
                </div>

                <button type="submit" class="btn btn-primary-custom mt-2">تسجيل الدخول الآمن</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
