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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
        }

        /* حاوية الخلفية الكاملة */
        .dashboard-wrapper {
            position: relative;
            width: 100vw;
            height: 100vh;
            background: url('assets/img/dashboard.png') no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        /* طبقة عتمة خفيفة جداً لزيادة وضوح الحواف إذا لزم الأمر */
        .dashboard-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.15);
            z-index: 1;
        }

        /* القوحة أو اللوحة اليسرى التي تحمل محتويات الداشبورد */
        .sidebar-content {
            position: relative;
            z-index: 2;
            width: 480px;
            height: 100%;
            background: linear-gradient(180deg, #071630 0%, #0a224a 100%);
            padding: 40px 35px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 15px 0 35px rgba(0, 0, 0, 0.3);
            overflow-y: auto;
        }

        /* تخصيص السكرول بار للقائمة اليسرى */
        .sidebar-content::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        /* قسم الشعار العلوي */
        .brand-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .brand-logo {
            max-height: 55px;
            object-fit: contain;
        }

        .brand-title {
            color: #ffffff;
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: 1px;
            margin: 0;
        }

        .brand-subtitle {
            color: #93c5fd;
            font-size: 0.8rem;
            margin: 0;
        }

        /* الترحيب */
        .welcome-section {
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .welcome-section h1 {
            color: #ffffff;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .welcome-section p {
            color: #94a3b8;
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }

        .welcome-section p span {
            color: #60a5fa;
            font-weight: 600;
        }

        /* الأزرار التفاعلية */
        .menu-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .action-card.primary {
            background: #2563eb;
            border-color: #3b82f6;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .action-card:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .action-card.primary:hover {
            background: #1d4ed8;
        }

        .action-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .action-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.2rem;
        }

        .action-card.primary .action-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .action-text h4 {
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            margin: 0 0 3px 0;
        }

        .action-text span {
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .action-card.primary .action-text span {
            color: #bfdbfe;
        }

        .arrow-icon {
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .action-card.primary .arrow-icon {
            color: #ffffff;
        }

        /* بوكس الباقة والقيمة السفلي */
        .footer-badge {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .badge-info h5 {
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 700;
            margin: 0 0 4px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-info h5 i {
            color: #60a5fa;
        }

        .badge-info span {
            color: #94a3b8;
            font-size: 0.75rem;
        }

        .badge-price {
            text-align: left;
        }

        .badge-price span.label {
            display: block;
            color: #94a3b8;
            font-size: 0.7rem;
            margin-bottom: 2px;
        }

        .badge-price span.amount {
            color: #38bdf8;
            font-weight: 800;
            font-size: 1.25rem;
        }

        @media (max-width: 992px) {
            .sidebar-content {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-wrapper">
    <div class="dashboard-overlay"></div>

    <div class="sidebar-content">
        
        <!-- الهيدر والشعار -->
        <div class="brand-section">
            <?php 
                $site_logo = $data['logo'] ?? 'assets/img/logo.png';
            ?>
            <img src="<?php echo htmlspecialchars(get_image_url($site_logo), ENT_QUOTES, 'UTF-8'); ?>" alt="شعار الموقع" class="brand-logo">
            <h2 class="brand-title">BEETHOVEN</h2>
            <span class="brand-subtitle">بيتهوفن سيتي للخدمات الطلابية</span>
        </div>

        <!-- الترحيب بالمشرف -->
        <div class="welcome-section">
            <h1>مرحباً بك 👋<br><span style="color: #60a5fa;"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'المشرف', ENT_QUOTES, 'UTF-8'); ?></span></h1>
            <p>
                مرحباً بك في لوحة التحكم الخاصة بموقع <span>بيتهوفن سيتي للخدمات الطلابية</span>. من هنا يمكنك إدارة محتوى الموقع بكل سهولة.
            </p>
        </div>

        <!-- الأزرار والقوائم -->
        <div class="menu-actions">
            <!-- استعراض الموقع -->
            <a href="index.php?url=home" class="action-card primary">
                <div class="action-content">
                    <div class="action-icon">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div class="action-text">
                        <h4>استعراض الموقع</h4>
                        <span>عرض الموقع كما يراه الزوار</span>
                    </div>
                </div>
                <i class="bi bi-chevron-left arrow-icon"></i>
            </a>

            <!-- تعديل الموقع -->
            <a href="index.php?url=admin/pages" class="action-card">
                <div class="action-content">
                    <div class="action-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div class="action-text">
                        <h4>تعديل الموقع</h4>
                        <span>إدارة وتعديل محتوى الموقع</span>
                    </div>
                </div>
                <i class="bi bi-chevron-left arrow-icon"></i>
            </a>

            <!-- تسجيل الخروج -->
            <a href="index.php?url=admin/logout" class="action-card">
                <div class="action-content">
                    <div class="action-icon" style="color: #f87171;">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <div class="action-text">
                        <h4>تسجيل الخروج</h4>
                        <span>الخروج من لوحة التحكم</span>
                    </div>
                </div>
                <i class="bi bi-chevron-left arrow-icon"></i>
            </a>
        </div>

        <!-- بطاقة الباقة والقيمة السفليّة -->
        <div class="footer-badge">
            <div class="badge-info">
                <h5><i class="bi bi-bank"></i> الباقة الحالية</h5>
                <span>الجامعات الألمانية 🇩🇪</span>
                <div style="color: #94a3b8; font-size: 0.7rem; margin-top: 2px;">
                    <i class="bi bi-calendar-event"></i> صالحة حتى 24 مايو 2026
                </div>
            </div>
            <div class="badge-price">
                <span class="label">القيمة الإجمالية</span>
                <span class="amount">€ 9,000</span>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
