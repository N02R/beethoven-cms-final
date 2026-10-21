<?php
declare(strict_types=1);
$path_prefix = $path_prefix ?? '';
$csrf_token = \App\Core\Security::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo ($current_lang === 'ar') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المشرفين - Beethoven CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { width: 100%; max-width: 420px; background: #ffffff; border-radius: 20px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
        .login-card h3 { font-weight: 700; color: #0f172a; margin-bottom: 8px; }
        .form-control { border-radius: 12px; height: 48px; border: 1px solid #cbd5e1; padding: 0 16px; }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15); }
        .btn-login { background: linear-gradient(135deg, #2563eb, #3b82f6); color: white; border: none; border-radius: 12px; height: 48px; font-weight: 600; width: 100%; transition: 0.3s; }
        .btn-login:hover { background: linear-gradient(135deg, #1d4ed8, #2563eb); transform: translateY(-1px); }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <i class="bi bi-shield-lock-fill text-primary" style="font-size: 3rem;"></i>
        <h3 class="mt-2">لوحة التحكم</h3>
        <p class="text-muted small">قم بتسجيل الدخول لإدارة محتوى الموقع</p>
    </div>

    <div id="loginAlert" class="alert d-none text-center small rounded-3"></div>

    <form id="loginForm">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="mb-3 text-start" dir="ltr">
            <label class="form-label small fw-bold text-secondary">البريد الإلكتروني</label>
            <input type="email" class="form-control" name="email" required placeholder="admin@beethoven.de">
        </div>

        <div class="mb-4 text-start" dir="ltr">
            <label class="form-label small fw-bold text-secondary">كلمة المرور</label>
            <input type="password" class="form-control" name="password" required placeholder="••••••••">
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
                window.location.href = '/admin/dashboard';
            }, 1000);
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
