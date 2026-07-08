<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Beethoven CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-width: 250px; --primary-color: #1a237e; }
        .sidebar { width: var(--sidebar-width); height: 100vh; background: #212529; position: fixed; color: #fff; }
        .main-content { margin-right: var(--sidebar-width); padding: 30px; }
        .nav-link { color: #adb5bd; border-radius: 8px; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: var(--primary-color); color: #fff; }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="sidebar p-3">
    <h4 class="text-center py-3">Beethoven CMS</h4>
    <nav class="nav flex-column">
        <a href="/dashboard" class="nav-link active"><i class="bi bi-speedometer2"></i> لوحة التحكم</a>
        <a href="/" class="nav-link"><i class="bi bi-eye"></i> معاينة الموقع</a>
        <a href="/logout" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> تسجيل الخروج</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>نظرة عامة</h2>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4">
                <div class="text-muted">عدد الصفحات المدارة</div>
                <h3 class="mt-2"><?php echo $pageCount ?? 0; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <div class="text-muted">حالة النظام</div>
                <h3 class="mt-2 text-success">يعمل بفعالية</h3>
            </div>
        </div>
    </div>
</div>

</body>
</html>
