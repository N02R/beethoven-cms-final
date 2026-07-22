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

$path_prefix = ''; 

// 1. تعريف ملفات الـ CSS والـ JS الخاصة بصفحة التدريب المهني
$page_css = [
    'assets/css/education.css', 
    'assets/css/responsive-education.css'
];

$page_js = [];

// 2. استدعاء الهيدر الأساسي
include_once 'includes/header.php'; 

// 3. تجهيز متغيرات بيانات "التدريب المهني والتوظيف" من $data مع القيم الافتراضية (Fallbacks)
$job_hero = $data['job_hero'] ?? [
    'title'    => 'ابدأ طريقك المهني في ألمانيا - تدريب عملي حقيقي، خبرة معترف بها، وفرص عمل تليق بطموحك!',
    'desc'     => 'نساعدك على ربط تعليمك الأكاديمي بالحياة العملية في ألمانيا من خلال برامج تدريب احترافية، بشراكة مع شركات ألمانية حقيقية. فرصة ذهبية لاكتساب خبرة أوروبية تُعزز سيرتك الذاتية وتفتح لك أبواب سوق العمل الدولي',
    'btn_text' => 'ابدأ الآن',
    'btn_url'  => '#',
    'img'      => 'assets/img/job/hero.jpg'
];

$job_why_title = $data['job_why_title'] ?? 'لماذا التدريب معنا؟';
$job_why_desc  = $data['job_why_desc'] ?? 'في بيتهوفن سيتي، نوفر لك تدريبًا مهنيًا مميزًا مع دعم مستمر وشراكات مع شركات ألمانية رائدة لبناء مستقبل مهني ناجح.';
$job_why_items = $data['job_why_items'] ?? [
    ['title' => 'الكفاءة والإحتراف', 'desc' => 'اكتساب مهارات نظرية وعملية متكاملة تُتقن بها مهنتك.', 'img' => 'assets/img/job/job-services1.png'],
    ['title' => 'تعلم واحصل على أجر', 'desc' => 'اكتساب راتب شهري أثناء فترة التدريب المهني.', 'img' => 'assets/img/job/job-services2.png'],
    ['title' => 'مستقبل مهني بخيارات متنوعة', 'desc' => 'فرص متنوعة بعد التدريب: دراسة جامعية، تأسيس عمل، أو دخول سوق العمل.', 'img' => 'assets/img/job/job-services3.png'],
    ['title' => 'فرص كبيرة للتوظيف', 'desc' => 'فرصة عالية للحصول على وظيفة دائمة في شركات التدريب بعد الانتهاء.', 'img' => 'assets/img/job/job-services4.png'],
    ['title' => 'شراكات حقيقية مع الشركات', 'desc' => 'نوفر فرص تدريب عملي حقيقي مع شركاء معتمدين في ألمانيا.', 'img' => 'assets/img/job/job-services5.png'],
    ['title' => 'دخل مضمون مع حد أدنى للأجور', 'desc' => 'حتى كمتدرب، تحصل على دخل ثابت وفق الحد الأدنى القانوني، ما يوفر دعم مستمر', 'img' => 'assets/img/job/job-services6.png'],
    ['title' => 'إجازات مرضية مدفوعة', 'desc' => 'في حال المرض، يحق لك الحصول على إجازة تصل إلى 6 أسابيع مدفوعة بالكامل.', 'img' => 'assets/img/job/job-services7.png'],
    ['title' => 'فرص إقامة وسكن ميسرة', 'desc' => 'نوفر لك دعمًا في تأمين سكن مريح طوال فترة التدريب.', 'img' => 'assets/img/job/job-services8.png'],
    ['title' => 'الأمان و الإستقرار', 'desc' => 'ألمانيا بلد آمن جدًا، يمكنك التنقل بحرية بدون خوف من الجريمة أو العنصرية.', 'img' => 'assets/img/job/job-services9.png'],
    ['title' => 'دعم تأشيرات وسفر', 'desc' => 'نساعدك في تجهيز التأشيرة وإجراءات السفر لدخول ألمانيا بسهولة.', 'img' => 'assets/img/job/job-services8.png'],
    ['title' => 'تنمية لغتك ومهاراتك الثقافية', 'desc' => 'تطور لغتك ومهاراتك الثقافية للاندماج السريع في بيئة العمل الألمانية.', 'img' => 'assets/img/job/job-services11.png'],
    ['title' => 'دعم كامل باللغة العربية', 'desc' => 'فريقنا يدعمك إداريًا ولغويًا بالعربية طوال فترة التدريب.', 'img' => 'assets/img/job/job-services12.png']
];

