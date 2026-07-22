<?php
session_start();
define('ALLOWED_ACCESS', true);

// إذا كان المشرف مسجلاً دخوله مسبقاً، يتم توجيهه للوحة التحكم مباشرة
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && $_SESSION['role'] === 'admin') {
    header("Location: dashboard.php");
    exit;
}

$error_message = '';

// توليد رمز CSRF ومنطقة الكابتشا البسيطة الآمنة إن لم تكن موجودة
if (empty($_SESSION['login_csrf'])) {
    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
}

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
            <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger py-2 small text-center"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <form action="api/auth_process.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['login_csrf']; ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold small">اسم المستخدم أو البريد</label>
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

                <!-- حماية Captcha بسيطة ومضمنة -->
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
