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

// افتراضات أساسية في حال لم تكن البيانات موجودة مسبقاً في ملف الـ JSON
$motivation_data = $global_data['motivation_page'] ?? [
    'page_breadcrumb'     => 'خطاب الدافع / التحفيز',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg3.png',
    'hero_position'       => 'center center',
    'main_title'          => 'خطاب دافع احترافي يعزز طلبك الأكاديمي أو المهني',
    'main_desc'           => "خطاب الدافع/التحفيز هي وثيقة من صفحة واحدة كحد أقصى. تكتُب فيها عن نفسك وتُظهر إهتمامك بالطلب الذي تتقدم إليه و الهدف الذي تريد تحقيقه مثل: (دورة لغة ألمانية، سنة تحضيرية بهدف دخول الجامعة، درجة البكالوريوس أو الماجستير، التدريب أو الزمالة الطبية، إلخ).\nإضافة الى ذلك، يتركز الأمر أكثر على دراستك المستقبلية وخططك المهنية وكيف أن درجة البكالوريوس مثلا التي تتقدم إليها ستساعدك على تحقيق أهدافك المستقبلية. أيضا يمكنك أن تشرح بها الأسباب التي تجعل منك المرشح المثالي لهذا المنصب.",
    'advice_section'      => [
        'title' => 'نصائح سريعة لكتابة خطاب الدافع',
        'items' => [
            'ابدأ بمقدمة تلخّص دوافعك',
            'اذكر أمثلة ملموسة (دراسة، تدريب، تجربة)',
            'اربط خبراتك بأهدافك القادمة',
            'استخدم لغة واضحة وشخصية',
            'احصل على مراجعة من مختص أو ناطق أصلي.',
            'راجع الأخطاء اللغوية جيدًا.'
        ]
    ],
    'download_items'      => [
        [
            'type'  => 'pdf',
            'title' => 'خطاب الدافع / التحفيز',
            'sub'   => 'Example (PDF)',
            'file'  => 'assets/files/motivation_letter.pdf'
        ],
        [
            'type'  => 'word',
            'title' => 'خطاب الدافع / التحفيز',
            'sub'   => 'Example (Word)',
            'file'  => 'assets/files/motivation_letter.docx'
        ]
    ]
];

$data['motivation_page'] = $motivation_data;
$is_admin = $is_admin ?? ($_SESSION['is_admin'] ?? (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'));

// 3. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً ليتم حَقنه في الهيدر
$page_css = [
    'css/edu-services.css'
];
$page_js = [];

// 4. استدعاء الهيدر المشترك
include_once $path_prefix . 'includes/header.php'; 
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($motivation_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($motivation_data['page_breadcrumb'] ?? 'خطاب الدافع / التحفيز'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="motivition-hero custom-hero" 
           style="background-image: url('<?php echo $path_prefix . htmlspecialchars($motivation_data['hero_img'] ?? 'assets/img/education/servicesimg3.png'); ?>?v=<?php echo time(); ?>'); background-position: <?php echo htmlspecialchars($motivation_data['hero_position'] ?? 'center center'); ?>;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($motivation_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($motivation_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- قسم النصائح -->
      <div class="advice-check py-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationAdviceModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل نصائح كتابة خطاب الدافع">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $advice_sec = $motivation_data['advice_section'] ?? [];
          $advice_title = $advice_sec['title'] ?? 'نصائح سريعة لكتابة خطاب الدافع';
          $advice_items = $advice_sec['items'] ?? [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($advice_title); ?></h5>
        <div class="row gy-3">
          <?php if (!empty($advice_items)): ?>
            <?php foreach ($advice_items as $item): ?>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <p class="mb-0">✅ <?php echo htmlspecialchars($item); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- قسم ملفات التحميل -->
      <div class="row gy-3" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationDownloadModal" style="position: absolute; top: -10px; right: 0; z-index: 10;" title="إدارة ملفات التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $downloads = $motivation_data['download_items'] ?? [];
          if (!empty($downloads)):
            foreach ($downloads as $dl):
              $is_pdf = (strtolower($dl['type']) === 'pdf');
              $icon_img = $is_pdf ? 'Grouppdf.png' : 'Groupword.png';
        ?>
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="download-card">
                  <div class="download-row">
                    <img src="<?php echo $path_prefix; ?>assets/img/education/<?php echo $icon_img; ?>" alt="ملف <?php echo htmlspecialchars($dl['type']); ?>" />
                    <div class="dl-info">
                      <div class="dl-title"><?php echo htmlspecialchars($dl['title'] ?? ''); ?></div>
                      <div class="dl-sub"><?php echo htmlspecialchars($dl['sub'] ?? ''); ?></div>
                    </div>
                    <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.........................................................................................................................</span>
                    <a class="download-link" href="<?php echo htmlspecialchars($dl['file'] ?? '#'); ?>" download>Download</a>
                  </div>
                </div>
              </div>
        <?php 
            endforeach;
          endif; 
        ?>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة إن وجِدت
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_motivation_modals.php')) {
    include_once __DIR__ . '/includes/admin_motivation_modals.php';
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