$job_program_title = $data['job_program_title'] ?? 'أنواع التدريب المهني';
$job_program_desc  = $data['job_program_desc'] ?? 'في ألمانيا، التدريب المهني ليس مسارًا واحدًا، بل يتفرّع إلى نوعين رئيسيين يلبيان احتياجات مختلفة للطلاب والمتدربين.';
$job_program_types = $data['job_program_types'] ?? [
    [
        'title'    => 'التدريب المهني المدرسي',
        'desc'     => 'التدريب المهني المدرسي يتم داخل مدرسة متخصصة، ويركز على الجوانب النظرية مع تطبيق عملي محدود. مناسب للمجالات الطبية والاجتماعية والتعليمية، ويكون مجانيًا أو برسوم حسب نوع المدرسة.',
        'btn_text' => 'اطلب الآن',
        'btn_url'  => '#',
        'img'      => 'assets/img/job/job-program1.png',
        'is_dark'  => false
    ],
    [
        'title'    => 'التدريب المهني المزدوج',
        'desc'     => 'التدريب المهني المزدوج في ألمانيا يجمع بين الدراسة في مدرسة مهنية والعمل داخل شركة، مما يوفر خبرة عملية حقيقية. يتمتع المتدرب بأجر شهري وتأمينات، وتكون لديه فرص عالية للتوظيف بعد الانتهاء.',
        'btn_text' => 'اطلب الآن',
        'btn_url'  => '#',
        'img'      => 'assets/img/job/job-program2.png',
        'is_dark'  => true
    ]
];

$job_timeline_title = $data['job_timeline_title'] ?? 'كيف نساعدك خطوة بخطوة؟';
$job_timeline_desc  = $data['job_timeline_desc'] ?? 'رحلة واضحة تبدأ بالاستشارة وتنتهي بشهادة تدريب وخبرة مهنية حقيقية.';
$job_timeline_steps = $data['job_timeline_steps'] ?? [
    ['title' => 'استشارة مهنية مخصصة', 'subtitle' => 'فهم أهدافك واختيار التخصص المناسب', 'desc' => 'مكالمة أولية لفهم خلفيتك واختيار المجال المناسب لك.', 'order' => 0, 'icon' => 'assets/img/vector/Grouptime1.png'],
    ['title' => 'اختيار البرنامج الأنسب', 'subtitle' => 'مطابقة بين مؤهلاتك والفرص المتاحة', 'desc' => 'مطابقة مؤهلاتك مع فرص تدريب متوفرة في شركات موثوقة.', 'order' => 1, 'icon' => 'assets/img/vector/Group (6).png'],
    ['title' => 'تجهيز ملفك المهني', 'subtitle' => 'عرض احترافي لسيرتك وخبراتك', 'desc' => 'إعداد سيرة ذاتية وخطاب تحفيزي وترجمة الشهادات.', 'order' => 2, 'icon' => 'assets/img/vector/Group (3).png'],
    ['title' => 'التقديم والتنسيق', 'subtitle' => 'تقديم الطلبات وترتيب المقابلات', 'desc' => 'نقدّم الطلبات نيابة عنك وننسق مقابلاتك مع الشركات.', 'order' => 3, 'icon' => 'assets/img/vector/Group (7).png'],
    ['title' => 'القبول والاستعداد', 'subtitle' => 'دعم كامل للسفر والسكن والتأشيرة', 'desc' => 'مساعدة في التأشيرة، السكن، والتحضير للخطوة العملية.', 'order' => 4, 'icon' => 'assets/img/vector/Group.png'],
    ['title' => 'المرافقة خلال التدريب', 'subtitle' => 'متابعة مستمرة حتى إنهاء التدريب بنجاح', 'desc' => 'متابعة مستمرة حتى نهاية البرنامج وإصدار شهادة رسمية.', 'order' => 5, 'icon' => 'assets/img/vector/Grouptime6.png']
];

// فرز مصفوفة الـ timeline بحسب حقل order
usort($job_timeline_steps, function($a, $b) {
    return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
});

