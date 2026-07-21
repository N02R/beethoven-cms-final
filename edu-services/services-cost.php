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

$pricelist_data = $global_data['pricelist_page'] ?? [
    'page_breadcrumb'     => 'قائمة أسعار الخدمات',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg15.png',
    'hero_position'       => 'center center',
    'main_title'          => 'قائمة الأسعار العامة',
    'main_desc'           => 'يسعى فريق عمل بيتهوفن سيتي جاهداً لتوفير خدمة عالية الجودة وبتكلفة معقولة وتنافسية للطلبة والمتدربين الأجانب الذين يبحثون عن فرص التعليم العالي والتدريب في ألمانيا. يوضح الجدول أدناه بعض الخدمات التي نسعى لتقديمها مع التكلفة التقديرية لكل خِدمة.',
    'download_item'       => [
        'type'  => 'pdf',
        'title' => 'قائمة الأسعار العامة',
        'file'  => 'assets/files/general_price_list.pdf'
    ]
];

$global_data['pricelist_page'] = $pricelist_data;
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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($pricelist_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($pricelist_data['page_breadcrumb'] ?? 'قائمة أسعار الخدمات'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="custom-hero" 
           style="background-image: url('<?php echo $path_prefix . htmlspecialchars($pricelist_data['hero_img'] ?? 'assets/img/education/servicesimg15.png'); ?>?v=<?php echo time(); ?>'); background-position: <?php echo htmlspecialchars($pricelist_data['hero_position'] ?? 'center center'); ?>;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($pricelist_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($pricelist_data['main_desc'] ?? '')); ?></p>
      </div>
      
      <!-- جدول/كرت التحميل -->
      <div class="dl-card py-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListCardModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل ملف التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row mt-3">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="download-card">
              <div class="download-row">
                <?php 
                  $item = $pricelist_data['download_item'] ?? [];
                  $file_type = strtolower($item['type'] ?? 'pdf');
                  if ($file_type === 'word' || $file_type === 'docx') {
                      $icon_img = 'assets/img/education/Groupword.png';
                      $alt_text = 'ملف Word';
                  } else {
                      $icon_img = 'assets/img/education/Grouppdf.png';
                      $alt_text = 'ملف PDF';
                  }
                ?>
                <img src="<?php echo $path_prefix . $icon_img; ?>" alt="<?php echo $alt_text; ?>" />
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? 'قائمة الأسعار العامة'); ?></div>
                </div>
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................</span>
                <a class="download-link" href="<?php echo $path_prefix . htmlspecialchars($item['file'] ?? 'assets/files/general_price_list.pdf'); ?>" download>Download</a>
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
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_pricelist_modals.php')) {
    include_once __DIR__ . '/includes/admin_pricelist_modals.php';
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
