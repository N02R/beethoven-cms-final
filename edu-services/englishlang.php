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

$english_data = $global_data['english_programs_page'] ?? [
    'page_breadcrumb'     => 'برامج دراسية باللغة الإنجليزية',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg5.png',
    'main_title'          => 'برامج دراسية باللغة الإنجليزية في ألمانيا',
    'main_desc'           => '',
    'who_title'           => 'من يمكنه الاستفادة من هذه البرامج',
    'who_subtitle'        => 'كل من يستوفي الشروط التالية:',
    'who_items'           => [],
    'lang_title'          => 'متطلبات اللغة بشكل عام',
    'lang_points'         => [],
    'note_highlight'      => 'ملاحظة:',
    'note_text'           => ''
];

$data['english_programs_page'] = $english_data;

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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'index.php'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'education.php'); ?>">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($english_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($english_data['page_breadcrumb'] ?? 'برامج دراسية باللغة الإنجليزية'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="germanlang-hero custom-hero"
        style="background-image: url('<?php echo htmlspecialchars($path_prefix . ($english_data['hero_img'] ?? 'assets/img/education/servicesimg5.png')) . '?v=' . time(); ?>');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. العنوان والوصف الرئيسي -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($english_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($english_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. من يمكنه الاستفادة والشروط -->
      <div class="advice-check my-5 pb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishWhoModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل شروط الاستفادة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($english_data['who_title'] ?? 'من يمكنه الاستفادة من هذه البرامج'); ?></h5>
        <h6 class="mb-3"><?php echo htmlspecialchars($english_data['who_subtitle'] ?? 'كل من يستوفي الشروط التالية:'); ?></h6>
        <div class="row">
          <?php foreach (($english_data['who_items'] ?? []) as $index => $item): 
              $col_class = ($index == 2) ? 'col-lg-4 col-md-4 col-sm-12' : (($index == 3) ? 'col-lg-8 col-md-8 col-sm-12' : 'col-lg-6 col-md-6 col-sm-12');
          ?>
            <div class="<?php echo htmlspecialchars($col_class); ?> mb-2">
              <p>✅️<?php echo htmlspecialchars($item); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. متطلبات اللغة -->
      <div class="advice-stars pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishLangModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل متطلبات اللغة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($english_data['lang_title'] ?? 'متطلبات اللغة بشكل عام'); ?></h5>
        <ul class="star-list">
          <div class="row">
            <?php foreach (($english_data['lang_points'] ?? []) as $point): ?>
              <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <li>
                  <p><img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/education/starList.svg'); ?>" alt="نجمة" class="ms-2"/><?php echo htmlspecialchars($point); ?></p>
                </li>
              </div>
            <?php endforeach; ?>
          </div>
        </ul>
      </div>
      
      <!-- 4. الملاحظة -->
      <div class="note mt-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishNoteModal" style="position: absolute; top: -10px; right: 0; z-index: 10;" title="تعديل الملاحظة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <p><span style="color: #66aeee;"><?php echo htmlspecialchars($english_data['note_highlight'] ?? 'ملاحظة:'); ?></span> <?php echo htmlspecialchars($english_data['note_text'] ?? ''); ?></p>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة
if (!empty($is_admin) && file_exists(__DIR__ . '/includes/admin_english_modals.php')) { 
    include_once __DIR__ . '/includes/admin_english_modals.php'; 
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
