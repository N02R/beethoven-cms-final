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

$medical_data = $global_data['medical_package_page'] ?? [
    'page_breadcrumb'     => 'باقة التدريب الطبي',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/job/servicesimg3.png',
    'hero_position'       => 'center center',
    'main_title'          => 'عرض وإتفاقية التدريب المهني',
    'main_desc'           => 'كل عرضٍ (حالة) له تكلفة الخدمة الخاصة به حيث أن كل عرض يتضمن خدمات مختلفة وبذلك يتطلب إجراءات ومراسلات وجهود مختلفة. للحصول على فكرةٍ عامة عن العرض الخاص بك وتكلفة الخدمات الخاصة به، تجد أدناه العروض الأكثر طلباً (مثال لكل عرض).',
    'note_text'           => 'جميع العروض والاتفاقيات تكتب وتملأ باللغة الإنجليزية، للإستفسار عن أي بند أو شرح أي معلومات، لا تتردد بالتواصل معنا.',
    'download_item'       => [
        'type'  => 'pdf',
        'title' => 'عرض واتفاقية التدريب الطبي',
        'sub'   => 'Example',
        'file'  => 'assets/files/medical_training_agreement.pdf'
    ]
];

$global_data['medical_package_page'] = $medical_data;
$is_admin = $is_admin ?? ($_SESSION['is_admin'] ?? ($_SESSION['is_logged_in'] ?? false));

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
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>job.php">التدريب المهني</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($medical_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($medical_data['page_breadcrumb'] ?? 'باقة التدريب الطبي'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero" 
           style="background-image: url('<?php echo $path_prefix . htmlspecialchars($medical_data['hero_img'] ?? 'assets/img/job/servicesimg3.png'); ?>?v=<?php echo time(); ?>'); background-position: <?php echo htmlspecialchars($medical_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- العنوان والوصف الرئيسي -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($medical_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($medical_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- ملاحظات هامة -->
      <div class="advice-stars pt-3 pb-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h5 class="note-text">ملاحظات هامة !!</h5>
        <ul class="star-list">
          <li>
            <p>
              <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="تنبيه" class="ms-2" />
              <?php 
                $note_text = $medical_data['note_text'] ?? '';
                // السماح برابط التواصل داخل النص إذا وجد
                $note_text_formatted = str_replace('بالتواصل معنا', '<a href="' . $path_prefix . 'contact.php" class="fw-bold" style="color: #66aeee; text-decoration: none;">بالتواصل معنا</a>', htmlspecialchars($note_text));
                echo $note_text_formatted;
              ?>
            </p>
          </li>
        </ul>
      </div>

      <!-- كرت التحميل -->
      <div class="dl-card py-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalCardModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل ملف التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="download-card">
              <div class="download-row">
                <?php 
                  $item = $medical_data['download_item'] ?? [];
                  $file_type = strtolower($item['type'] ?? 'pdf');
                  $icon_img = ($file_type === 'word' || $file_type === 'docx') ? 'assets/img/education/Groupword.png' : 'assets/img/education/Grouppdf.png';
                  $alt_text = ($file_type === 'word' || $file_type === 'docx') ? 'ملف Word' : 'ملف PDF';
                ?>
                <img src="<?php echo $path_prefix . $icon_img; ?>" alt="<?php echo $alt_text; ?>" />
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? 'عرض واتفاقية التدريب الطبي'); ?></div>
                  <div class="dl-sub"><?php echo htmlspecialchars($item['sub'] ?? 'Example'); ?></div>
                </div>
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">...........................................................................................................</span>
                <a class="download-link" href="<?php echo $path_prefix . htmlspecialchars($item['file'] ?? 'assets/files/medical_training_agreement.pdf'); ?>" download>Download</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة إن وجدت
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_medical_modals.php')) {
    include_once __DIR__ . '/includes/admin_medical_modals.php';
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
