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

// 2. تحميل البيانات من ملف الـ JSON المركزي
$config_file = __DIR__ . '/../announcement_config.json';
$global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];

$lang_data = $global_data['language_page'] ?? [
    'page_breadcrumb'     => 'الدورة التحضيرية لشهادات اللغة الألمانية',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg12.png',
    'main_title'          => 'الدورات التحضيرية لشهادات اللغة الألمانية',
    'main_desc'           => '',
    'goals_title'         => 'أهداف الدورة التحضيرية',
    'goals'               => [],
    'warning_text'        => '',
    'cost_title'          => 'اماكن الالتحاق والتكلفة',
    'cost_items'          => []
];

$data['language_page'] = $lang_data;

// 3. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً ليتم حَقنه في الهيدر
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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'index.php'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'education.php'); ?>">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($lang_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($lang_data['page_breadcrumb'] ?? 'الدورة التحضيرية لشهادات اللغة الألمانية'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo htmlspecialchars($path_prefix . ($lang_data['hero_img'] ?? 'assets/img/education/servicesimg12.png')) . '?v=' . time(); ?>');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. العنوان والوصف الرئيسي (مع قلم تعديل مستقل) -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($lang_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($lang_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. أهداف الدورة التحضيرية (مع قلم تعديل مستقل) -->
      <div class="advice-check py-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langGoalsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل أهداف الدورة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars($lang_data['goals_title'] ?? 'أهداف الدورة التحضيرية'); ?></h5>
        <div class="row mt-3">
          <?php foreach (($lang_data['goals'] ?? []) as $goal): ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
              <p>✅ <?php echo htmlspecialchars($goal); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. ملاحظة شروط القبول (تعديل ضمن محتوى النص التحذيري) -->
      <div class="my-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langWarningModal" style="position: absolute; top: -5px; right: 0; z-index: 10;" title="تعديل نص التنبيه والشروط">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <p class="red-text pt-2"><?php echo htmlspecialchars($lang_data['warning_text'] ?? ''); ?></p>
      </div>

      <!-- 4. أماكن الالتحاق والتكلفة (مع قلم تعديل مستقل) -->
      <div class="advice-list py-4 mt-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langCostModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل أماكن الالتحاق والتكاليف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($lang_data['cost_title'] ?? 'اماكن الالتحاق والتكلفة'); ?></h5>
        <ul>
          <div class="row">
            <?php foreach (($lang_data['cost_items'] ?? []) as $item): ?>
              <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                <li><span><?php echo htmlspecialchars($item['title'] ?? ''); ?></span> <?php echo htmlspecialchars($item['desc'] ?? ''); ?></li>
              </div>
            <?php endforeach; ?>
          </div>
        </ul>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة
if (!empty($is_admin) && file_exists(__DIR__ . '/includes/admin_language_modals.php')) { 
    include_once __DIR__ . '/includes/admin_language_modals.php'; 
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
