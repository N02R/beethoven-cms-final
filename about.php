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

// 1. تعريف ملفات الـ CSS والـ JS الخاصة بهذه الصفحة
$page_css = [
    'assets/css/swiper-bundle.min.css',
    'assets/css/about.css',
    'assets/css/responsive-about.css'
]; 

$page_js = [
    'assets/js/swiper-bundle.min.js'
];

$custom_script = '
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 20,
    rtl: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      576: { slidesPerView: 2 },
      992: { slidesPerView: 3 },
      1400: { slidesPerView: 4 },
      1800: { slidesPerView: 5 }
    },
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    }
  });
</script>';

// 2. استدعاء الهيدر الأساسي
include_once 'includes/header.php'; 

// 3. تجهيز متغيرات بيانات "عن الشركة" من $data
$ab       = $data['about'] ?? [];
$services = $data['services'] ?? [];
$counts   = $data['about_counts'] ?? [];
$team     = $data['team_members'] ?? [];
$partners = $data['partners_items'] ?? [];
?>

  <!-- 1. about start -->
  <section class="about py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#aboutEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل قسم من نحن">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6 order-2 order-lg-1">
          <h2 class="sec-title mb-3"><?php echo htmlspecialchars($ab['title'] ?? 'من نحن'); ?></h2>
          <p class="about-par mb-4"><?php echo htmlspecialchars($ab['desc'] ?? ''); ?></p>
          
          <div class="row g-3 mb-4">
            <!-- رؤية الشركة -->
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-body p-0">
                  <div class="title mb-2">
                    <span class="icon-wrap">
                      <img src="<?php echo htmlspecialchars($path_prefix . ($ab['vision_icon'] ?? 'assets/img/About us Icon, image/Company vision.svg') . '?v=' . time()); ?>" alt="رؤية الشركة">
                    </span>
                    <span><?php echo htmlspecialchars($ab['vision_title'] ?? 'رؤية الشركة'); ?></span>
                  </div>
                  <p class="card-text"><?php echo htmlspecialchars($ab['vision_desc'] ?? ''); ?></p>
                </div>
              </div>
            </div>

            <!-- رسالة الشركة -->
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-body p-0">
                  <div class="title mb-2">
                    <span class="icon-wrap">
                      <img src="<?php echo htmlspecialchars($path_prefix . ($ab['message_icon'] ?? 'assets/img/About us Icon, image/Company message.svg') . '?v=' . time()); ?>" alt="رسالة الشركة">
                    </span>
                    <span><?php echo htmlspecialchars($ab['message_title'] ?? 'رسالة الشركة'); ?></span>
                  </div>
                  <p class="card-text"><?php echo htmlspecialchars($ab['message_desc'] ?? ''); ?></p>
                </div>
              </div>
            </div>
          </div>

          <a href="<?php echo htmlspecialchars($ab['btn_url'] ?? '#'); ?>" class="btn btn-about">
            <?php echo htmlspecialchars($ab['btn_text'] ?? 'قراءة المزيد'); ?>
          </a>
        </div>

        <!-- صور القسم -->
        <div class="col-lg-6 order-1 order-lg-2 position-relative">
          <div class="image-stack">
            <img src="<?php echo htmlspecialchars($path_prefix . ($ab['main_img'] ?? 'assets/img/about us icon, image/about1.jpg') . '?v=' . time()); ?>" alt="Main About Image" class="img-fluid main-img">
            <div class="sub-img-wrapper">
              <img src="<?php echo htmlspecialchars($path_prefix . ($ab['sub_img'] ?? 'assets/img/about us icon, image/about2.png') . '?v=' . time()); ?>" alt="Sub About Image" class="img-fluid sub-img">
              <div class="dots-bg"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- about end -->

  <!-- 2. services start -->
  <section class="services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#servicesEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخدمات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <h2 class="mb-5 sec-title"><?php echo htmlspecialchars($data['services_section_title'] ?? 'خدماتنا المميزة'); ?></h2>
      <div class="row g-4">
        <?php foreach ($services as $service): ?>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <a href="<?php echo htmlspecialchars($service['url'] ?? '#'); ?>" class="card-link text-decoration-none">
              <div class="card" style="background: url('<?php echo htmlspecialchars($path_prefix . ($service['img'] ?? 'assets/img/home/education.jpg') . '?t=' . time()); ?>') no-repeat center/cover;">
                <div class="card-info">
                  <h3><?php echo htmlspecialchars($service['title'] ?? 'اسم الخدمة'); ?></h3>
                  <img src="assets/img/home/Arrow.svg" alt="Arrow">
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <!-- services end -->

  <!-- 3. team start -->
  <section class="team py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#teamEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل فريق العمل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="team-text mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($data['team_title'] ?? 'فريق العمل'); ?></h2>
        <p class="description main-p"><?php echo htmlspecialchars($data['team_desc'] ?? ''); ?></p>
      </div>
      <div class="swiper-container-wrapper">
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <?php if (!empty($team)): ?>
              <?php foreach ($team as $member): ?>
                <div class="swiper-slide">
                  <div class="team-card">
                    <img src="<?php echo htmlspecialchars($path_prefix . ($member['img'] ?? 'assets/img/team/member1.jpg') . '?v=' . time()); ?>" alt="<?php echo htmlspecialchars($member['name'] ?? ''); ?>" />
                    <div class="info">
                      <h5><?php echo htmlspecialchars($member['name'] ?? ''); ?></h5>
                      <p><?php echo htmlspecialchars($member['role'] ?? ''); ?></p>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-center w-100">لا يوجد أعضاء مضافون حالياً.</p>
            <?php endif; ?>
          </div>
        </div>
        <div class="swiper-nav-wrapper">
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
    </div>
  </section>
  <!-- team end -->

  <!-- 4. count start -->
  <section class="count" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#countsEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الإحصائيات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="row g-4">
        <?php foreach ($counts as $c): ?>
          <div class="col-lg-3 col-md-6">
            <div class="count-card">
              <div class="count-img">
                <img src="<?php echo htmlspecialchars($path_prefix . ($c['img'] ?? '') . '?v=' . time()); ?>" alt="icon">
              </div>
              <div class="count-info">
                <span><?php echo htmlspecialchars($c['number'] ?? ''); ?></span>
                <p><?php echo htmlspecialchars($c['title'] ?? ''); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <!-- count end -->

  <!-- 5. partenar start -->
  <section class="partenar py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#partnersEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الشركاء">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <h2 class="sec-title mb-5"><?php echo htmlspecialchars($data['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا'); ?></h2>
      <div class="row row-cols-2 row-cols-md-4 g-4 align-items-center justify-content-center">
        <?php foreach ($partners as $p): ?>
          <div class="col">
            <div class="partner-item">
              <img src="<?php echo htmlspecialchars($path_prefix . ($p['img'] ?? '') . '?v=' . time()); ?>" alt="Partner" class="img-fluid" />
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <!-- partenar end -->

<?php 
// 4. استدعاء ملف مودالات صفحة about للأدمن
if (!empty($is_admin) && file_exists(__DIR__ . '/includes/admin_about_modals.php')) { 
    include_once __DIR__ . '/includes/admin_about_modals.php'; 
}

// 5. استدعاء الفوتر الأساسي
include_once 'includes/footer.php'; 
?>
