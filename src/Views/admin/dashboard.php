<?php
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - Beethoven City Services</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif; 
            color: #1e293b; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            text-align: right;
        }

        .welcome-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 24px;
            padding: 50px 40px;
            max-width: 550px;
            width: 100%;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.05);
            text-align: center;
        }

        .logo-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px 25px;
            border-radius: 16px;
            display: inline-block;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .logo-box img {
            max-height: 45px;
            object-fit: contain;
        }

        h1 {
            font-weight: 800;
            font-size: 1.8rem;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        p.description {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .btn-custom-primary {
            background: #2563eb;
            border: none;
            color: #fff;
            font-weight: 700;
            padding: 14px 24px;
            border-radius: 12px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            font-size: 1rem;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
            text-decoration: none;
            margin-bottom: 12px;
        }

        .btn-custom-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
            color: #fff;
        }

        .btn-custom-outline {
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            color: #475569;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            font-size: 0.95rem;
            text-decoration: none;
        }

        .btn-custom-outline:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            color: #0f172a;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="welcome-card mx-auto">
        
        <div class="logo-box">
            <?php 
                $site_logo = $data['logo'] ?? 'assets/img/logo.png';
            ?>
            <img src="<?php echo htmlspecialchars(get_image_url($site_logo), ENT_QUOTES, 'UTF-8'); ?>" alt="شعار الموقع">
        </div>

        <h1>أهلاً بك، <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'المشرف', ENT_QUOTES, 'UTF-8'); ?> ✨</h1>
        <p class="description">
            أنت الآن مسجل الدخول بصفحة الإدارة. يمكنك الانتقال الفوري للموقع واستعراضه لتعديل أي عنصر تريده بضغطة زر واحدة.
        </p>

        <div class="d-grid gap-2">
            <a href="index.php?url=home" class="btn-custom-primary">
                <i class="bi bi-pencil-square fs-5"></i>
                استعراض الموقع والتعديل عليه فوراً
            </a>

            <a href="index.php?url=admin/logout" class="btn-custom-outline">
                <i class="bi bi-box-arrow-right fs-5"></i>
                تسجيل الخروج الآمن
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
