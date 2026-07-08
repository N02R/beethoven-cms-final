<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول | Beethoven CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container { min-height: 100vh; display: flex; align-items: center; }
        .branding-section { background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%); color: white; padding: 40px; }
        .form-section { padding: 40px; }
        .btn-primary { background-color: #3949ab; border: none; padding: 12px; font-weight: 600; }
        .btn-primary:hover { background-color: #1a237e; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row login-container">
        <div class="col-md-6 branding-section d-none d-md-flex flex-column justify-content-center">
            <h1>Beethoven CMS</h1>
            <p class="lead">نظام إدارة محتوى متطور، صُمم خصيصاً للتميز الألماني.</p>
        </div>

        <div class="col-md-6 form-section">
            <div class="mx-auto" style="max-width: 400px;">
                <h3 class="mb-4">أهلاً بك مجدداً</h3>
                <form action="/admin-process" method="POST">
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="أدخل كلمة المرور" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">دخول إلى لوحة التحكم</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
