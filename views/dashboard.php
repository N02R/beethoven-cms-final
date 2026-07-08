<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم | Beethoven CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { height: 100vh; background: #212529; color: white; padding-top: 20px; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover { color: white; background: #343a40; }
        .main-content { padding: 20px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <h4 class="text-center">Beethoven CMS</h4>
                <hr>
                <a href="/dashboard">الرئيسية</a>
                <a href="/">معاينة الموقع</a>
                <a href="/logout" class="text-danger">تسجيل الخروج</a>
            </div>
            
            <div class="col-md-10 main-content">
                <h2>أهلاً بكِ في لوحة التحكم</h2>
                <div class="card p-4 mt-4">
                    <h5>إحصائيات سريعة</h5>
                    <p>هنا ستظهر إحصائيات الموقع وتنبيهات التعديلات الأخيرة.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
