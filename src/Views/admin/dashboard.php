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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
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
            height: 100vh;
        }
        
        .dashboard-wrapper {
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* القسم الأيمن (الخلفية الجامعية) */
        .university-section {
            flex: 0 0 55%; /* يأخذ 55% من العرض */
            background-image: url('assets/img/413d735a-84d2-4a08-a4c7-325ad579df85.jpeg'); /* رابط مباشر لصورة الجامعة من الصورة الأصلية */
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* تأثير التعتيم الأزرق الشفاف فوق الصورة */
        .university-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(11, 28, 60, 0.2), #0b1c3c 95%);
        }

        /* القسم الأيسر (لوحة التحكم) */
        .control-section {
            flex: 0 0 45%; /* يأخذ 45% من العرض */
            background-color: #0b1c3c;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px;
            z-index: 1;
        }

        /* الشعار والترويسة */
        .brand-logo {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 60px;
        }

        .brand-logo img {
            max-height: 50px;
        }

        /* محتوى لوحة التحكم */
        .content-area {
            max-width: 500px;
            margin-top: auto;
            margin-bottom: auto;
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
            padding: 15px;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-item h6 {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 5px;
        }

        .info-item span {
            font-size: 1.0rem;
            font-weight: 700;
        }

        .info-item .price {
            color: #60a5fa;
            font-size: 1.2rem;
        }

        /* تجاوب التصميم مع الشاشات الصغيرة */
        @media (max-width: 992px) {
            .university-section {
                display: none; /* إخفاء الصورة في الشاشات الصغيرة */
            }
            .control-section {
                flex: 0 0 100%; /* أخذ كامل العرض */
                padding: 20px;
            }
            .welcome-title { font-size: 2rem; }
            .brand-logo { margin-bottom: 30px; }
        }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
        
        <!-- القسم الأيمن: صورة الجامعة -->
        <div class="university-section">
            <!-- يمكن إضافة محتوى هنا إذا لزم الأمر، مثل شعار الجامعة -->
        </div>

        <!-- القسم الأيسر: لوحة التحكم -->
        <div class="control-section">
            <!-- الترويسة والشعار -->
            <div>
                <a href="#" class="brand-logo">
                    <i class="fa-solid fa-graduation-cap fa-2x text-primary"></i>
                    <div>
                        <div>BEETHOVEN</div>
                        <small style="font-size: 11px; letter-spacing: 2px; color: #94a3b8;">CITY SERVICES</small>
                    </div>
                </a>
            </div>

            <!-- المحتوى الرئيسي -->
            <div class="content-area">
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

                <!-- بطاقة المعلومات السفلية -->
                <div class="info-card">
                    <div class="info-item">
                        <h6>الباقة الحالية</h6>
                        <span><i class="fa-solid fa-landmark text-primary ms-1"></i> الجامعات الألمانية</span>
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 4px;">
                            <i class="fa-regular fa-calendar-days"></i> صالحة حتى 24 مايو 2026
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- مسافة فارغة أسفل المحتوى -->
            <div></div> 

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
