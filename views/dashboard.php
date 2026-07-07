<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | Beethoven CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
    /* دمج هويتك البصرية */
    body { 
        background: var(--bg-secondary); 
        color: var(--dark); 
        font-family: var(--main-font); 
        min-height: 100vh;
    }
    
    .sidebar { 
        background: var(--white); 
        border-left: 1px solid var(--border); 
        min-height: 100vh;
    }

    .card-custom { 
        background: var(--white); 
        border: 1px solid var(--border); 
        border-radius: 12px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .nav-link { 
        color: var(--dark); 
        font-weight: var(--fw-medium);
        transition: 0.3s;
        padding: 12px 20px;
    }

    .nav-link:hover { 
        color: var(--main-color); 
        background: var(--hoverLight); 
    }

    .btn-custom {
        background-color: var(--main-color);
        color: var(--white);
        font-weight: var(--fw-bold);
    }
    
    .btn-custom:hover { background-color: var(--hoverDark); }

    h1, h2, h3, .sec-title { color: var(--black); font-weight: var(--fw-bold); }
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