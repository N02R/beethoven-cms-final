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

$health_data = $global_data['health_insurance_page'] ?? [
    'page_breadcrumb'   => 'التأمين الصحي',
    'page_breadcrumb_url' => '#',
    'hero_img'          => 'assets/img/education/servicesimg6.png',
    'hero_position'     => 'center center',
    'main_title'        => 'التأمين الصحي للطلاب، الزائرين، المتدربين، العاملين، وغيرهم.',
    'main_desc'         => 'نوفر لك خيارات تأمين صحي حكومي وخاص تناسب حالتك وتعتمد من السفارات والجامعات الألمانية، لتبدأ رحلتك بثقة وبدون تعقيدات.',
    'importance_section' => [
        'title' => 'لماذا التأمين الصحي مهم؟',
        'items' => [
            'شرط أساسي لقبول تأشيرة الدراسة أو التدريب.',
            'يحميك من تكاليف العلاج المرتفعة.',
            'مطلوب عند التسجيل في الجامعة.',
            'للتسجيل الرسمي لدى التأمين العام (Barmer, AOK, TK...)'
        ]
    ],
    'documents_section' => [
        'title' => 'الوثائق المكملة',
        'items' => [
            'قبول جامعي أو من معهد اللغة.',
            'صورة جواز السفر.',
            'بيانات الوصول.',
            'إثبات الدفع.'
        ]
    ],
    'expert_note'       => 'نحن نتعامل مع أحد الخبراء الألمان البارزين في تلبية خدمات التأمين الصحي للزوار الأجانب في ألمانيا والذين تتجاوز خبرتهم أكثر من 60 عامًا في هذا المجال.. يمكنك مباشرة أن تختار وتحجز تأمينك الصحي المناسب من خلال أحد الروابط التالية:',
    'insurance_links'   => [
        [
            'title' => 'إحجز التأمين الصحي الخاص بإقامة التعليم في ألمانيا وأوروبا من خلال شركة - Dr.WALTER',
            'url'   => 'https://www.dr-walter.com/',
            'active' => true
        ],
        [
            'title' => 'إحجز التأمين الصحي الخاص بإقامة التعليم في ألمانيا وأوروبا من خلال شركة - FINTIBA',
            'url'   => 'https://www.fintiba.com/',
            'active' => false
        ]
    ]
];

$data['health_insurance_page'] = $health_data;
$is_admin = $is_admin ?? ($_SESSION['is_admin'] ?? false);

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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($health_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($health_data['page_breadcrumb'] ?? 'التأمين الصحي'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="health-hero custom-hero" 
           style="background-image: url('<?php echo $path_prefix . htmlspecialchars($health_data['hero_img'] ?? 'assets/img/education/servicesimg6.png'); ?>?v=<?php echo time(); ?>'); background-position: <?php echo htmlspecialchars($health_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- القسم الرئيسي (العنوان والوصف) -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($health_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($health_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 1. لماذا التأمين الصحي مهم؟ -->
      <div class="advice-check py-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthImportanceModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل أهمية التأمين الصحي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $imp_sec = $health_data['importance_section'] ?? [];
          $imp_title = $imp_sec['title'] ?? 'لماذا التأمين الصحي مهم؟';
          $imp_items = $imp_sec['items'] ?? [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($imp_title); ?></h5>
        <div class="row">
          <?php if (!empty($imp_items)): ?>
            <?php foreach ($imp_items as $item): ?>
              <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars($item); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- 2. الوثائق المكملة -->
      <div class="advice-stars my-5 py-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthDocumentsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الوثائق المكملة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $doc_sec = $health_data['documents_section'] ?? [];
          $doc_title = $doc_sec['title'] ?? 'الوثائق المكملة';
          $doc_items = $doc_sec['items'] ?? [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($doc_title); ?></h5>
        <ul class="star-list">
          <div class="row">
            <?php if (!empty($doc_items)): ?>
              <?php foreach ($doc_items as $doc): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                  <li>
                    <p class="mb-0">
                      <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" />
                      <?php echo htmlspecialchars($doc); ?>
                    </p>
                  </li>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </ul>
      </div>

      <!-- 3. ملاحظة الخبير والروابط -->
      <div class="py-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthLinksModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظة والروابط">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <p class="advice-text mt-4 mb-4"><?php echo nl2br(htmlspecialchars($health_data['expert_note'] ?? '')); ?></p>
        
        <?php if (!empty($health_data['insurance_links'])): ?>
          <?php foreach ($health_data['insurance_links'] as $link_item): ?>
            <a href="<?php echo htmlspecialchars($link_item['url'] ?? '#'); ?>" target="_blank" rel="noopener" class="link <?php echo (!empty($link_item['active'])) ? 'active' : 'mt-4'; ?> d-block text-decoration-none mb-3">
              <p class="text-center mb-0"><?php echo htmlspecialchars($link_item['title'] ?? ''); ?></p>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_health_modals.php')) {
    include_once __DIR__ . '/includes/admin_health_modals.php';
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
