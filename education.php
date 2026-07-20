<?php 
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

$path_prefix = ''; 

// 1. تعريف ملفات الـ CSS والـ JS الخاصة بصفحة التعليم العالي
$page_css = [
    'assets/css/education.css',
    'assets/css/responsive-education.css'
];

$page_js = [];

// 2. استدعاء الهيدر الأساسي
include_once 'includes/header.php'; 

// 3. تجهيز متغيرات بيانات "التعليم العالي" من $data مع القيم الافتراضية
$edu_hero = $data['edu_hero'] ?? [
    'title' => 'ابدأ رحلتك التعليمية في ألمانيا – مع خدماتنا، التعليم العالي أقرب إليك من أي وقت مضى!',
    'desc'  => 'نوفر لك دعمًا شاملًا في كل خطوة.. من اختيار التخصص والجامعة، إلى التقديم والحصول على القبول، وحتى تأمين السكن والتأشيرة، و بفضل خبرتنا ووجودنا داخل ألمانيا، نضمن لك تجربة سلسة وموثوقة، بلغتك، وبأسعار تنافسية',
    'btn_text' => 'ابدأ الآن',
    'btn_url'  => '#',
    'img'      => 'assets/img/education/hero.jpg'
];

$edu_why_title = $data['edu_why_title'] ?? 'لماذا الدراسة في ألمانيا؟';
$edu_why_desc  = $data['edu_why_desc'] ?? 'إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي';
$edu_why_items = $data['edu_why_items'] ?? [
    ['title' => 'جودة التعليم العالمي', 'desc' => 'جامعات ألمانية مرموقة وبرامج أكاديمية معترف بها دوليًا.', 'img' => 'assets/img/education/edu-services1.png'],
    ['title' => 'شهادات معترف بها دوليًا', 'desc' => 'الدراسة في ألمانيا تضمن لك شهادة معترف بها وفرص عمل ومستقبل مهني ناجح.', 'img' => 'assets/img/education/edu-services2.png'],
    ['title' => 'تدريب عملي إلى جانب الدراسة', 'desc' => 'الدراسة في ألمانيا تجمع بين التعلم النظري والتدريب العملي مع شركات حقيقية.', 'img' => 'assets/img/education/edu-services3.png'],
    ['title' => 'تخصصات متنوعة', 'desc' => 'معاهد ألمانيا تقدم آلاف البرامج والشهادات لتناسب جميع اهتمامات الطلاب.', 'img' => 'assets/img/education/edu-services4.png'],
    ['title' => 'رسوم دراسية منخفضة', 'desc' => 'تعليم برسوم رمزية في الجامعات الحكومية، حتى للطلاب الخليجيين.', 'img' => 'assets/img/education/edu-services5.png'],
    ['title' => 'فرصة لاكتشاف أوروبا', 'desc' => 'تأشيرة الطالب تتيح لك الإقامة في ألمانيا والسفر بحرية داخل أوروبا بدون تأشيرة.', 'img' => 'assets/img/education/edu-services6.png'],
    ['title' => 'الدراسة بالإنجليزية أو الألمانية', 'desc' => 'ألمانيا تقدم آلاف البرامج الدراسية باللغة الإنجليزية لجميع الطلاب.', 'img' => 'assets/img/education/edu-services7.png'],
    ['title' => 'إمكانية العمل أثناء الدراسة', 'desc' => 'تكلفة المعيشة في ألمانيا معقولة، ويمكنك العمل أثناء الدراسة لتساعد نفسك.', 'img' => 'assets/img/education/edu-services8.png'],
    ['title' => 'فرص توظيف بعد التخرج', 'desc' => 'بعد التخرج، يمكنك البقاء في ألمانيا لفترة للبحث عن وظيفة.', 'img' => 'assets/img/education/edu-services9.png'],
    ['title' => 'بلد آمن ومستقر', 'desc' => 'ألمانيا بلد آمن جدًا، يمكنك التنقل بحرية بدون خوف من الجريمة أو العنصرية.', 'img' => 'assets/img/education/edu-services8.png'],
    ['title' => 'تعلم الألمانية = فرص أكبر', 'desc' => 'الألمانية قريبة من الإنجليزية وتزيد فرصك في الدراسة والشغل.', 'img' => 'assets/img/education/edu-services11.png'],
    ['title' => 'ثقافة غنية وتجربة حياتية مميزة', 'desc' => 'مجتمع متنوع، صداقات دولية، وانفتاح ثقافي.', 'img' => 'assets/img/education/edu-services12.png']
];

