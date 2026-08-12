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
        body { 
            background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; 
            text-align: right; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card { 
            width: 100%;
            max-width: 420px; 
            background: #ffffff; 
            border-radius: 20px; 
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15);
            border: none;
            overflow: hidden;
        }

        .login-header { 
            background: #090d16; 
            color: #ffffff; 
            padding: 35px 25px; 
            text-align: center; 
        }

        .login-header h4 {
            font-weight: 800;
            letter-spacing: -0.5px;
            font-size: 1.4rem;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.85rem;
        }

        .form-control {
            padding: 12px 16px;
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 0 10px 10px 0;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #0284c7;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.1);
        }

        /* جعل الأيقونة على اليمين */
        .input-group-text {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #64748b;
        }

        .captcha-box {
            background: #f8fafc;
            padding: 18px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
        }

        .btn-primary-custom { 
            background: #0284c7; 
            border: none; 
            color: #fff; 
            font-weight: 700; 
            padding: 14px; 
            width: 100%; 
            border-radius: 10px; 
            transition: all 0.2s ease;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
        }

        .btn-primary-custom:hover { 
            background: #0369a1; 
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.3);
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="login-card mx-auto">
        <div class="login-header">
            <h4 class="mb-1"><i class="bi bi-cpu text-info"></i> بوابة الإدارة الآمنة</h4>
            <p class="text-white-50 small mb-0">نظام إدارة المحتوى التجاري (CMS)</p>
        </div>
        <div class="p-4 p-md-4">
            
            <?php if (!empty($data['error_msg'])): ?>
                <div class="alert alert-danger py-2 small text-center mb-4 rounded-3 border-0 bg-danger-subtle text-danger-emphasis">
                    <?php echo htmlspecialchars($data['error_msg'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?url=admin/login/process" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="mb-3">
                    <label class="form-label">اسم المستخدم أو البريد الإلكتروني</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="username" required autocomplete="username">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control" name="password" required autocomplete="current-password">
                    </div>
                </div>

                <div class="mb-4 captcha-box">
                    <label class="form-label mb-2 d-block">
                        <i class="bi bi-shield-check text-info"></i> التحقق الأمني: كم الناتج <?php echo $data['captcha_num1']; ?> + <?php echo $data['captcha_num2']; ?>؟
                    </label>
                    <input type="number" class="form-control rounded-2 border" name="captcha_answer" required placeholder="أدخل الناتج الرقمي">
                </div>

                <button type="submit" class="btn btn-primary-custom mt-2">تسجيل الدخول الآمن</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
