<?php 
ob_start();

if (session_status() === PHP_SESSION_NONE) {
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

$cv_data = $global_data['cv_page'] ?? [
    'page_breadcrumb'     => 'السيرة الذاتية CV',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg2.jpg',
    'main_title'          => 'السيرة الذاتية "CV"',
    'main_desc'           => '',
    'advice_title'        => 'نصائح سريعة لكتابة CV فعّال',
    'advice_points'       => [],
    'download_items'      => []
];

$data['cv_page'] = $cv_data;

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
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($cv_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($cv_data['page_breadcrumb'] ?? 'السيرة الذاتية CV'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo $path_prefix . htmlspecialchars($cv_data['hero_img'] ?? 'assets/img/education/servicesimg2.jpg'); ?>?v=<?php echo time(); ?>'); background-position: center -30px;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. العنوان والوصف الرئيسي -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($cv_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($cv_data['main_desc'] ?? '')); ?></p>
      </div>
      
      <!-- 2. نصائح سريعة لكتابة CV فعّال -->
      <div class="advice-check pt-3 pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvAdviceModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل النصائح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($cv_data['advice_title'] ?? 'نصائح سريعة لكتابة CV فعّال'); ?></h5>
        <div class="row">
          <?php foreach (($cv_data['advice_points'] ?? []) as $point): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-2"><p>✅️ <?php echo htmlspecialchars($point); ?></p></div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. روابط تحميل النماذج (PDF & Word) -->
      <div class="row mt-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvDownloadModal" style="position: absolute; top: -10px; right: 10px; z-index: 10;" title="تعديل النماذج وملفات التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php foreach (($cv_data['download_items'] ?? []) as $item): 
            $is_pdf = (strtolower($item['type']) === 'pdf');
            $icon_img = $is_pdf ? 'Grouppdf.png' : 'Groupword.png';
            $alt_text = $is_pdf ? 'PDF Icon' : 'Word Icon';
        ?>
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="download-card mb-3">
              <div class="download-row">
                <img src="<?php echo $path_prefix; ?>assets/img/education/<?php echo $icon_img; ?>" alt="<?php echo $alt_text; ?>" />
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? ''); ?></div>
                  <div class="dl-sub"><?php echo htmlspecialchars($item['sub'] ?? 'Example'); ?></div>
                </div>
                <span class="leader d-lg-block d-md-none d-sm-none"
                  aria-hidden="true">................................................................................................................</span>
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
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_cv_modals.php')) { 
    include_once __DIR__ . '/includes/admin_cv_modals.php'; 
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
