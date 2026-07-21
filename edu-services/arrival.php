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

// --- التصحيح الجذري الآمن لقراءة البيانات وتهيئة arrival_page إن لمש تكن موجودة ---
$config_file = __DIR__ . '/../announcement_config.json';
$global_data = file_exists($config_file) ? json_decode(file_get_contents($config_file), true) : [];

// التأكد من وجود مفتاح arrival_page بداخل المصفوفة وجلب بياناته أو تعبئته بقيم افتراضية آمنة لمنع أي خطأ
$arrival_data = $global_data['arrival_page'] ?? [
    'hero_img'            => 'assets/img/education/servicesimg9.png',
    'main_title'          => 'الإستقبال في المطار والمواصلات',
    'main_desc'           => '',
    'advice_title'        => 'نصائح هامة',
    'advice_desc'         => '',
    'tips'                => [],
    'notes'               => [],
    'note_title'          => 'ملاحظات هامة',
    'page_breadcrumb'     => 'الإستقبال في المطار',
    'page_breadcrumb_url' => '#'
];
// ---------------------------------------------------------------------------------

// 2. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً ليقرأه الهيدر تلقائياً
$page_css = [
    'edu-services/css/edu-services.css'
];

$page_js = [];

// 3. استدعاء الهيدر المشترك
include_once $path_prefix . 'includes/header.php'; 
?>


  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($arrival_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($arrival_data['page_breadcrumb'] ?? 'الإستقبال في المطار، المواصلات، الإقامة والسكن'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->



  <!-- custom-services start-->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الهيدر والصورة">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="arrival-hero custom-hero" style="background-image: url('<?php echo $path_prefix . ($arrival_data['hero_img'] ?? 'assets/img/education/servicesimg9.png'); ?>?v=<?php echo time(); ?>');">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalContentModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل المحتوى والإرشادات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text"><?php echo htmlspecialchars($arrival_data['main_title']); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($arrival_data['main_desc'])); ?></p>
      </div>

      <div class="advice-check py-5">
        <h5 class="advice-text"><?php echo htmlspecialchars($arrival_data['advice_title']); ?></h5>
        <p><?php echo htmlspecialchars($arrival_data['advice_desc']); ?></p>
        <div class="row">
          <?php foreach ($arrival_data['tips'] as $tip): ?>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <p>✅ <?php echo htmlspecialchars($tip); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="advice-stars my-5">
        <h5 class="mb-4 note-text"><?php echo htmlspecialchars($arrival_data['note_title']); ?></h5>
        <ul class="star-list">
          <?php foreach ($arrival_data['notes'] as $note): ?>
            <li>
              <!-- لاحظي أننا لم نستخدم htmlspecialchars هنا للملاحظات للسماح بوجود أكواد HTML مثل span لتلوين البريد الإلكتروني -->
              <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2"><?php echo $note; ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>
  <!-- custom-services-info end-->


<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة إذا كان مسجلاً للدخول كأدمن
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_arrival_modals.php')) { 
    include_once __DIR__ . '/includes/admin_arrival_modals.php'; 
}

// 6. استدعاء الفوتر الأساسي المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
