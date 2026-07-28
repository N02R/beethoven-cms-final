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

$check_data = $global_data['check_page'] ?? [
    'page_breadcrumb'   => 'تحقق من شهاداتك التعليمية',
    'page_breadcrumb_url' => '#',
    'hero_img'          => 'assets/img/education/servicesimg13.png',
    'main_title'        => 'إفحص و تحقق من شهاداتك التعليمية السابقة',
    'main_desc'         => '',
    'note_title'        => 'ملاحظات هامة !!',
    'notes'             => [],
    'links_intro'       => '',
    'anabin_url'        => 'https://anabin.kmk.org/anabin.html',
    'uniassist_url'     => 'https://www.uni-assist.de',
    'uni_contact_intro' => '',
    'condition_1'       => '',
    'condition_2'       => '',
    'conclusion_text'   => ''
];

$data['check_page'] = $check_data;

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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'index.php'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'education.php'); ?>">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($check_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($check_data['page_breadcrumb'] ?? 'تحقق من شهاداتك التعليمية'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo htmlspecialchars($path_prefix . ($check_data['hero_img'] ?? 'assets/img/education/servicesimg13.png')) . '?v=' . time(); ?>'); background-position: center -9rem;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. قسم العنوان الرئيسي والوصف -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkMainContentModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($check_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($check_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. قسم الملاحظات الهامة (النجوم) -->
      <div class="advice-stars my-4 pb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات الهامة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-4 note-text"><?php echo htmlspecialchars($check_data['note_title'] ?? 'ملاحظات هامة !!'); ?></h5>
        <ul class="star-list">
          <?php foreach (($check_data['notes'] ?? []) as $note): ?>
            <li class="mb-2">
              <p><img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/education/starList.svg'); ?>" alt="" class="ms-2"/><?php echo $note; ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- 3. قسم الروابط والتعليمات والنتيجة النهائية -->
      <div class="links pt-2" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkLinksModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الروابط والفقرات التوضيحية">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <p class="mt-3"><?php echo htmlspecialchars($check_data['links_intro'] ?? ''); ?></p>
        
        <?php if (!empty($check_data['anabin_url'])): ?>
          <a href="<?php echo htmlspecialchars($check_data['anabin_url']); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($check_data['anabin_url']); ?></a>
        <?php endif; ?>
        
        <?php if (!empty($check_data['uniassist_url'])): ?>
          <a href="<?php echo htmlspecialchars($check_data['uniassist_url']); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($check_data['uniassist_url']); ?></a>
        <?php endif; ?>

        <p class="mt-5"><?php echo htmlspecialchars($check_data['uni_contact_intro'] ?? ''); ?></p>
        
        <span class="span-list"><?php echo htmlspecialchars($check_data['condition_1'] ?? ''); ?></span>
        <span class="span-list"><?php echo htmlspecialchars($check_data['condition_2'] ?? ''); ?></span>
        
        <!-- طباعة مباشرة لدعم وسوم الـ HTML المخصصة والتلوين بأمان -->
        <p class="mt-5"><?php echo $check_data['conclusion_text'] ?? ''; ?></p>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة
if (!empty($is_admin) && file_exists(__DIR__ . '/includes/admin_check_modals.php')) { 
    include_once __DIR__ . '/includes/admin_check_modals.php'; 
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
