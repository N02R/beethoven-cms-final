<?php 
ob_start();

// تطبيق إعدادات أمان الجلسات والكوكيز الحديثة
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
    session_start();
}

if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي
$path_prefix = '../'; 

// 2. تحميل البيانات الأساسية من ملف الـ JSON
$config_file = __DIR__ . '/../announcement_config.json';
$global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];

$bachelor_data = $global_data['bachelor_page'] ?? [
    'page_breadcrumb'     => 'BCS Bachelor Package',
    'page_breadcrumb_url' => '#',
    'main_title'          => 'BCS Bachelor Package and Agreement Templet',
    'main_desc'           => 'هذا المستند محمي بكلمة مرور. يرجى <span style="color: #66aeee;">الاتصال بنا</span> للحصول على كلمة المرور<br>هذا المحتوى محمي بكلمة مرور. لإظهار المحتوى يتعين عليك كتابة كلمة المرور في الأدنى:',
    'password_label'      => 'كلمة المرور:',
    'btn_text'            => 'ادخال'
];

$data['bachelor_page'] = $bachelor_data;

// 3. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً
$page_css = [
    'edu-services/css/edu-services.css'
];
$page_js = [];

// 4. استدعاء الهيدر المشترك
include_once $path_prefix . 'includes/header.php'; 
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#bachelorBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'index.php'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'education.php'); ?>">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($bachelor_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($bachelor_data['page_breadcrumb'] ?? 'BCS Bachelor Package'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#bachelorContentModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل محتوى الصفحة وحماية كلمة المرور">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container text-center">
      <h2 class="pt-5"><?php echo htmlspecialchars($bachelor_data['main_title'] ?? ''); ?></h2>
      
      <!-- استخدام المتغير المباشر لدعم وسوم الـ HTML والـ span بأمان -->
      <p class="text-center pas-text"><?php echo $bachelor_data['main_desc'] ?? ''; ?></p>
      
      <div class="pas-info mt-5">
        <p><?php echo htmlspecialchars($bachelor_data['password_label'] ?? 'كلمة المرور:'); ?> .............................</p>
        <a href="#" class="btn"><?php echo htmlspecialchars($bachelor_data['btn_text'] ?? 'ادخال'); ?></a>
      </div>
    </div>
  </section>
  <!-- custom-services-info end-->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة
if (!empty($is_admin) && file_exists(__DIR__ . '/includes/admin_bachelor_modals.php')) { 
    include_once __DIR__ . '/includes/admin_bachelor_modals.php'; 
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
