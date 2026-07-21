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

$visa_data = $global_data['visa_requirements_page'] ?? [
    'page_breadcrumb'     => 'متطلبات التأشيرة',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg14.png',
    'hero_position'       => 'center center',
    'main_title'          => 'قائمة مراجعة عامة لِمتطلبات تأشيرات مختلفة',
    'main_desc'           => '',
    'note_title'          => 'ملاحظة !!',
    'note_text'           => '',
    'download_items'      => []
];

$data['visa_requirements_page'] = $visa_data;

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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($visa_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($visa_data['page_breadcrumb'] ?? 'متطلبات التأشيرة'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero" 
           style="background-image: url('<?php echo $path_prefix . htmlspecialchars($visa_data['hero_img'] ?? 'assets/img/education/servicesimg14.png'); ?>?v=<?php echo time(); ?>'); background-position: <?php echo htmlspecialchars($visa_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- العنوان الرئيسي والوصف -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($visa_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($visa_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- الملاحظات (ديناميكية) -->
      <div class="advice-stars py-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $notes_sec = $visa_data['notes_section'] ?? [];
          $note_title = $notes_sec['title'] ?? 'ملاحظة !!';
          $notes_list = $notes_sec['notes_list'] ?? [];
        ?>

        <h5 class="mb-3 note-text"><?php echo htmlspecialchars($note_title); ?></h5>
        <ul class="star-list">
          <?php if (!empty($notes_list)): ?>
            <?php foreach ($notes_list as $note_text): ?>
              <li class="mb-2">
                <p class="mb-0">
                  <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" />
                  <?php echo htmlspecialchars($note_text); ?>
                </p>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>


      <!-- قائمة الملفات المتاحة للتحميل (ديناميكية) -->
      <div class="py-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaDownloadModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="إدارة ملفات التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row">
          <?php if (!empty($visa_data['download_items'])): ?>
            <?php foreach ($visa_data['download_items'] as $item): ?>
              <?php 
                $file_type = strtolower($item['type'] ?? 'pdf');
                $icon_img = ($file_type === 'word') ? 'Grouppdf.png' : 'Grouppdf.png'; // يمكنك تخصيص الأيقونة حسب النوع إذا رغبتِ
              ?>
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="download-card mb-3">
                  <div class="download-row">
                    <img src="<?php echo $path_prefix; ?>assets/img/education/<?php echo $icon_img; ?>" alt="ملف التحميل" />
                    <div class="dl-info">
                      <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? ''); ?></div>
                    </div>
                    <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">..............................................................................</span>
                    <a class="download-link" href="<?php echo $path_prefix . htmlspecialchars($item['file'] ?? '#'); ?>" download>Download</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_visa_modals.php')) { 
    include_once __DIR__ . '/includes/admin_visa_modals.php'; 
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
