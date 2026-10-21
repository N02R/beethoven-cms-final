<?php
declare(strict_types=1);
$current_lang = $current_lang ?? 'ar';
$csrf_token = \App\Core\Security::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo ($current_lang === 'ar') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .login-card h3 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 10px;
            height: 46px;
            border: 1px solid #cbd5e1;
            padding: 0 14px;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .btn-login {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 10px;
            height: 46px;
            font-weight: 600;
            width: 100%;
            transition: 0.2s;
        }
        .btn-login:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <i class="bi bi-lock-fill text-primary" style="font-size: 2.5rem;"></i>
        <h3 class="mt-2">تسجيل الدخول</h3>
        <p class="text-muted small">أدخل بيانات المشرف للوصول إلى لوحة التحكم</p>
    </div>

    <div id="loginAlert" class="alert d-none text-center small rounded-3"></div>

    <form id="loginForm">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="mb-3 text-start" dir="ltr">
            <label class="form-label small fw-bold text-secondary">البريد الإلكتروني</label>
            <input type="email" class="form-control" name="email" required placeholder="admin@beethoven.de" value="admin@beethoven.de">
        </div>

        <div class="mb-4 text-start" dir="ltr">
            <label class="form-label small fw-bold text-secondary">كلمة المرور</label>
            <input type="password" class="form-control" name="password" required placeholder="••••••••" value="Admin123!">
        </div>

        <button type="submit" class="btn btn-login" id="submitBtn">تسجيل الدخول</button>
    </form>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const alertBox = document.getElementById('loginAlert');
    const submitBtn = document.getElementById('submitBtn');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> جاري التحقق...';

    const formData = new FormData(this);

    fetch('/admin/login/process', {
        method: 'POST',
        body: formData,
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        alertBox.classList.remove('d-none', 'alert-success', 'alert-danger');
        if (data.success) {
            alertBox.classList.add('alert-success');
            alertBox.textContent = data.message;
            setTimeout(() => {
                // التحويل إلى لوحة التحكم بعد نجاح الدخول
                window.location.href = '/admin/dashboard';
            }, 800);
        } else {
            alertBox.classList.add('alert-danger');
            alertBox.textContent = data.message;
            submitBtn.disabled = false;
            submitBtn.textContent = 'تسجيل الدخول';
        }
    })
    .catch(err => {
        console.error(err);
        alertBox.classList.remove('d-none');
        alertBox.classList.add('alert-danger');
        alertBox.textContent = 'حدث خطأ في الاتصال بالخادم.';
        submitBtn.disabled = false;
        submitBtn.textContent = 'تسجيل الدخول';
    });
});
</script>

</body>
</html>
