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
    <title>لوحة التحكم - بيتهوفن سيتي للخدمات الطلابية</title>
    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css5/bootstrap.rtl.min.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts (Cairo) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #0b1c3c;
            color: #ffffff;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        .dashboard-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            background: linear-gradient(135deg, #0b1c3c 0%, #050d1a 100%);
        }

        /* الشعار والترويسة */
        .brand-section {
            padding: 40px;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand-logo img {
            max-height: 50px;
        }

        /* محتوى لوحة التحكم */
        .content-section {
            padding: 0 40px 40px 40px;
            max-width: 600px;
            z-index: 2;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .welcome-desc {
            color: #b0c4de;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .welcome-desc span {
            color: #3b82f6;
            font-weight: 600;
        }

        /* الأزرار التفاعلية */
        .custom-card-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 18px 24px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .custom-card-btn.primary {
            background: #1d4ed8;
            border-color: #3b82f6;
        }

        .custom-card-btn:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .custom-card-btn.primary:hover {
            background: #2563eb;
        }

        .btn-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .custom-card-btn.primary .btn-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .btn-text h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .btn-text p {
            margin: 0;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        /* بطاقة المعلومات السفلية */
        .info-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 20px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 450px;
        }

        .info-item h6 {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 5px;
        }

        .info-item span {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .info-item .price {
            color: #60a5fa;
            font-size: 1.3rem;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <!-- الترويسة والشعار -->
        <div class="brand-section">
            <a href="#" class="brand-logo">
                <i class="fa-solid fa-graduation-cap fa-2x text-primary"></i>
                <div>
                    <div>BEETHOVEN</div>
                    <small style="font-size: 11px; letter-spacing: 2px; color: #94a3b8;">CITY SERVICES</small>
                </div>
            </a>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="content-section">
            <h1 class="welcome-title">مرحباً بك المشرف 👋</h1>
            <p class="welcome-desc">
                مرحباً بك في لوحة التحكم الخاصة بموقع <span>بيتهوفن سيتي للخدمات الطلابية</span>، من هنا يمكنك إدارة محتوى الموقع بكل سهولة.
            </p>

            <!-- الأزرار -->
            <div class="d-flex flex-column">
                <!-- زر استعراض الموقع -->
                <a href="index.php" class="custom-card-btn primary">
                    <div class="btn-content">
                        <div class="btn-icon">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div class="btn-text">
                            <h5>استعراض الموقع</h5>
                            <p>عرض الموقع كما يراه الزوار</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                <!-- زر تعديل الموقع -->
                <a href="edit-content.php" class="custom-card-btn">
                    <div class="btn-content">
                        <div class="btn-icon">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div class="btn-text">
                            <h5>تعديل الموقع</h5>
                            <p>إدارة وتعديل محتوى الموقع</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                <!-- زر تسجيل الخروج -->
                <a href="logout.php" class="custom-card-btn" style="border-color: rgba(239, 68, 68, 0.2);">
                    <div class="btn-content">
                        <div class="btn-icon" style="color: #f87171; background: rgba(239, 68, 68, 0.1);">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </div>
                        <div class="btn-text">
                            <h5 style="color: #f87171;">تسجيل الخروج</h5>
                            <p>الخروج من لوحة التحكم</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-left" style="color: #f87171;"></i>
                </a>
            </div>

            <!-- معلومات إضافية أسفل القائمة -->
            <div class="info-card">
                <div class="info-item">
                    <h6>الباقة الحالية</h6>
                    <span><i class="fa-solid fa-landmark text-primary ms-1"></i> الجامعات الألمانية</span>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 4px;">
                        <i class="fa-regular fa-calendar-days"></i> صالحة حتى 24 مايو 2026
                    </div>
                </div>
                <div class="info-item text-start border-start border-secondary ps-4">
                    <h6>القيمة الإجمالية</h6>
                    <span class="price">€ 9,000</span>
                </div>
            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
