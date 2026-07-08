<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول المدير | Beethoven CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; padding: 2rem; }
        .btn-primary { background-color: #0d6efd; border-radius: 8px; transition: 0.3s; }
        .btn-primary:hover { background-color: #0b5ed7; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="login-card bg-white">
    <div class="text-center mb-4">
        <h4>Beethoven CMS</h4>
        <p class="text-muted">مرحباً بك مجدداً، يرجى تسجيل الدخول</p>
    </div>
    
    <form action="/admin-process" method="POST">
        <div class="mb-3">
            <label class="form-label">كلمة المرور</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">دخول إلى لوحة التحكم</button>
    </form>
</div>

</body>
</html>
