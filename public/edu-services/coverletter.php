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

$cover_data = $global_data['coverletter_page'] ?? [
    'page_breadcrumb'     => 'خطاب الطلب',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg1.jpg',
    'main_title'          => 'رسالة تعريف/خطاب طلب احترافي يدعم طلبك، أياً كان هدفك أو وجهتك',
    'main_desc'           => '',
    'advice_title'        => 'النقاط التي يجب مراعاتها عند كتابة رسالة التعريف',
    'advice_points'       => [],
    'note_title'          => 'ملاحظات هامة !!',
    'notes'               => [],
    'download_items'      => []
];

$data['coverletter_page'] = $cover_data;

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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'index.php'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'education.php'); ?>">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($cover_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($cover_data['page_breadcrumb'] ?? 'خطاب الطلب'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo htmlspecialchars($path_prefix . ($cover_data['hero_img'] ?? 'assets/img/education/servicesimg1.jpg')) . '?v=' . time(); ?>'); background-position: center -30px;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($cover_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($cover_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. النقاط التي يجب مراعاتها -->
      <div class="advice-stars my-4 pb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverAdviceModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل نقاط النصائح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars($cover_data['advice_title'] ?? 'النقاط التي يجب مراعاتها عند كتابة رسالة التعريف'); ?></h5>
        <div class="row star-list mt-4">
          <?php foreach (($cover_data['advice_points'] ?? []) as $point): ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-3">
              <div class="d-flex align-items-center">
                <img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/education/starList.svg'); ?>" class="ms-2" alt="نجمة">
                <p class="mb-0"><?php echo htmlspecialchars($point); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. ملاحظات هامة -->
      <div class="advice-check py-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل ملاحظات هامة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="note-text mb-4"><?php echo htmlspecialchars($cover_data['note_title'] ?? 'ملاحظات هامة !!'); ?></h5>
        <div class="row">
          <?php foreach (($cover_data['notes'] ?? []) as $note): ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-2"><p>✅ <?php echo htmlspecialchars($note); ?></p></div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 4. روابط تحميل النماذج (PDF & Word الديناميكية) -->
      <div class="row pt-2" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverDownloadModal" style="position: absolute; top: -10px; right: 10px; z-index: 10;" title="تعديل النماذج وملفات التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php foreach (($cover_data['download_items'] ?? []) as $item): 
            $is_pdf = (strtolower($item['type'] ?? '') === 'pdf');
            $icon_img = $is_pdf ? 'Grouppdf.png' : 'Groupword.png';
            $alt_text = $is_pdf ? 'PDF Icon' : 'Word Icon';
        ?>
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="download-card mb-3">
              <div class="download-row">
                <img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/education/' . $icon_img); ?>" alt="<?php echo htmlspecialchars($alt_text); ?>" />
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? ''); ?></div>
                  <div class="dl-sub"><?php echo htmlspecialchars($item['sub'] ?? 'Example'); ?></div>
                </div>
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................</span>
                <a class="download-link" href="<?php echo htmlspecialchars($item['file'] ?? '#'); ?>" download>Download</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة
if (!empty($is_admin) && file_exists(__DIR__ . '/includes/admin_cover_modals.php')) { 
    include_once __DIR__ . '/includes/admin_cover_modals.php'; 
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
