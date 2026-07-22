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

$stk_data = $global_data['studienkolleg_page'] ?? [
    'page_breadcrumb'   => 'الدورة التأسيسية / السنة التحضيرية',
    'page_breadcrumb_url' => '#',
    'hero_img'          => 'assets/img/education/serviceimg11.png',
    'hero_position'     => 'center -20rem',
    'main_title'        => 'الدورة التأسيسيّة/السنة التحضيرية \"Studienkolleg\"',
    'main_desc'         => '',
    'goals_title'       => 'أهداف الدورة التأسيسية',
    'goals_items'       => [],
    'learning_title'    => '',
    'learning_intro'    => '',
    'learning_p1'       => '',
    'learning_p2'       => '',
    'courses_title'     => 'أنواع دورات السنة التحضيرية',
    'courses_items'     => [],
    'uni_type_title'    => '',
    'uni_type_intro'    => '',
    'uni_public'        => '',
    'uni_applied'       => '',
    'types_title'       => 'أنواع السنة التحضيرية في ألمانيا',
    'type_public_desc'  => '',
    'type_private_desc' => '',
    'notes_title'       => 'ملاحظات هامة !!',
    'notes_items'       => [],
    'exam_title'        => '',
    'exam_desc'         => '',
    'fsp_title'         => '',
    'fsp_desc'          => '',
    'tips_title'        => 'نصائح مهمة قبل التقديم',
    'tips_items'        => []
];

$data['studienkolleg_page'] = $stk_data;

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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'index.php'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix . 'education.php'); ?>">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($stk_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($stk_data['page_breadcrumb'] ?? 'الدورة التأسيسية / السنة التحضيرية'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start-->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="foundation-hero custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars($path_prefix . ($stk_data['hero_img'] ?? 'assets/img/education/serviceimg11.png')) . '?v=' . time(); ?>'); background-position: <?php echo htmlspecialchars($stk_data['hero_position'] ?? 'center -20rem'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. العنوان الرئيسي -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان الرئيسي والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($stk_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($stk_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. أهداف الدورة التأسيسية -->
      <div class="advice-check py-5 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkGoalsModal" style="position: absolute; top: 10px; right: 0; z-index: 10;" title="تعديل أهداف الدورة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($stk_data['goals_title'] ?? 'أهداف الدورة التأسيسية'); ?></h5>
        <div class="row">
          <?php foreach (($stk_data['goals_items'] ?? []) as $goal): ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
              <p class="mb-0">✅ <?php echo htmlspecialchars($goal); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. ماذا يدرس و يتعلم الطالب -->
      <div class="head-info py-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkLearningModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل المحتوى الدراسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-3"><?php echo htmlspecialchars($stk_data['learning_title'] ?? ''); ?></h5>
        <p class="par-text" style="font-weight: 500;"><?php echo htmlspecialchars($stk_data['learning_intro'] ?? ''); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($stk_data['learning_p1'] ?? '')); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($stk_data['learning_p2'] ?? '')); ?></p>
      </div>

      <!-- 4. أنواع دورات السنة التحضيرية -->
      <div class="advice-stars my-5 pb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkCoursesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل أنواع الدورات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($stk_data['courses_title'] ?? 'أنواع دورات السنة التحضيرية'); ?></h5>
        <ul class="star-list">
          <div class="row">
            <?php foreach (($stk_data['courses_items'] ?? []) as $course): ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <li>
                  <p class="mb-0">
                    <img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/education/starList.svg'); ?>" alt="نجمة" class="ms-2"/>
                    <?php echo htmlspecialchars($course); ?>
                  </p>
                </li>
              </div>
            <?php endforeach; ?>
          </div>
        </ul>
      </div>

      <!-- 5. ارتباطها بالجامعات -->
      <div class="head-info py-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkUniTypeModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل علاقة الجامعات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-3"><?php echo htmlspecialchars($stk_data['uni_type_title'] ?? ''); ?></h5>
        <p class="par-text" style="font-weight: 500;"><?php echo htmlspecialchars($stk_data['uni_type_intro'] ?? ''); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($stk_data['uni_public'] ?? '')); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($stk_data['uni_applied'] ?? '')); ?></p>
      </div>

      <!-- 6. أنواع السنة التحضيرية (حكومية / خاصة) -->
      <div class="head-info py-5 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkTypesModal" style="position: absolute; top: 10px; right: 0; z-index: 10;" title="تعديل الأنواع الحكومية والخاصة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($stk_data['types_title'] ?? 'أنواع السنة التحضيرية في ألمانيا'); ?></h5>
        <p class="par-text mb-4"><?php echo nl2br(htmlspecialchars($stk_data['type_public_desc'] ?? '')); ?></p>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($stk_data['type_private_desc'] ?? '')); ?></p>
      </div>

      <!-- 7. ملاحظات هامة -->
      <div class="advice-stars py-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات الهامة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="note-text mb-4"><?php echo htmlspecialchars($stk_data['notes_title'] ?? 'ملاحظات هامة !!'); ?></h5>
        <ul class="star-list">
          <div class="d-flex flex-column gap-2">
            <?php foreach (($stk_data['notes_items'] ?? []) as $note): ?>
              <li>
                <p class="mb-0">
                  <img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/education/starList.svg'); ?>" alt="نجمة" class="ms-2"/>
                  <?php echo htmlspecialchars($note); ?>
                </p>
              </li>
            <?php endforeach; ?>
          </div>
        </ul>
      </div>

      <!-- 8. اختبار القبول والـ FSP -->
      <div class="head-info py-5 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkExamFspModal" style="position: absolute; top: 10px; right: 0; z-index: 10;" title="تعديل اختبار القبول والتقييم">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-3 advice-text"><?php echo htmlspecialchars($stk_data['exam_title'] ?? ''); ?></h5>
        <p class="par-text mb-4" style="font-weight: 500;"><?php echo nl2br(htmlspecialchars($stk_data['exam_desc'] ?? '')); ?></p>

        <h5 class="mb-3 advice-text mt-5"><?php echo htmlspecialchars($stk_data['fsp_title'] ?? ''); ?></h5>
        <p class="par-text" style="font-weight: 500;"><?php echo nl2br(htmlspecialchars($stk_data['fsp_desc'] ?? '')); ?></p>
      </div>

      <!-- 9. نصائح مهمة قبل التقديم -->
      <div class="advice-check py-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#stkTipsModal" style="position: absolute; top: 10px; right: 0; z-index: 10;" title="تعديل النصائح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($stk_data['tips_title'] ?? 'نصائح مهمة قبل التقديم'); ?></h5>
        <div class="row">
          <?php foreach (($stk_data['tips_items'] ?? []) as $tip): ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
              <p class="mb-0">✅ <?php echo htmlspecialchars($tip); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </section>
  <!-- custom-services-info end-->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة
if (!empty($is_admin) && file_exists(__DIR__ . '/includes/admin_studienkolleg_modals.php')) { 
    include_once __DIR__ . '/includes/admin_studienkolleg_modals.php'; 
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
