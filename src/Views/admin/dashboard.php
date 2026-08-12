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
            background-color: #0b1c3c; /* خلفية الصفحة الخارجية داكنة */
            color: #333333;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .dashboard-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        /* تنسيق حاوية الصورة */
        .img-hero {
            width: 100%;
            height: 100%;
            min-height: 600px;
            background-image: url('/public/assets/img/dashboard.jpeg');
            background-size: cover;
            background-position: center;
            border-radius: 20px;
            position: relative;
        }

        .img-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(11, 28, 60, 0.1);
            border-radius: 20px;
        }

        /* حاوية المحتوى بخلفية بيضاء (شكل الكتاب) */
        .dashboard-card-box {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            color: #1e293b;
        }

        /* الشعار والترويسة */
        .brand-logo {
            font-size: 22px;
            font-weight: 700;
            color: #0b1c3c;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .welcome-title {
            font-size: 2.1rem;
            font-weight: 700;
            color: #0b1c3c;
            margin-bottom: 15px;
        }

        .welcome-desc {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .welcome-desc span {
            color: #2563eb;
            font-weight: 600;
        }

        /* الأزرار التفاعلية على الخلفية البيضاء */
        .custom-card-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 15px 20px;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .custom-card-btn.primary {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #ffffff;
        }

        .custom-card-btn.primary .btn-text p {
            color: #93c5fd;
        }

        .custom-card-btn:hover {
            transform: translateY(-2px);
            background: #f1f5f9;
            color: #1e293b;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .custom-card-btn.primary:hover {
            background: #1e40af;
            color: #ffffff;
        }

        .btn-content {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .btn-icon {
            width: 42px;
            height: 42px;
            background: #e2e8f0;
            color: #1e293b;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .custom-card-btn.primary .btn-icon {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .btn-text h5 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 600;
        }

        .btn-text p {
            margin: 0;
            font-size: 0.8rem;
            color: #64748b;
        }

        /* بطاقة المعلومات السفلية */
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 15px 20px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-item h6 {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 4px;
        }

        .info-item span {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0b1c3c;
        }

        .info-item .price {
            color: #2563eb;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

  <!-- dashboard section start -->
  <section class="dashboard-section py-5">
    <div class="container-fluid px-lg-5">
      <div class="row align-items-stretch g-4">
        
        <!-- عمود الصورة (يمين الشاشة) -->
        <div class="col-lg-6">
          <div class="img-hero"></div>
        </div>

        <!-- عمود المحتوى بخلفية بيضاء (يسار الشاشة) -->
        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <div class="dashboard-card-box">
            
            <!-- الشعار -->
            <a href="#" class="brand-logo">
                <i class="fa-solid fa-graduation-cap fa-2x text-primary"></i>
                <div>
                    <div>BEETHOVEN</div>
                    <small style="font-size: 10px; letter-spacing: 2px; color: #64748b;">CITY SERVICES</small>
                </div>
            </a>

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
                    <i class="fa-solid fa-chevron-left text-muted"></i>
                </a>

                <!-- زر تسجيل الخروج -->
                <a href="logout.php" class="custom-card-btn" style="border-color: #fee2e2;">
                    <div class="btn-content">
                        <div class="btn-icon" style="color: #dc2626; background: #fee2e2;">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </div>
                        <div class="btn-text">
                            <h5 style="color: #dc2626;">تسجيل الخروج</h5>
                            <p>الخروج من لوحة التحكم</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-left text-danger"></i>
                </a>
            </div>

            <!-- بطاقة المعلومات السفلية -->
            <div class="info-card">
                <div class="info-item">
                    <h6>الباقة الحالية</h6>
                    <span><i class="fa-solid fa-landmark text-primary ms-1"></i> الجامعات الألمانية</span>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">
                        <i class="fa-regular fa-calendar-days"></i> صالحة حتى 24 مايو 2026
                    </div>
                </div>
                <div class="info-item text-start border-start border-2 ps-4">
                    <h6>القيمة الإجمالية</h6>
                    <span class="price">€ 9,000</span>
                </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- dashboard section end -->

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
