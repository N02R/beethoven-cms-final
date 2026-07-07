<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #0f172a; /* Dark background */
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px); /* Glassmorphism effect */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
        }
        .btn-custom { background: #6366f1; color: white; transition: 0.3s; }
        .btn-custom:hover { background: #4f46e5; }
    </style>
    <title>تسجيل الدخول - Beethoven CMS</title>
</head>
<body>

<div class="login-card shadow-lg">
    <h3 class="text-center mb-4">Beethoven CMS</h3>
    <form action="/login" method="POST">
        <div class="mb-3">
            <label class="form-label">اسم المستخدم</label>
            <input type="text" name="username" class="form-control bg-dark text-white border-0" required>
        </div>
        <div class="mb-3">
            <label class="form-label">كلمة المرور</label>
            <input type="password" name="password" class="form-control bg-dark text-white border-0" required>
        </div>
        <button type="submit" class="btn btn-custom w-100">دخول الإدارة</button>
    </form>
</div>

</body>
</html>