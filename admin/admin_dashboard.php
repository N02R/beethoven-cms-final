<?php
/**
 * admin_dashboard.php - لوحة التحكم الرئيسية المؤمّنة
 */

// 1. السماح بالوصول وتفعيل الحماية المركزية
define('ALLOWED_ACCESS', true);

// 2. استدعاء ملف التهيئة المركزي (يقوم بتفعيل الجلسة الآمنة، الكوكيز، والاتصال بقاعدة البيانات عبر init.php)
require_once __DIR__ . '/api/init.php';

// 3. جدار حماية صارم: التحقق من أن المشرف مسجل دخوله وصلاحيته admin
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    header("Location: login.php?error=" . urlencode('يرجى تسجيل الدخول للوصول إلى لوحة التحكم.'));
    exit();
}

// 4. قراءة البيانات الحالية من ملف الـ JSON (مع تصحيح المسار النسبي بدقة بناءً على وجود الملف في مجلد admin)
$config_file_path = __DIR__ . '/announcement_config.json';
$config_data = [];
if (file_exists($config_file_path)) {
    $config_data = json_decode(file_get_contents($config_file_path), true);
}

// إعداد القيم الافتراضية والإحصائيات
$current_logo   = $config_data['site_logo_path'] ?? '../assets/img/logo.png';
$ad_status      = $config_data['status'] ?? 'Draft';
$ad_type        = $config_data['type'] ?? 'text';
$menu_count     = isset($config_data['menu_links']) ? count($config_data['menu_links']) : 6;
$consult_emails = $config_data['consultation_emails'] ?? [];
$consult_count  = count($consult_emails);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>لوحة التحكم الاحترافية || Beethoven City Services</title>
  <!-- تضمين Bootstrap 5.3 RTL مع أيقونات Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --bs-body-bg: #f8fafc;
      --sidebar-bg: #0f172a;
      --primary-color: #2563eb;
    }
    body { background-color: var(--bs-body-bg); font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif; color: #1e293b; text-align: right; }
    
    /* تصميم الـ Sidebar الأوروبي العصري متوافق مع RTL */
    .sidebar { min-height: 100vh; background-color: var(--sidebar-bg); color: #94a3b8; border-right: 1px solid rgba(255,255,255,0.05); }
    .sidebar .nav-link { color: #94a3b8; border-radius: 8px; margin-bottom: 6px; padding: 10px 15px; font-weight: 500; transition: all 0.25s ease; display: flex; align-items: center; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: rgba(37, 99, 235, 0.15); color: #ffffff; }
    .sidebar .nav-link.active { color: #60a5fa; }
    .sidebar .nav-link i { margin-left: 10px; font-size: 1.1rem; }
    
    /* البطاقات والإحصائيات الحديثة */
    .card-stat { border: none; border-radius: 12px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05); transition: all 0.3s ease; background: #ffffff; }
    .card-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    
    /* الجداول العصرية */
    .table-card { border: none; border-radius: 12px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05); background: #ffffff; }
    .table-custom th { font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: right; }
    .table-custom td { vertical-align: middle; color: #334155; text-align: right; }
    
    .top-navbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02); }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <!-- القائمة الجانبية (Sidebar) -->
    <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
      <div class="position-sticky text-center">
        <div class="bg-white p-3 rounded-3 mb-3 shadow-sm d-inline-block w-100">
          <img src="../<?php echo $current_logo; ?>" alt="شعار الموقع" class="img-fluid" style="max-height: 50px; object-fit: contain;">
        </div>
        <h6 class="text-secondary small fw-bold mb-4">نظام الإدارة الأوروبي</h6>
        <ul class="nav flex-column text-end p-0">
          <li class="nav-item">
            <a class="nav-link active" href="#"><i class="bi bi-grid-1x2-fill"></i> رئيسية اللوحة</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#consultations-section">
              <i class="bi bi-people-fill"></i> طلبات الاستشارة 
              <span class="badge bg-primary ms-auto"><?php echo $consult_count; ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../index.php" target="_blank"><i class="bi bi-globe"></i> استعراض الموقع</a>
          </li>
          <li class="nav-item my-2">
            <hr class="border-secondary opacity-25">
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> تسجيل الخروج</a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- المحتوى الرئيسي (Main Content) -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <!-- شريط العنوان العلوي -->
      <div class="top-navbar d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center p-3 mb-4 rounded-3">
        <div>
          <h1 class="h4 fw-bold mb-0">لوحة التحكم الإدارية</h1>
          <p class="text-muted small mb-0">مرحباً بك مجدداً، نظرة عامة على أداء ومراسلات المنصة.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
          <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
            <i class="bi bi-shield-check ms-1"></i> مسجل كمدير عام (Admin)
          </span>
        </div>
      </div>

      <!-- بطاقات الإحصائيات السريعة (Widgets) -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-muted small fw-semibold mb-1">طلبات الاستشارة</h6>
                <h3 class="fw-bold text-primary mb-0"><?php echo $consult_count; ?></h3>
              </div>
              <div class="fs-2 text-primary bg-primary bg-opacity-10 p-2 rounded-3">
                <i class="bi bi-chat-left-quote"></i>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-muted small fw-semibold mb-1">شريط الإعلانات</h6>
                <h5 class="fw-bold <?php echo $ad_status === 'Published' ? 'text-success' : 'text-secondary'; ?> mb-0 mt-2">
                  <?php echo $ad_status === 'Published' ? 'نشط الآن' : 'مسودة معطلة'; ?>
                </h5>
              </div>
              <div class="fs-2 text-success bg-success bg-opacity-10 p-2 rounded-3">
                <i class="bi bi-megaphone"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-muted small fw-semibold mb-1">نوع البانر</h6>
                <h5 class="fw-bold text-dark mb-0 mt-2">
                  <?php echo $ad_type === 'text' ? 'نص ديناميكي' : 'صورة بانر'; ?>
                </h5>
              </div>
              <div class="fs-2 text-warning bg-warning bg-opacity-10 p-2 rounded-3">
                <i class="bi bi-palette"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-muted small fw-semibold mb-1">روابط القائمة</h6>
                <h3 class="fw-bold text-dark mb-0"><?php echo $menu_count; ?> روابط</h3>
              </div>
              <div class="fs-2 text-info bg-info bg-opacity-10 p-2 rounded-3">
                <i class="bi bi-link-45deg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- قسم جدول طلبات الاستشارة الجديدة -->
      <div id="consultations-section" class="card table-card mb-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0"><i class="bi bi-envelope-check text-primary me-2"></i> سجل المتقدمين للاستشارة المجانية</h5>
          <span class="text-muted small">محفوظة آمنة وفق متطلبات (DSGVO)</span>
        </div>
        
        <?php if ($consult_count > 0): ?>
          <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>البريد الإلكتروني للعميل</th>
                  <th>تاريخ ووقت الطلب</th>
                  <th>الموافقة القانونية (DSGVO)</th>
                  <th>إجراءات سريعة</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($consult_emails as $index => $item): ?>
                  <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td class="fw-semibold text-primary" dir="ltr" style="text-align: right;"><?php echo htmlspecialchars($item['email']); ?></td>
                    <td class="text-muted small"><?php echo htmlspecialchars($item['date']); ?></td>
                    <td>
                      <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">
                        <i class="bi bi-check-circle-fill"></i> موافقة موثقة
                      </span>
                    </td>
                    <td>
                      <a href="mailto:<?php echo htmlspecialchars($item['email']); ?>" class="btn btn-sm btn-outline-primary py-1 px-2">
                        <i class="bi bi-reply-fill ms-1"></i> مراسلة
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
            <p class="mb-0">لا توجد طلبات استشارة واردة حتى الآن.</p>
          </div>
        <?php endif; ?>
      </div>

      <!-- قسم الإجراءات السريعة ومعلومات الأمان -->
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="card p-4 shadow-sm border-0 h-100 rounded-3">
            <h5 class="fw-bold mb-3"><i class="bi bi-sliders text-primary me-2"></i> التعديل الفوري للموقع</h5>
            <p class="text-muted small">يمكنك الانتقال للموقع كمسؤول، واستخدام أزرار التعديل العائمة والسريعة المرتبطة مباشرة بالنظام.</p>
            <div class="d-grid gap-2">
              <a href="../index.php" target="_blank" class="btn btn-outline-primary text-start p-2 fw-semibold d-flex align-items-center justify-content-between">
                <span><i class="bi bi-pencil-square me-2"></i> فتح الصفحة الرئيسية وتعديل المحتوى فوراً</span>
                <i class="bi bi-arrow-left"></i>
              </a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="card p-4 shadow-sm border-0 h-100 rounded-3">
            <h5 class="fw-bold mb-3"><i class="bi bi-shield-lock text-success me-2"></i> أمان وحماية النظام</h5>
            <div class="alert alert-success d-flex align-items-center small mb-2" role="alert">
              <div>
                كافة البيانات مشفرة وتعمل ببروتوكول الفحص والحماية الأوروبي ضد الثغرات.
              </div>
            </div>
            <p class="small text-muted mb-0">ملف الإعدادات النشط: <code class="bg-light p-1 rounded">announcement_config.json</code></p>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
