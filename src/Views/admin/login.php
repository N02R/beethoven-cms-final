<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Admin Login View (Production-Ready & BSI/OWASP Compliant)
 */
$logout_msg = isset($_GET['message']) ? trim((string)$_GET['message']) : '';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
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

        .captcha-badge {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            font-weight: 700;
            border-radius: 12px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            min-width: 120px;
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
    </style>
</head>
<body>

<div class="login-card-wrapper">
    <a href="/home" class="back-home-btn">
        <i class="fa-solid fa-arrow-right"></i> العودة للموقع الرئيسي
    </a>

    <h3 class="fw-bold mb-1">تسجيل دخول المشرف</h3>
    <p class="text-muted mb-4">أهلاً بك مجدداً، يرجى إدخال بياناتك للمتابعة</p>

    <?php if ($logout_msg !== ''): ?>
        <div class="alert alert-info text-center small mb-3">
            <?php echo htmlspecialchars($logout_msg, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- مكان عرض رسائل الخطأ أو النجاح الديناميكية -->
    <div id="login-alert" class="alert <?php echo !empty($error_msg) ? 'alert-danger' : 'd-none'; ?> text-center small mb-3">
        <?php echo !empty($error_msg) ? htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8') : ''; ?>
    </div>

    <form id="loginForm" method="POST" action="/admin/login/process" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['login_csrf'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="mb-3">
            <label for="emailInput" class="form-label small fw-bold">البريد الإلكتروني (Email)</label>
            <input type="email" id="emailInput" class="form-control" name="email" required autocomplete="email" placeholder="name@example.com">
        </div>

        <div class="mb-3">
            <label for="passwordInput" class="form-label small fw-bold">كلمة المرور</label>
            <input type="password" id="passwordInput" class="form-control" name="password" required autocomplete="current-password" placeholder="••••••••">
        </div>

        <?php if (isset($_SESSION['captcha_num1'], $_SESSION['captcha_num2'])): ?>
        <div class="mb-4">
            <label for="captchaInput" class="form-label small fw-bold">التحقق الأمني</label>
            <div class="d-flex gap-2 align-items-center">
                <div class="captcha-badge" dir="ltr">
                    <?php echo (int)$_SESSION['captcha_num1']; ?> + <?php echo (int)$_SESSION['captcha_num2']; ?> = ?
                </div>
                <input type="number" id="captchaInput" class="form-control" name="captcha" required autocomplete="off" placeholder="الناتج">
            </div>
        </div>
        <?php endif; ?>

        <button type="submit" id="submitBtn" class="btn btn-primary-custom w-100">دخول اللوحة</button>
    </form>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('login-alert');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> جاري التحقق...';
    alertBox.classList.add('d-none');

    const formData = new FormData(this);

    try {
        const response = await fetch('/admin/login/process', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();

        alertBox.classList.remove('d-none');
        if (result.success) {
            alertBox.className = 'alert alert-success p-2 small text-center mb-3';
            alertBox.textContent = result.message;
            setTimeout(() => {
                window.location.href = result.redirect || '/admin/dashboard';
            }, 800);
        } else {
            alertBox.className = 'alert alert-danger p-2 small text-center mb-3';
            alertBox.textContent = result.message;
            btn.disabled = false;
            btn.textContent = 'دخول اللوحة';

            // تحديث الصفحة إذا انتهت صلاحية CSRF لتوليد رمز جديد
            if (response.status === 403) {
                setTimeout(() => window.location.reload(), 1500);
            }
        }
    } catch (error) {
        alertBox.classList.remove('d-none');
        alertBox.className = 'alert alert-danger p-2 small text-center mb-3';
        alertBox.textContent = 'حدث خطأ في الاتصال بالخادم، يرجى المحاولة لاحقاً.';
        btn.disabled = false;
        btn.textContent = 'دخول اللوحة';
    }
});
</script>

</body>
</html>
