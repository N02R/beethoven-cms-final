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

$german_data = $global_data['germanlang_page'] ?? [
    'page_breadcrumb'   => 'دورات اللغة الألمانية',
    'page_breadcrumb_url' => '#',
    'hero_img'          => 'assets/img/education/servicesimg4.png',
    'hero_position'     => 'center center',
    'main_title'        => 'دورات اللغة الألمانية باحتراف تدعم خطتك الأكاديمية والمهنية',
    'main_desc'         => 'اتقن اللغة الألمانية من البداية حتى التفوق مع دوراتنا المعتمدة حسب المستويات الرسمية (A1-C2), تُعدك للدراسة, العمل, التقديم للسفارة, او الحياة اليومية في ألمانيا.',
    'levels_section'    => [
        'title' => 'المستويات المتوفرة (طبقًا ل CEFR)',
        'levels_list' => [
            'المستوى A1: للمبتدئين.',
            'المستوى A2: المعرفة الأساسية في اللغة.',
            'المستوى B1: قبل المتوسط.',
            'المستوى B2: معرفة متوسطة في اللغة.',
            'المستوى C1: المستوى العلوي.',
            'المستوى C2: مُتقدم.'
        ]
    ],
    'features_section'  => [
        'title' => 'مميزات دوراتنا',
        'features_list' => [
            'معتمدون من CEFR.',
            'مدرسون ناطقون أصليون.',
            'شهادات مقبولة للسفارات والجامعات.',
            'دعم في التقدم للإمتحانات (DSH, TestDaf).'
        ]
    ],
    'tips_section'      => [
        'title' => 'نصائح للنجاح في الدراسة بالألمانية',
        'tips_list' => [
            'حضّر لامتحانات اللغة مبكرًا.',
            'مارس مهارات الاستماع والمحادثة يوميًا.',
            'راقب تقدمك من خلال اختبارات دورية.',
            'استخدم موارد مساعدة مثل كتب ووسائط صوت.'
        ]
    ]
];

$data['germanlang_page'] = $german_data;

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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($german_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($german_data['page_breadcrumb'] ?? 'دورات اللغة الألمانية'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="germanlang-hero custom-hero" 
           style="background-image: url('<?php echo $path_prefix . htmlspecialchars($german_data['hero_img'] ?? 'assets/img/education/servicesimg4.png'); ?>?v=<?php echo time(); ?>'); background-position: <?php echo htmlspecialchars($german_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- القسم الرئيسي (العنوان والوصف) -->
      <div class="head-info pb-4 mb-4 border-bottom position-relative">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($german_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($german_data['main_desc'] ?? '')); ?></p>
      </div>
      
      <!-- 1. المستويات المتوفرة -->
      <div class="advice-stars my-5 py-4 border-bottom position-relative">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanLevelsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل المستويات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $levels_sec = $german_data['levels_section'] ?? [];
          $levels_title = $levels_sec['title'] ?? 'المستويات المتوفرة (طبقًا ل CEFR)';
          $levels_list = $levels_sec['levels_list'] ?? [];
        ?>
        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($levels_title); ?></h5>
        <ul class="star-list">
          <div class="row">
            <?php if (!empty($levels_list)): ?>
              <?php foreach ($levels_list as $level): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                  <li>
                    <p class="mb-0">
                      <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>
                      <?php echo htmlspecialchars($level); ?>
                    </p>
                  </li>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </ul>
      </div>

      <!-- 2. المميزات والنصائح -->
      <div class="advice-check py-4 position-relative">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanFeaturesTipsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل المميزات والنصائح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <!-- مميزات دوراتنا -->
        <?php 
          $feat_sec = $german_data['features_section'] ?? [];
          $feat_title = $feat_sec['title'] ?? 'مميزات دوراتنا';
          $feat_list = $feat_sec['features_list'] ?? [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($feat_title); ?></h5>
        <div class="row mb-5">
          <?php if (!empty($feat_list)): ?>
            <?php foreach ($feat_list as $feature): ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars($feature); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- نصائح للنجاح -->
        <?php 
          $tips_sec = $german_data['tips_section'] ?? [];
          $tips_title = $tips_sec['title'] ?? 'نصائح للنجاح في الدراسة بالألمانية';
          $tips_list = $tips_sec['tips_list'] ?? [];
        ?>
        <h5 class="mt-5 mb-4 advice-text"><?php echo htmlspecialchars($tips_title); ?></h5>
        <div class="row">
          <?php if (!empty($tips_list)): ?>
            <?php foreach ($tips_list as $tip): ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars($tip); ?></p>
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
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_german_modals.php')) {
    include_once __DIR__ . '/includes/admin_german_modals.php';
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
