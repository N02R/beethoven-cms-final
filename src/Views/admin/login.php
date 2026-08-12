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

        .login-card-wrapper {
            width: 100%;
            max-width: 480px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 40px;
            position: relative;
        }

        .back-home-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 25px;
            transition: color 0.2s ease;
        }

        .back-home-btn:hover {
            color: #2563eb;
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(37, 99, 235, 0.3);
        }

        .captcha-box {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 12px;
            font-size: 0.9rem;
            color: #475569;
        }
    </style>
</head>
<body>

<div class="login-card-wrapper">
    <!-- زر العودة للموقع -->
    <a href="index.php?url=home" class="back-home-btn">
        <i class="fa-solid fa-arrow-right"></i> العودة للموقع الرئيسي
    </a>

    <h3 class="fw-bold mb-1">تسجيل دخول المشرف</h3>
    <p class="text-muted mb-4">أهلاً بك مجدداً، يرجى إدخال بياناتك للمتابعة</p>

    <?php if (!empty($data['error_msg'])): ?>
        <div class="alert alert-danger p-2 small text-center mb-3"><?php echo htmlspecialchars($data['error_msg'], ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form action="index.php?url=admin/login/process" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="mb-3">
            <label class="form-label small fw-bold">اسم المستخدم</label>
            <input type="text" class="form-control" name="username" required autocomplete="username">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">كلمة المرور</label>
            <input type="password" class="form-control" name="password" required autocomplete="current-password">
        </div>

        <div class="mb-4 captcha-box">
            <i class="fa-solid fa-shield-halved text-primary me-1"></i>
            كم الناتج: <strong><?php echo $data['captcha_num1']; ?> + <?php echo $data['captcha_num2']; ?></strong>؟
            <input type="number" class="form-control mt-2" name="captcha_answer" required placeholder="أدخل الناتج الرقمي">
        </div>

        <button type="submit" class="btn btn-primary-custom w-100">دخول اللوحة</button>
    </form>
</div>

</body>
</html>
