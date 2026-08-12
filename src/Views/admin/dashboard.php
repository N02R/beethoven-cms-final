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
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #020617 100%);
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif; 
            color: #ffffff; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            text-align: right;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 50px 40px;
            max-width: 550px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .logo-box {
            background: #ffffff;
            padding: 15px 25px;
            border-radius: 16px;
            display: inline-block;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .logo-box img {
            max-height: 45px;
            object-fit: contain;
        }

        h1 {
            font-weight: 800;
            font-size: 1.8rem;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        p.description {
            color: #94a3b8;
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
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
            text-decoration: none;
            margin-bottom: 12px;
        }

        .btn-custom-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
            color: #fff;
        }

        .btn-custom-outline {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            color: #cbd5e1;
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
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="welcome-card mx-auto">
        
        <div class="logo-box">
            <?php 
                // جلب الشعار مباشرة من جدول الإعدادات في قاعدة البيانات لضمان عرضه دائماً
                $logo_path = 'assets/img/logo.png'; // القيمة الافتراضية
                if (isset($pdo)) {
                    $stmtLogo = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'logo'");
                    $stmtLogo->execute();
                    $dbLogo = $stmtLogo->fetchColumn();
                    if (!empty($dbLogo)) {
                        $logo_path = $dbLogo;
                    }
                } elseif (isset($data['logo'])) {
                    $logo_path = $data['logo'];
                }
            ?>
            <img src="<?php echo htmlspecialchars(get_image_url($logo_path), ENT_QUOTES, 'UTF-8'); ?>" alt="شعار الموقع">
        </div>


        <h1>أهلاً بك، <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'المشرف', ENT_QUOTES, 'UTF-8'); ?> ✨</h1>
        <p class="description">
            أنت الآن مسجل الدخول بصفحة الإدارة. يمكنك الانتقال الفوري للموقع واستعراضه لتعديل أي عنصر تريده بضغطة زر واحدة.
        </p>

        <div class="d-grid gap-2">
            <!-- زر استعراض وتعديل الموقع مع التوجيه الصحيح إلى /home -->
            <a href="index.php?url=home" class="btn-custom-primary">
                <i class="bi bi-pencil-square fs-5"></i>
                استعراض الموقع والتعديل عليه فوراً
            </a>

            <!-- زر تسجيل الخروج -->
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