$job_services_title = $data['job_services_title'] ?? 'ماذا تقدم في بيتهوفن سيتي؟';
$job_services_desc  = $data['job_services_desc'] ?? 'توفر شركة بيتهوفن سيتي خدمات متكاملة للطلبة الراغبين بالإلتحاق بالجامعات الألمانية بما في ذلك طلبة الدراسات العليا';
$job_services_items = $data['job_services_items'] ?? [
    ['title' => 'خطاب الطلب', 'url' => 'edu-services/coverletter.php', 'img' => 'assets/img/education/servicesimg1.jpg'],
    ['title' => 'السيرة الذاتية (CV)', 'url' => 'edu-services/cv.php', 'img' => 'assets/img/education/servicesimg2.jpg'],
    ['title' => 'رسالة الدافع والتحفيز', 'url' => 'edu-services/motivitionletter.php', 'img' => 'assets/img/education/servicesimg3.png'],
    ['title' => 'دورات اللغة الألمانية', 'url' => 'edu-services/germanlang.php', 'img' => 'assets/img/education/servicesimg4.png'],
    ['title' => 'التخصصات الطبية', 'url' => 'edu-services/englishlang.php', 'img' => 'assets/img/job/servicesimg1.png'],
    ['title' => 'التأمين الصحي', 'url' => 'edu-services/health.php', 'img' => 'assets/img/education/servicesimg6.png'],
    ['title' => 'الضمانات المالية', 'url' => 'edu-services/financial.php', 'img' => 'assets/img/education/servicesimg7.png'],
    ['title' => 'تكلفة المعيشة في آلمانيا', 'url' => 'edu-services/living.php', 'img' => 'assets/img/education/servicesimg8.png'],
    ['title' => 'الوصول والإقامة', 'url' => 'edu-services/arrival.php', 'img' => 'assets/img/education/servicesimg9.png'],
    ['title' => 'باقة التدريب المهني', 'url' => 'edu-services/pakeges.php', 'img' => 'assets/img/job/servicesimg2.png'],
    ['title' => 'باقة الإقامة الطبية', 'url' => 'edu-services/foundation.php', 'img' => 'assets/img/job/serviceimg3.png'],
    ['title' => 'اتفاقية البحث عن عمل', 'url' => 'edu-services/courses.php', 'img' => 'assets/img/job/servicesimg4.png'],
    ['title' => 'تحقق من شهادتك', 'url' => 'edu-services/check.php', 'img' => 'assets/img/education/servicesimg13.png'],
    ['title' => 'متطلبات التأشيرة', 'url' => 'edu-services/general.php', 'img' => 'assets/img/education/servicesimg14.png'],
    ['title' => 'قائمة أسعار الخدمات', 'url' => 'edu-services/services-cost.php', 'img' => 'assets/img/education/servicesimg15.png']
];
?>

  <!-- 1. job start -->
  <section class="job py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="row align-items-stretch g-5">
        <div class="col-lg-6">
          <div class="job-info pt-2">
            <h2 class="sec-title"><?php echo htmlspecialchars($job_hero['title'] ?? ''); ?></h2>
            <p class="main-p"><?php echo nl2br(htmlspecialchars($job_hero['desc'] ?? '')); ?></p>
            <a href="<?php echo htmlspecialchars($job_hero['btn_url'] ?? '#'); ?>" class="btn btn-job">
              <?php echo htmlspecialchars($job_hero['btn_text'] ?? 'ابدأ الآن'); ?>
            </a>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="img-hero" style="background-image: url('<?php echo htmlspecialchars($path_prefix . ($job_hero['img'] ?? 'assets/img/job/hero.jpg') . '?v=' . time()); ?>'); background-size: cover; background-position: center; min-height: 350px; border-radius: 20px;"></div>
        </div>
      </div>
    </div>
  </section>
  <!-- job end -->

  <!-- 2. why study start -->
  <section class="study py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobWhyModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل لماذا التدريب">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($job_why_title); ?></h2>
        <p class="main-p"><?php echo htmlspecialchars($job_why_desc); ?></p>
      </div>
      <div class="row g-3">
        <?php foreach ($job_why_items as $item): ?>
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="card choose-card h-100">
              <div class="card-body">
                <a href="#"><img src="<?php echo htmlspecialchars($path_prefix . ($item['img'] ?? '') . '?v=' . time()); ?>" alt="" /></a>
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

  <!-- 3. program start -->
  <section class="program py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobProgramModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل أنواع التدريب">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="container">
      <div class="mb-5">
        <h2 class="sec-title fw-bold"><?php echo htmlspecialchars($job_program_title); ?></h2>
        <p class="main-p"><?php echo htmlspecialchars($job_program_desc); ?></p>
      </div>
      <div class="row g-4">
        <?php foreach ($job_program_types as $p): ?>
          <div class="col-md-6">
            <div class="program-info h-100 <?php echo !empty($p['is_dark']) ? 'highlight-box' : ''; ?>">
              <div class="program-content">
                <img src="<?php echo htmlspecialchars($path_prefix . ($p['img'] ?? '') . '?v=' . time()); ?>" alt="<?php echo htmlspecialchars($p['title'] ?? ''); ?>" class="mb-3">
                <h4 class="fw-bold mb-4 <?php echo !empty($p['is_dark']) ? 'text-white' : ''; ?>"><?php echo htmlspecialchars($p['title'] ?? ''); ?></h4>
                <p class="<?php echo !empty($p['is_dark']) ? 'text-white' : ''; ?>">
                  <?php echo nl2br(htmlspecialchars($p['desc'] ?? '')); ?>
                </p>
              </div>

              <a href="<?php echo htmlspecialchars($p['btn_url'] ?? '#'); ?>" class="btn-info-wrapper mt-4 <?php echo !empty($p['is_dark']) ? 'is-light' : ''; ?>">
                <h3 class="mb-0"><?php echo htmlspecialchars($p['btn_text'] ?? 'اطلب الآن'); ?></h3>
                <div class="arrow-icon">
                  <img src="assets/img/home/Arrow.svg" alt="سهم">
                </div>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <!-- program end -->

  <!-- 4. time line start -->
  <section class="timeline-section py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobTimelineModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخطوات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($job_timeline_title); ?></h2>
        <p class="main-p"><?php echo htmlspecialchars($job_timeline_desc); ?></p>
      </div>
      <div class="map-container d-none d-lg-block">
        <div class="map-box">
          <img src="assets/img/vector/Vector.png" alt="base" class="line-base">
          <img src="assets/img/vector/Vector-1.png" alt="active" class="line-active">
          
          <?php 
          $dots = ['bg-blue', 'bg-green', 'bg-yellow', 'bg-orange', 'bg-orange', 'bg-red'];
          foreach ($job_timeline_steps as $idx => $step): 
              $num = sprintf("%02d", $idx + 1);
              $dotClass = $dots[$idx % count($dots)];
          ?>
            <div class="step-wrapper step-<?php echo ($idx + 1); ?>">
              <div class="step-img-num"><img src="assets/img/vector/Group<?php echo ($idx + 1); ?>.png" alt="<?php echo $num; ?>"></div>
              <div class="icon-main"><img src="<?php echo htmlspecialchars($path_prefix . ($step['icon'] ?? "assets/img/vector/Grouptime".($idx+1).".png") . '?v=' . time()); ?>" alt=""></div>
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
        <?php foreach ($job_timeline_steps as $idx => $step): 
            $num = sprintf("%02d", $idx + 1);
        ?>
          <div class="m-step">
            <div class="m-number-box">
              <span class="m-num"><?php echo $num; ?></span>
            </div>
            <div class="m-content">
              <div class="m-header">
                <div class="m-icon"><img src="<?php echo htmlspecialchars($path_prefix . ($step['icon'] ?? "assets/img/vector/Grouptime".($idx+1).".png") . '?v=' . time()); ?>" alt=""></div>
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

  <!-- 5. education services start -->
  <section class="edu-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobServicesModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخدمات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="container">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars($job_services_title); ?></h2>
      <p class="mb-5 main-p"><?php echo htmlspecialchars($job_services_desc); ?></p>
      <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 text-center">
        <?php foreach ($job_services_items as $item): 
            $raw_url = $item['url'] ?? '#';
            $final_url = ($raw_url !== '#' && !str_starts_with($raw_url, 'http')) ? ($path_prefix ?? '') . $raw_url : $raw_url;
        ?>
          <div class="col">
            <a href="<?php echo htmlspecialchars($final_url); ?>" class="text-decoration-none">
              <div class="card service-card text-white border-0 rounded-5"
                style="background-image: url('<?php echo htmlspecialchars(($path_prefix ?? '') . ($item['img'] ?? '') . '?v=' . time()); ?>');">
                <div class="card-body d-flex align-items-end justify-content-center">
                  <h6 class="card-title m-0">
                    <?php echo htmlspecialchars($item['title'] ?? ''); ?>
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
// 6. استدعاء ملف مودالات التوظيف للأدمن
if (!empty($is_admin) && file_exists(__DIR__ . '/includes/admin_job_modals.php')) { 
    include_once __DIR__ . '/includes/admin_job_modals.php'; 
}

// 7. استدعاء الفوتر الأساسي
include_once 'includes/footer.php'; 
?>
