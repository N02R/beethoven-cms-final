<?php
declare(strict_types=1);

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

// 1. إدارة اللغة وحفظها من الجلسة أو الرابط
$supportedLangs = ['de', 'en', 'ar'];

if (isset($_GET['lang']) && in_array($_GET['lang'], $supportedLangs, true)) {
    $_SESSION['admin_login_lang'] = $_GET['lang'];
    $_SESSION['site_lang'] = $_GET['lang'];
}

$currentLang = $_SESSION['admin_login_lang'] ?? ($_SESSION['site_lang'] ?? 'de');
if (!in_array($currentLang, $supportedLangs, true)) {
    $currentLang = 'de';
}

$isRtl = ($currentLang === 'ar');
$dir   = $isRtl ? 'rtl' : 'ltr';

$langBadges = [
    'de' => 'DE',
    'en' => 'EN',
    'ar' => 'ع'
];

// 2. مصفوفة النصوص المترجمة للوحة التحكم
$translations = [
    'de' => [
        'page_title'       => 'Dashboard - Beethoven City Services',
        'welcome'          => 'Willkommen',
        'role_super_admin' => 'Hauptadministrator (Super Admin)',
        'role_admin'       => 'Systemadministrator (Admin)',
        'desc_part1'       => 'Willkommen im Kontrollzentrum von ',
        'brand_name'       => 'Beethoven City Services',
        'desc_part2'       => '. Hier können Sie Website-Inhalte verwalten und Beratungsanfragen mühelos bearbeiten.',
        'preview_title'    => 'Website ansehen',
        'preview_desc'     => 'Zur Hauptseite wechseln und Direktbearbeitungs-Stifte aktivieren',
        'logout_title'     => 'Abmelden',
        'logout_desc'      => 'Sicher aus dem Kontrollzentrum abmelden',
        'sys_status_label' => 'Systemstatus',
        'sys_active'       => 'Beethoven CMS - Aktiv',
        'connected_as'     => 'Angemeldet als:',
        'consult_label'    => 'Beratungsanfragen',
        'lang_label'       => 'Sprache wählen'
    ],
    'en' => [
        'page_title'       => 'Dashboard - Beethoven City Services',
        'welcome'          => 'Welcome',
        'role_super_admin' => 'Super Administrator (Super Admin)',
        'role_admin'       => 'System Administrator (Admin)',
        'desc_part1'       => 'Welcome to the control panel of ',
        'brand_name'       => 'Beethoven City Services',
        'desc_part2'       => '. From here, you can manage website content and handle consultation requests effortlessly.',
        'preview_title'    => 'Preview Website',
        'preview_desc'     => 'Go to the main site and activate live editing tools',
        'logout_title'     => 'Logout',
        'logout_desc'      => 'Securely sign out from the control panel',
        'sys_status_label' => 'System Status',
        'sys_active'       => 'Beethoven CMS - Active',
        'connected_as'     => 'Connected as:',
        'consult_label'    => 'Consultations',
        'lang_label'       => 'Select Language'
    ],
    'ar' => [
        'page_title'       => 'لوحة التحكم - بيتهوفن سيتي للخدمات الطلابية',
        'welcome'          => 'مرحباً بك',
        'role_super_admin' => 'مشرف عام (Super Admin)',
        'role_admin'       => 'مسؤول النظام (Admin)',
        'desc_part1'       => 'مرحباً بك في لوحة التحكم الخاصة بموقع ',
        'brand_name'       => 'بيتهوفن سيتي للخدمات الطلابية',
        'desc_part2'       => '، من هنا يمكنك إدارة محتوى الموقع وتلقي طلبات الاستشارة بكل سهولة.',
        'preview_title'    => 'استعراض الموقع',
        'preview_desc'     => 'الانتقال للموقع الرئيسي وتفعيل أقلام التعديل المباشر',
        'logout_title'     => 'تسجيل الخروج',
        'logout_desc'      => 'الخروج الآمن من لوحة التحكم',
        'sys_status_label' => 'حالة النظام',
        'sys_active'       => 'بيتهوفن CMS - نشط',
        'connected_as'     => 'متصل بصلاحية:',
        'consult_label'    => 'طلبات الاستشارة',
        'lang_label'       => 'اختر اللغة'
    ]
];

$t = $translations[$currentLang];

