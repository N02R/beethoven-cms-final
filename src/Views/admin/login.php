<?php
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرفين - Beethoven City Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; text-align: right; }
        .login-card { max-width: 420px; margin: 80px auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .login-header { background: #1e293b; color: #fff; padding: 25px; border-radius: 12px 12px 0 0; text-align: center; }
        .btn-primary-custom { background: #2563eb; border: none; color: #fff; font-weight: 600; padding: 12px; width: 100%; border-radius: 6px; transition: background 0.2s; }
        .btn-primary-custom:hover { background: #1d4ed8; }
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
            
            <?php if (!empty($data['error_msg'])): ?>
                <div class="alert alert-danger py-2 small text-center mb-3">
                    <?php echo htmlspecialchars($data['error_msg'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?url=admin/login/process" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                
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

                <div class="mb-3 bg-light p-3 rounded border">
                    <label class="form-label fw-bold small">التحقق الأمني: كم الناتج <?php echo $data['captcha_num1']; ?> + <?php echo $data['captcha_num2']; ?>؟</label>
                    <input type="number" class="form-control" name="captcha_answer" required placeholder="أدخل الناتج الرقمي">
                </div>

                <button type="submit" class="btn btn-primary-custom mt-2">تسجيل الدخول الآمن</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
