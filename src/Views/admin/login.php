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
        :root {
            --primary-color: #0f172a; /* Deep Navy المؤسسي */
            --accent-color: #2563eb;  /* الأزرق الحيوي */
            --bg-color: #f8fafc;
            --border-color: #e2e8f0;
        }

        body { 
            background-color: var(--bg-color); 
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; 
            text-align: right; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card { 
            width: 100%;
            max-width: 440px; 
            background: #fff; 
            border-radius: 16px; 
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.05); 
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .login-header { 
            background: var(--primary-color); 
            color: #fff; 
            padding: 30px 25px; 
            text-align: center; 
        }

        .login-header h4 {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .form-control, .input-group-text {
            padding: 12px 15px;
            border-color: var(--border-color);
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            border-color: var(--accent-color);
        }

        .input-group-text {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .captcha-box {
            background: #f8fafc;
            padding: 16px;
            border-radius: 12px;
            border: 1px dashed var(--border-color);
        }

        .btn-primary-custom { 
            background: var(--accent-color); 
            border: none; 
            color: #fff; 
            font-weight: 600; 
            padding: 12px; 
            width: 100%; 
            border-radius: 10px; 
            transition: all 0.2s ease-in-out;
            font-size: 1rem;
        }

        .btn-primary-custom:hover { 
            background: #1d4ed8; 
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="login-card mx-auto">
        <div class="login-header">
            <h4 class="mb-1"><i class="bi bi-shield-lock-fill text-info"></i> بوابة الإدارة الآمنة</h4>
            <p class="text-white-50 small mb-0">نظام إدارة المحتوى التجاري (CMS)</p>
        </div>
        <div class="p-4 p-md-5">
            
            <?php if (!empty($data['error_msg'])): ?>
                <div class="alert alert-danger py-2 small text-center mb-4 rounded-3 border-0 bg-danger-subtle text-danger-emphasis">
                    <?php echo htmlspecialchars($data['error_msg'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?url=admin/login/process" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">اسم المستخدم أو البريد الإلكتروني</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="username" required autocomplete="username">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-secondary">كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control" name="password" required autocomplete="current-password">
                    </div>
                </div>

                <div class="mb-4 captcha-box">
                    <label class="form-label fw-bold small text-dark mb-2">
                        <i class="bi bi-shield-check text-primary"></i> التحقق الأمني: كم الناتج <?php echo $data['captcha_num1']; ?> + <?php echo $data['captcha_num2']; ?>؟
                    </label>
                    <input type="number" class="form-control" name="captcha_answer" required placeholder="أدخل الناتج الرقمي">
                </div>

                <button type="submit" class="btn btn-primary-custom mt-2">تسجيل الدخول الآمن</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
