<?php
if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرفين - Beethoven CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #06b6d4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }
        .form-control {
            padding: 12px 16px;
            border-radius: 10px;
        }
        .btn-submit {
            background: #2563eb;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 700;
            width: 100%;
        }
        .btn-submit:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="fw-bold mb-2 text-center">لوحة التحكم</h3>
    <p class="text-muted mb-4 text-center">يرجى تسجيل الدخول للمتابعة</p>

    <?php if (!empty($data['error_msg'])): ?>
        <div class="alert alert-danger p-2 small text-center mb-3">
            <?php echo htmlspecialchars($data['error_msg'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?url=admin/login/process" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="mb-3">
            <label class="form-label small fw-bold">البريد الإلكتروني</label>
            <input type="email" class="form-control" name="username" required autocomplete="email" placeholder="admin@beethoven-services.de">
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold">كلمة المرور</label>
            <input type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="••••••••••••">
        </div>

        <button type="submit" class="btn-submit">دخول النظام</button>
    </form>
</div>

</body>
</html>
