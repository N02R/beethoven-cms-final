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

$offers_data = $global_data['offers_page'] ?? [
    'page_breadcrumb'     => 'العروض والاتفاقيات',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/education/servicesimg10.png',
    'hero_position'       => 'center center',
    'main_title'          => 'العروض والاتفاقيات',
    'main_desc'           => 'كل عرضٍ (حالة) له تكلفة الخدمة الخاصة به حيث أن كل عرض يتضمن خدمات مختلفة وبذلك يتطلب إجراءات ومراسلات وجهود مختلفة. للحصول على فكرةٍ عامة عن العرض الخاص بك وتكلفة الخدمات الخاصة به، تجد أدناه العروض الأكثر طلباً (مثال لكل عرض).',
    'note_title'          => 'ملاحظات هامة !!',
    'note_text'           => 'جميع العروض والاتفاقيات تكتب وتملأ باللغة الإنجليزية، للإستفسار عن أي بند أو شرح أي معلومات، لا تتردد <a href="contact.php" class="fw-bold" style="color: #66aeee; text-decoration: none;">بالتواصل معنا</a>.',
    'download_cards'      => [
        [
            'title' => 'بكالوريوس',
            'file'  => 'assets/files/BCS-bachelor.pdf',
            'sub'   => 'حزمة واتفاقية البكالوريوس',
            'active'=> false
        ],
        [
            'title' => 'الماجستير',
            'file'  => 'assets/files/BCS-master.pdf',
            'sub'   => 'حزمة واتفاقية الماجستير',
            'active'=> true
        ],
        [
            'title' => 'الدكتوراه',
            'file'  => 'assets/files/BCS-phd.pdf',
            'sub'   => 'حزمة واتفاقية الدكتوراه',
            'active'=> false
        ]
    ]
];

$global_data['offers_page'] = $offers_data;
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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($offers_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($offers_data['page_breadcrumb'] ?? 'العروض والاتفاقيات'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start-->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="pakeges-hero custom-hero" 
           style="background-image: url('<?php echo $path_prefix . htmlspecialchars($offers_data['hero_img'] ?? 'assets/img/education/servicesimg10.png'); ?>?v=<?php echo time(); ?>'); background-position: <?php echo htmlspecialchars($offers_data['hero_position'] ?? 'center center'); ?>;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($offers_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($offers_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- ملاحظات هامة -->
      <div class="advice-stars py-4 mb-4 border-bottom" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات الهامة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="note-text mb-3"><?php echo htmlspecialchars($offers_data['note_title'] ?? 'ملاحظات هامة !!'); ?></h5>
        <ul class="star-list list-unstyled p-0">
          <li class="d-flex align-items-start">
            <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2 mt-1" />
            <p class="mb-0">
              <?php 
              // السماح بعرض الـ HTML داخل نص الملاحظة (مثل روابط التواصل) بأمان
              $note_text_processed = str_replace('href="contact.php"', 'href="' . $path_prefix . 'contact.php"', $offers_data['note_text'] ?? '');
              echo $note_text_processed; 
              ?>
            </p>
          </li>
        </ul>
      </div>

      <!-- كروت التحميل والاتفاقيات -->
      <div class="dl-card py-4" style="position: relative;">
        <?php if (isset($is_admin) && $is_admin): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersCardsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الحزم والاتفاقيات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row g-4 mt-2">
          <?php if (!empty($offers_data['download_cards']) && is_array($offers_data['download_cards'])): ?>
            <?php foreach ($offers_data['download_cards'] as $card): ?>
              <?php 
                $is_active = !empty($card['active']);
                $card_class = $is_active ? 'card card-active h-100' : 'card h-100';
                $title_class = $is_active ? 'text-bg text-center text-bg-active' : 'text-bg text-center';
                $link_class = $is_active ? 'text-active' : '';
                $p_class = $is_active ? 'text-active mb-0' : 'mb-0';
              ?>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="<?php echo $card_class; ?>">
                  <h5 class="<?php echo $title_class; ?>"><?php echo htmlspecialchars($card['title'] ?? ''); ?></h5>
                  <div class="card-body d-flex align-items-center gap-3">
                    <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="ملف PDF" />
                    <div class="card-body-info">
                      <a href="<?php echo $path_prefix . htmlspecialchars($card['file'] ?? '#'); ?>" class="<?php echo $link_class; ?>" download>Download</a>
                      <p class="<?php echo $p_class; ?>"><?php echo htmlspecialchars($card['sub'] ?? ''); ?></p>
                    </div>
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
// 5. استدعاء مودالات الأدمن الخاصة بهذه الصفحة (إن وجدت أو تم إنشاؤها)
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_offers_modals.php')) {
    include_once __DIR__ . '/includes/admin_offers_modals.php';
}

// 6. استدعاء الفوتر المشترك
include_once $path_prefix . 'includes/footer.php'; 
?>
