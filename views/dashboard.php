<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | Beethoven CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .sidebar { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); min-height: 100vh; border-left: 1px solid rgba(255,255,255,0.1); }
        .card-custom { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 15px; }
        .nav-link { color: #94a3b8; transition: 0.3s; }
        .nav-link:hover { color: #fff; background: rgba(99, 102, 241, 0.2); border-radius: 8px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar p-3">
            <h4 class="text-center py-3">Beethoven</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="#">الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link" href="#">الصفحات</a></li>
                <li class="nav-item"><a class="nav-link" href="#">الإعدادات</a></li>
                <li class="nav-item mt-5"><a class="nav-link text-danger" href="/logout">خروج</a></li>
            </ul>
        </nav>

        <main class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>مرحباً بك في لوحة الإدارة</h2>
                <div class="card-custom p-2">Admin Profile</div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card-custom p-4">
                        <h5>إجمالي الصفحات</h5>
                        <h2 class="display-4">12</h2>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>