$site_logo  = $data['site_logo'] ?? $data['current_logo'] ?? 'assets/img/logo.png';
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$userRole   = $_SESSION['role'] ?? 'admin';
$role_badge = ($userRole === 'super_admin') ? $t['role_super_admin'] : $t['role_admin'];
$csrf_token = $data['csrf_token'] ?? ($_SESSION['csrf_token'] ?? '');
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($currentLang, ENT_QUOTES, 'UTF-8'); ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="<?php echo htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($t['page_title'], ENT_QUOTES, 'UTF-8'); ?></title>
    
    <?php if ($isRtl): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <?php else: ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php endif; ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: <?php echo $isRtl ? "'Cairo', sans-serif" : "'Plus Jakarta Sans', system-ui, sans-serif"; ?>;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #06b6d4 100%);
            color: #333333;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
        }

        /* مربع اللغة الزجاجي العائم */
        .floating-lang-wrapper {
            position: fixed;
            top: 24px;
            <?php echo $isRtl ? 'left: 24px;' : 'right: 24px;'; ?>
            z-index: 1050;
        }

        .lang-glass-box {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.25s ease;
            user-select: none;
        }

        .floating-lang-wrapper:hover .lang-glass-box,
        .lang-glass-box:focus {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.22);
        }

        .lang-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            <?php echo $isRtl ? 'left: 0;' : 'right: 0;'; ?>
            min-width: 155px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 6px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .floating-lang-wrapper::after {
            content: '';
            position: absolute;
            top: 46px;
            left: 0;
            right: 0;
            height: 12px;
        }

        .floating-lang-wrapper:hover .lang-dropdown-menu,
        .floating-lang-wrapper:focus-within .lang-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .lang-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 12px;
            border-radius: 10px;
            color: #1e293b;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .lang-option:hover {
            background: #eff6ff;
            color: #2563eb;
        }

        .lang-option.active {
            background: #2563eb;
            color: #ffffff;
        }

        .lang-option .code-tag {
            font-size: 0.72rem;
            font-weight: 700;
            opacity: 0.75;
            padding: 2px 6px;
            border-radius: 6px;
            background: rgba(0, 0, 0, 0.06);
        }

        .lang-option.active .code-tag {
            background: rgba(255, 255, 255, 0.2);
            opacity: 1;
        }

        .dashboard-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .main-card-wrapper {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .img-hero {
            width: 100%;
            height: 100%;
            min-height: 600px;
            background-image: url('/assets/img/dashboard.jpeg');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .img-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(30, 58, 138, 0.4), rgba(6, 182, 212, 0.1));
        }

        .dashboard-card-box {
            background-color: rgba(255, 255, 255, 0.98);
            padding: 50px 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-logo {
            text-decoration: none;
            display: inline-block;
            margin-bottom: 25px;
        }

        .brand-logo-img {
            max-height: 55px;
            object-fit: contain;
        }

        .welcome-title {
            font-size: 2.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .role-badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 20px;
            width: fit-content;
        }

        .welcome-desc {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .welcome-desc span {
            color: #3b82f6;
            font-weight: 600;
        }

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
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }

        .custom-card-btn.primary .btn-text p {
            color: #bfdbfe;
        }

        .custom-card-btn:hover {
            transform: translateY(-2px);
            background: #f1f5f9;
            color: #1e293b;
        }

        .custom-card-btn.primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
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
            color: #475569;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
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

        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 15px 20px;
            margin-top: auto;
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
            color: #0f172a;
        }

        .info-item .price {
            color: #2563eb;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

  <!-- مربع اللغة الزجاجي العائم -->
  <div class="floating-lang-wrapper">
      <div class="lang-glass-box" tabindex="0" role="button" aria-label="<?php echo htmlspecialchars($t['lang_label'], ENT_QUOTES, 'UTF-8'); ?>">
          <?php echo htmlspecialchars($langBadges[$currentLang], ENT_QUOTES, 'UTF-8'); ?>
      </div>
      <div class="lang-dropdown-menu">
          <a href="/admin/dashboard?lang=de" class="lang-option <?php echo $currentLang === 'de' ? 'active' : ''; ?>">
              <span>Deutsch</span>
              <span class="code-tag">DE</span>
          </a>
          <a href="/admin/dashboard?lang=en" class="lang-option <?php echo $currentLang === 'en' ? 'active' : ''; ?>">
              <span>English</span>
              <span class="code-tag">EN</span>
          </a>
          <a href="/admin/dashboard?lang=ar" class="lang-option <?php echo $currentLang === 'ar' ? 'active' : ''; ?>">
              <span>العربية</span>
              <span class="code-tag">AR</span>
          </a>
      </div>
  </div>

  <!-- dashboard section start -->
  <section class="dashboard-section py-5">
    <div class="container-fluid px-lg-5">
      
      <div class="main-card-wrapper">
          <div class="row align-items-stretch g-0">
            
            <!-- عمود الصورة -->
            <div class="col-lg-6">
              <div class="img-hero"></div>
            </div>

            <!-- عمود المحتوى -->
            <div class="col-lg-6">
              <div class="dashboard-card-box">
                
                <!-- الشعار -->
                <a href="/<?php echo htmlspecialchars($currentLang, ENT_QUOTES, 'UTF-8'); ?>/home" class="brand-logo">
                    <?php if (!empty($site_logo)): ?>
                        <img src="<?php echo htmlspecialchars(get_image_url($site_logo), ENT_QUOTES, 'UTF-8'); ?>" alt="Logo" class="brand-logo-img">
                    <?php else: ?>
                        <i class="fa-solid fa-graduation-cap fa-2x text-primary"></i>
                    <?php endif; ?>
                </a>

                <h1 class="welcome-title"><?php echo htmlspecialchars($t['welcome'], ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars((string)$admin_name, ENT_QUOTES, 'UTF-8'); ?> 👋</h1>
                <div class="role-badge">
                    <i class="fa-solid fa-shield-halved <?php echo $isRtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)$role_badge, ENT_QUOTES, 'UTF-8'); ?>
                </div>

                <p class="welcome-desc">
                  <?php echo htmlspecialchars($t['desc_part1'], ENT_QUOTES, 'UTF-8'); ?><span><?php echo htmlspecialchars($t['brand_name'], ENT_QUOTES, 'UTF-8'); ?></span><?php echo htmlspecialchars($t['desc_part2'], ENT_QUOTES, 'UTF-8'); ?>
                </p>

                <!-- الأزرار -->
                <div class="d-flex flex-column mb-4">
                    <!-- استعراض الموقع وتفعيل التعديل المباشر بنفس اللغة -->
                    <a href="/<?php echo htmlspecialchars($currentLang, ENT_QUOTES, 'UTF-8'); ?>/home" class="custom-card-btn primary">
                        <div class="btn-content">
                            <div class="btn-icon">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <div class="btn-text">
                                <h5><?php echo htmlspecialchars($t['preview_title'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                <p><?php echo htmlspecialchars($t['preview_desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </div>
                        <i class="fa-solid <?php echo $isRtl ? 'fa-chevron-left' : 'fa-chevron-right'; ?>"></i>
                    </a>

                    <!-- تسجيل الخروج -->
                    <a href="/admin/logout" class="custom-card-btn" style="border-color: #fee2e2;">
                        <div class="btn-content">
                            <div class="btn-icon" style="color: #dc2626; background: #fee2e2;">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </div>
                            <div class="btn-text">
                                <h5 style="color: #dc2626;"><?php echo htmlspecialchars($t['logout_title'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                <p><?php echo htmlspecialchars($t['logout_desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </div>
                        <i class="fa-solid <?php echo $isRtl ? 'fa-chevron-left' : 'fa-chevron-right'; ?> text-danger"></i>
                    </a>
                </div>

                <!-- بطاقة المعلومات السفلية -->
                <div class="info-card">
                    <div class="info-item">
                        <h6><?php echo htmlspecialchars($t['sys_status_label'], ENT_QUOTES, 'UTF-8'); ?></h6>
                        <span><i class="fa-solid fa-circle-check text-success <?php echo $isRtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($t['sys_active'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">
                            <i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($t['connected_as'], ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars((string)$userRole, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                    <div class="info-item <?php echo $isRtl ? 'text-start border-start ps-4' : 'text-end border-end pe-4'; ?> border-2">
                        <h6><?php echo htmlspecialchars($t['consult_label'], ENT_QUOTES, 'UTF-8'); ?></h6>
                        <span class="price"><?php echo (int)($data['consult_count'] ?? 0); ?></span>
                    </div>
                </div>

              </div>
            </div>

          </div>
      </div>
      
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
