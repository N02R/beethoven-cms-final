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

$living_data = $global_data['living_cost_page'] ?? [
    'page_breadcrumb'     => 'تكلفة المعيشة في ألمانيا',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg8.png',
    'hero_position'       => 'center center',
    'main_title'          => 'تكلفة المعيشة في ألمانيا للطلاب الأجانب',
    'main_desc'           => 'تُعد تكلفة المعيشة في ألمانيا معتدلة مقارنة بدول أوروبية أخرى، لكنها أعلى من الدول النامية. يحتاج الطالب الأجنبي عادةً بين 700 و900 يورو شهريًا لتغطية الإيجار، الطعام، المواصلات، التأمين وغيرها، وتختلف التكاليف حسب المدينة ونمط الحياة.',
    'tips_section'        => [
        'title' => 'نصائح لتقليل النفقات',
        'items' => [
            'اختيار السكن الجامعي أو مشاركة شقة مع طلاب آخرين.',
            'استخدام بطاقة الطالب لخصومات في المواصلات والمتاجر.',
            'فرز النفايات المنزلية إلزامي لتجنب الغرامات والإخلاء.',
            'تجنّب المكتبات العقارية إلا عند الضرورة، لتوفير التكاليف.'
        ]
    ],
    'notes_section'       => [
        'title' => 'ملاحظات هامة !!',
        'items' => [
            'يتوجب دفع وديعة (Kaution) تعادل إيجار شهر أو شهرين عند توقيع العقد.',
            'عند طلب سكن عن طريق مكتب عقاري، قد تُدفع عمولة تتراوح بين 600 إلى 1000 يورو.',
            'خيار مشاركة شقة (WG) مع طلاب آخرين يساعد في تقليل التكاليف.'
        ]
    ]
];

$data['living_cost_page'] = $living_data;
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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($living_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($living_data['page_breadcrumb'] ?? 'تكلفة المعيشة في ألمانيا'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="living-hero custom-hero" 
           style="background-image: url('<?php echo $path_prefix . htmlspecialchars($living_data['hero_img'] ?? 'assets/img/education/servicesimg8.png'); ?>?v=<?php echo time(); ?>'); background-position: <?php echo htmlspecialchars($living_data['hero_position'] ?? 'center center'); ?>;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($living_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($living_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 1. نصائح لتقليل النفقات -->
      <div class="advice-check py-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingTipsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل نصائح تقليل النفقات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $tips_sec = $living_data['tips_section'] ?? [];
          $tips_title = $tips_sec['title'] ?? 'نصائح لتقليل النفقات';
          $tips_items = $tips_sec['items'] ?? [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($tips_title); ?></h5>
        <div class="row">
          <?php if (!empty($tips_items)): ?>
            <?php foreach ($tips_items as $tip): ?>
              <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars($tip); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- 2. ملاحظات هامة -->
      <div class="advice-stars my-5 py-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات الهامة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $notes_sec = $living_data['notes_section'] ?? [];
          $notes_title = $notes_sec['title'] ?? 'ملاحظات هامة !!';
          $notes_items = $notes_sec['items'] ?? [];
        ?>
        <h5 class="mb-4 note-text"><?php echo htmlspecialchars($notes_title); ?></h5>
        <ul class="star-list list-unstyled p-0">
          <?php if (!empty($notes_items)): ?>
            <?php foreach ($notes_items as $note): ?>
              <li class="d-flex align-items-start mb-3">
                <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2 mt-1" />
                <p class="mb-0"><?php echo htmlspecialchars($note); ?></p>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة إن وجدت
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_living_modals.php')) {
    include_once __DIR__ . '/includes/admin_living_modals.php';
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
