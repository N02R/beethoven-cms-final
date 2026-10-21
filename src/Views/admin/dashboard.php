<?php
declare(strict_types=1);
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - Beethoven CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .dashboard-card { background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 10px 25px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-top: 80px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">لوحة التحكم الرئيسية</h3>
                        <p class="text-muted small mb-0">Beethoven CMS - النظام الإداري</p>
                    </div>
                    <a href="/admin/logout" class="btn btn-outline-danger btn-sm px-3">تسجيل الخروج</a>
                </div>
                
                <hr class="text-muted opacity-25 my-4">

                <div class="mb-4">
                    <h5 class="text-secondary">أهلاً بك، <span class="text-primary fw-bold"><?php echo htmlspecialchars($admin_name, ENT_QUOTES, 'UTF-8'); ?></span> 👋</h5>
                    <p class="text-muted mt-2">
                        أنت الآن مسجل الدخول بصفتك مسؤول النظام. يمكنك الانتقال مباشرة إلى واجهة الموقع لتفقد أقسام الموقع وتفعيل التعديل المباشر.
                    </p>
                </div>

                <div class="d-flex gap-3">
                    <a href="/home" class="btn btn-primary px-4 py-2 rounded-3">
                        <i class="bi bi-house-door-fill me-2"></i> الانتقال إلى الصفحة الرئيسية (الموقع)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
