<?php
// 1. بدء الجلسة بأمان للتحقق من الهوية
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. جدار حماية صارم: منع أي شخص غير المسؤول من رؤية اللوحة
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    // توجيه المتسلل إلى صفحة تسجيل الدخول أو عرض رسالة حظر
    header("Location: ../login.php");
    exit();
}

// 3. قراءة البيانات الحالية من ملف الـ JSON لعرض الإحصائيات والحالة الحالية
$config_file_path = __DIR__ . '/../announcement_config.json';
$config_data = [];
if (file_exists($config_file_path)) {
    $config_data = json_decode(file_get_contents($config_file_path), true);
}

// إعداد القيم الافتراضية للعرض في لوحة التحكم
$current_logo = $config_data['site_logo_path'] ?? 'assets/img/logo.png';
$ad_status = $config_data['status'] ?? 'Draft';
$ad_type = $config_data['type'] ?? 'text';
$menu_count = isset($config_data['menu_links']) ? count($config_data['menu_links']) : 6;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>لوحة التحكم الإدارية || Beethoven City Services</title>
  <!-- تضمين البيئة التصميمية المفضلة لديكِ -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
  <style>
    body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .sidebar { min-height: 100vh; background-color: #212529; color: #fff; }
    .sidebar .nav-link { color: #f8f9fa; border-radius: 5px; margin-bottom: 5px; transition: all 0.2s; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #0d6efd; color: #fff; }
    .card-stat { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; }
    .card-stat:hover { transform: translateY(-5px); }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <!-- القائمة الجانبية للوحة التحكم (Sidebar) -->
    <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
      <div class="position-sticky text-center">
        <img src="../<?php echo $current_logo; ?>" alt="شعار الموقع" class="img-fluid mb-4 p-2 bg-white rounded" style="max-height: 60px;">
        <h6 class="text-muted mb-4">نظام إدارة BCS</h6>
        <ul class="nav flex-column text-end">
          <li class="nav-item">
            <a class="nav-link active" href="#">📊 رئيسية اللوحة</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../index.php" target="_blank">🌐 استعراض الموقع</a>
          </li>
          <li class="nav-item">
            <hr class="text-muted">
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">🚪 تسجيل الخروج</a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- المحتوى الرئيسي للوحة التحكم (Main Content) -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">لوحة التحكم الإدارية</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <span class="badge bg-primary p-2">مرحباً، المدير العام</span>
        </div>
      </div>

      <!-- قسم بطاقات الإحصائيات السريعة (Widgets) -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card card-stat bg-white p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-muted">شريط الإعلانات</h6>
                <h3 class="fw-bold <?php echo $ad_status === 'Published' ? 'text-success' : 'text-secondary'; ?>">
                  <?php echo $ad_status === 'Published' ? 'نشط الآن' : 'معطل (مسودة)'; ?>
                </h3>
              </div>
              <div class="fs-1">📢</div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-stat bg-white p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-muted">نوع الإعلان الحالي</h6>
                <h3 class="fw-bold text-primary">
                  <?php echo $ad_type === 'text' ? 'نص ديناميكي' : 'صورة بانر'; ?>
                </h3>
              </div>
              <div class="fs-1">🎨</div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-stat bg-white p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="text-muted">روابط القائمة المفعّلة</h6>
                <h3 class="fw-bold text-dark"><?php echo $menu_count; ?> روابط</h3>
              </div>
              <div class="fs-1">🔗</div>
            </div>
          </div>
        </div>
      </div>

      <!-- قسم التحكم السريع وإعدادات النظام -->
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="card p-4 shadow-sm border-0 h-100">
            <h5 class="card-title fw-bold mb-3">🛠️ إجراءات سريعة لتعديل الموقع</h5>
            <p class="text-muted small">يمكنك التوجه للموقع الرئيسي كمسؤول، واستخدام أزرار التعديل الفورية الذكية والمحمية التي قمنا بدمجها في التصميم الأصلي.</p>
            <div class="d-grid gap-2">
              <a href="../index.php" target="_blank" class="btn btn-outline-primary text-end p-2">
                ✏️ فتح الصفحة الرئيسية وتعديل الشعار أو الإعلان فوراً
              </a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="card p-4 shadow-sm border-0 h-100">
            <h5 class="card-title fw-bold mb-3">🔒 أمان واستقرار النظام</h5>
            <div class="alert alert-success d-flex align-items-center small" role="alert">
              <div>
                كافة عمليات الرفع والمعالجة مشفرة ومحمية ببروتوكول فحص الـ Mime-Type الألماني ضد الثغرات.
              </div>
            </div>
            <p class="small text-muted">ملف الإعدادات النشط حالياً: <code class="bg-light p-1 rounded">announcement_config.json</code></p>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