$edu_timeline_title = $data['edu_timeline_title'] ?? 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS';
$edu_timeline_desc  = $data['edu_timeline_desc'] ?? 'نرشدك من أول استشارة حتى استقرارك في ألمانيا — إليك كيف تتم العملية معنا.';
$edu_timeline_steps = $data['edu_timeline_steps'] ?? [
    ['title' => 'استشارة أولية', 'subtitle' => 'نرسم معك طريقك الدراسي في ألمانيا', 'desc' => 'نساعدك على تحديد التخصص والجامعة المناسبة حسب أهدافك الأكاديمية والمهنية.', 'icon' => 'assets/img/vector/Grouptime1.png'],
    ['title' => 'تجهيز المستندات', 'subtitle' => 'نجهز ملفك بالشكل المثالي', 'desc' => 'ترجمة، تصديق، تنسيق السيرة الذاتية، كتابة خطاب الدافع وكل ما تحتاجه لتقديم قوي', 'icon' => 'assets/img/vector/Grouptime2.png'],
    ['title' => 'تقديم الطلبات', 'subtitle' => 'نقدم لك على أفضل الجامعات', 'desc' => 'نختار أفضل الجامعات ونرسل طلباتك ونتابع الردود معك', 'icon' => 'assets/img/vector/Grouptime3.png'],
    ['title' => 'دعم التأشيرة', 'subtitle' => 'نضمن جهوزيتك الكاملة للمقابلة', 'desc' => 'نعد معك ملف الفيزا بالكامل ونرشدك خلال الإجراءات الرسمية خطوة بخطوة', 'icon' => 'assets/img/vector/Grouptime4.png'],
    ['title' => 'الوصول والاستقرار', 'subtitle' => 'نستقبلك ونرتب تفاصيل حياتك', 'desc' => 'من الاستقبال في المطار، إلى السكن، إلى التسجيل في المدينة وفتح الحساب البنكي', 'icon' => 'assets/img/vector/Grouptime5.png'],
    ['title' => 'دعم بعد الوصول', 'subtitle' => 'نبقى معك حتى تستقر تمامًا', 'desc' => 'دعم دائم بعد الوصول يشمل الإرشاد، المتابعة الدراسية، وحل أي تحديات تواجهها', 'icon' => 'assets/img/vector/Grouptime6.png']
];

