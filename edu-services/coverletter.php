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
    'pdf_title'           => 'رسالة التعريف/ خطاب الطلب',
    'pdf_sub'             => 'Example',
    'pdf_file'            => 'downloads/cover-letter.pdf',
    'word_title'          => 'رسالة التعريف/ خطاب الطلب',
    'word_sub'            => 'Example',
    'word_file'           => 'downloads/cover-letter.docx'
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
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
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
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo $path_prefix . htmlspecialchars($cover_data['hero_img'] ?? 'assets/img/education/servicesimg1.jpg'); ?>?v=<?php echo time(); ?>'); background-position: center -30px;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($cover_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($cover_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. النقاط التي يجب مراعاتها -->
      <div class="advice-stars my-4 pb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverAdviceModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل نقاط النصائح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars($cover_data['advice_title'] ?? 'النقاط التي يجب مراعاتها عند كتابة رسالة التعريف'); ?></h5>
        <div class="row star-list mt-4">
          <?php foreach (($cover_data['advice_points'] ?? []) as $point): ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-3">
              <div class="d-flex align-items-center">
                <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" class="ms-2" alt="نجمة">
                <p class="mb-0"><?php echo htmlspecialchars($point); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. ملاحظات هامة -->
      <div class="advice-check py-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
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

      <!-- 4. روابط تحميل النماذج (PDF & Word) -->
      <div class="row pt-2" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverDownloadModal" style="position: absolute; top: -10px; right: 10px; z-index: 10;" title="تعديل روابط الملفات النماذج">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card mb-3">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="PDF Icon" />
              <div class="dl-info">
                <div class="dl-title"><?php echo htmlspecialchars($cover_data['pdf_title'] ?? ''); ?></div>
                <div class="dl-sub"><?php echo htmlspecialchars($cover_data['pdf_sub'] ?? 'Example'); ?></div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................</span>
              <a class="download-link" href="<?php echo htmlspecialchars($cover_data['pdf_file'] ?? 'downloads/cover-letter.pdf'); ?>" download>Download</a>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Groupword.png" alt="Word Icon" />
              <div class="dl-info">
                <div class="dl-title"><?php echo htmlspecialchars($cover_data['word_title'] ?? ''); ?></div>
                <div class="dl-sub"><?php echo htmlspecialchars($cover_data['word_sub'] ?? 'Example'); ?></div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................</span>
              <a class="download-link" href="<?php echo htmlspecialchars($cover_data['word_file'] ?? 'downloads/cover-letter.docx'); ?>" download>Download</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_cover_modals.php')) { 
    include_once __DIR__ . '/includes/admin_cover_modals.php'; 
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