$edu_services_title = $data['edu_services_title'] ?? 'ماذا تقدم في بيتهوفن سيتي؟';
$edu_services_desc  = $data['edu_services_desc'] ?? 'توفر شركة بيتهوفن سيتي خدمات متكاملة للطلبة الراغبين بالالتحاق بالجامعات الألمانية بما في ذلك طلبة الدراسات العليا';
$edu_services_items = $data['edu_services_items'] ?? [
    ['title' => 'خطاب الطلب', 'url' => 'edu-services/coverletter.php', 'img' => 'assets/img/education/servicesimg1.jpg'],
    ['title' => 'السيرة الذاتية (CV)', 'url' => 'edu-services/cv.php', 'img' => 'assets/img/education/servicesimg2.jpg'],
    ['title' => 'رسالة الدافع والتحفيز', 'url' => 'edu-services/motivitionletter.php', 'img' => 'assets/img/education/servicesimg3.png'],
    ['title' => 'دورات اللغة الألمانية', 'url' => 'edu-services/germanlang.php', 'img' => 'assets/img/education/servicesimg4.png'],
    ['title' => 'مساقات اللغة الإنجليزية', 'url' => 'edu-services/englishlang.php', 'img' => 'assets/img/education/servicesimg5.png'],
    ['title' => 'التأمين الصحي', 'url' => 'edu-services/health.php', 'img' => 'assets/img/education/servicesimg6.png'],
    ['title' => 'الضمانات المالية', 'url' => 'edu-services/financial.php', 'img' => 'assets/img/education/servicesimg7.png'],
    ['title' => 'تكلفة المعيشة في آلمانيا', 'url' => 'edu-services/living.php', 'img' => 'assets/img/education/servicesimg8.png'],
    ['title' => 'الوصول والإقامة', 'url' => 'edu-services/arrival.php', 'img' => 'assets/img/education/servicesimg9.png'],
    ['title' => 'برامجنا الأكاديمية', 'url' => 'edu-services/pakeges.php', 'img' => 'assets/img/education/servicesimg10.png'],
    ['title' => 'الدورة التأسيسية', 'url' => 'edu-services/foundation.php', 'img' => 'assets/img/education/serviceimg11.png'],
    ['title' => 'الدورة التحضيرية', 'url' => 'edu-services/courses.php', 'img' => 'assets/img/education/servicesimg12.png'],
    ['title' => 'تحقق من شهادتك', 'url' => 'edu-services/check.php', 'img' => 'assets/img/education/servicesimg13.png'],
    ['title' => 'متطلبات التأشيرة', 'url' => 'edu-services/general.php', 'img' => 'assets/img/education/servicesimg14.png'],
    ['title' => 'قائمة أسعار الخدمات', 'url' => 'edu-services/services-cost.php', 'img' => 'assets/img/education/servicesimg15.png']
];
?>

  <!-- 1. education start -->
  <section class="education py-5" style="position: relative;">
    <?php if ($is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="row align-items-stretch g-5">
        <div class="col-lg-6">
          <div class="img-hero" style="background-image: url('<?php echo $path_prefix . ($edu_hero['img'] ?? 'assets/img/education/hero.jpg') . '?v=' . time(); ?>'); background-size: cover; background-position: center; min-height: 350px; border-radius: 20px;"></div>
        </div>
        <div class="col-lg-6">
          <div class="education-info pt-2">
            <h2 class="sec-title"><?php echo htmlspecialchars($edu_hero['title'] ?? ''); ?></h2>
            <p class="main-p"><?php echo nl2br(htmlspecialchars($edu_hero['desc'] ?? '')); ?></p>
            <a href="<?php echo htmlspecialchars($edu_hero['btn_url'] ?? '#'); ?>" class="btn btn-education">
              <?php echo htmlspecialchars($edu_hero['btn_text'] ?? 'ابدأ الآن'); ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- education end -->

  <!-- 2. why study start -->
  <section class="study py-5" style="position: relative;">
    <?php if ($is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduWhyModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل لماذا الدراسة">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($edu_why_title); ?></h2>
        <p class="main-p"><?php echo htmlspecialchars($edu_why_desc); ?></p>
      </div>
      <div class="row g-3">
        <?php foreach ($edu_why_items as $item): ?>
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="card choose-card h-100">
              <div class="card-body">
                <a href="#"><img src="<?php echo $path_prefix . ($item['img'] ?? '') . '?v=' . time(); ?>" alt="" /></a>
                <h5 class="card-title"><?php echo htmlspecialchars($item['title'] ?? ''); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($item['desc'] ?? ''); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <!-- why study end -->

  <!-- 3. time line start -->
  <section class="timeline-section py-5" style="position: relative;">
    <?php if ($is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduTimelineModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخطوات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($edu_timeline_title); ?></h2>
        <p class="main-p"><?php echo htmlspecialchars($edu_timeline_desc); ?></p>
      </div>
      <div class="map-container d-none d-lg-block">
        <div class="map-box">
          <img src="assets/img/vector/Vector.png" alt="base" class="line-base">
          <img src="assets/img/vector/Vector-1.png" alt="active" class="line-active">
          
          <?php 
          $dots = ['bg-blue', 'bg-green', 'bg-yellow', 'bg-orange', 'bg-orange', 'bg-red'];
          foreach ($edu_timeline_steps as $idx => $step): 
              $num = sprintf("%02d", $idx + 1);
              $dotClass = $dots[$idx % count($dots)];
          ?>
            <div class="step-wrapper step-<?php echo ($idx + 1); ?>">
              <div class="step-img-num"><img src="assets/img/vector/Group<?php echo ($idx + 1); ?>.png" alt="<?php echo $num; ?>"></div>
              <div class="icon-main"><img src="<?php echo $path_prefix . ($step['icon'] ?? "assets/img/vector/Grouptime".($idx+1).".png") . '?v=' . time(); ?>" alt=""></div>
              <div class="info-content">
                <h3><?php echo htmlspecialchars($step['title'] ?? ''); ?></h3>
                <span class="dot <?php echo $dotClass; ?>"></span>
                <h4><?php echo htmlspecialchars($step['subtitle'] ?? ''); ?></h4>
                <p><?php echo htmlspecialchars($step['desc'] ?? ''); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="mobile-timeline d-lg-none">
        <?php foreach ($edu_timeline_steps as $idx => $step): 
            $num = sprintf("%02d", $idx + 1);
        ?>
          <div class="m-step">
            <div class="m-number-box">
              <span class="m-num"><?php echo $num; ?></span>
            </div>
            <div class="m-content">
              <div class="m-header">
                <div class="m-icon"><img src="<?php echo $path_prefix . ($step['icon'] ?? "assets/img/vector/Grouptime".($idx+1).".png") . '?v=' . time(); ?>" alt=""></div>
                <h3><?php echo htmlspecialchars($step['title'] ?? ''); ?></h3>
              </div>
              <h4><?php echo htmlspecialchars($step['subtitle'] ?? ''); ?></h4>
              <p><?php echo htmlspecialchars($step['desc'] ?? ''); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <!-- time line end -->

  <!-- 4. education services start -->
  <section class="edu-services py-5" style="position: relative;">
    <?php if ($is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduServicesModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل خدمات التعليم">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="container">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars($edu_services_title); ?></h2>
      <p class="mb-5 main-p"><?php echo htmlspecialchars($edu_services_desc); ?></p>
      <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 text-center">
        <?php foreach ($edu_services_items as $item): ?>
          <div class="col">
            <a href="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>">
              <div class="card service-card text-white border-0 rounded-5"
                style="background-image: url('<?php echo $path_prefix . ($item['img'] ?? '') . '?v=' . time(); ?>');">
                <div class="card-body d-flex align-items-end justify-content-center">
                  <h6 class="card-title">
                    <a href="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>">
                      <?php echo htmlspecialchars($item['title'] ?? ''); ?>
                    </a>
                  </h6>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <!-- education services end -->

<?php 
// 4. استدعاء ملف مودالات التعليم العالي للأدمن
if ($is_admin && file_exists(__DIR__ . '/includes/admin_edu_modals.php')) { 
    include_once __DIR__ . '/includes/admin_edu_modals.php'; 
}

// 5. استدعاء الفوتر الأساسي
include_once 'includes/footer.php'; 
?>
