<?php 
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

$path_prefix = ''; 

// 1. استدعاء الهيدر الأساسي
include_once 'includes/header.php'; 

// تعريف ملفات الـ CSS والـ JS الخاصة بهذه الصفحة
$page_css = [
    '/assets/css/swiper-bundle.min.css',
    '/assets/css/about.css',
    '/assets/css/responsive-about.css'
]; 

$page_js = [
    '/assets/js/swiper-bundle.min.js'
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

// تجهيز بيانات صفحة عن الشركة من مصفوفة $data
$ab = $data['about'] ?? [];
?>

  <!-- about start -->
  <section class="about py-5" style="position: relative;">
    <?php if ($is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#aboutEditModal" title="تعديل قسم من نحن" style="position: absolute; top: 15px; right: 20px; z-index: 10;">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6 order-2 order-lg-1">
          <h2 class="sec-title mb-3"><?php echo htmlspecialchars($ab['title'] ?? 'من نحن'); ?></h2>
          <p class="about-par mb-4">
            <?php echo nl2br(htmlspecialchars($ab['desc'] ?? 'شركة بيتهوفن سيتي للخدمات (BCS)...')); ?>
          </p>
          
          <div class="row g-3 mb-4">
            <!-- رؤية الشركة -->
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-body p-0">
                  <div class="title mb-2">
                    <span class="icon-wrap">
                      <img src="<?php echo $path_prefix . ($ab['vision_icon'] ?? 'assets/img/About us Icon, image/Company vision.svg') . '?v=' . time(); ?>" alt="رؤية الشركة">
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
                      <img src="<?php echo $path_prefix . ($ab['message_icon'] ?? 'assets/img/About us Icon, image/Company message.svg') . '?v=' . time(); ?>" alt="رسالة الشركة">
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

        <div class="col-lg-6 order-1 order-lg-2 position-relative">
          <div class="image-stack">
            <img src="<?php echo $path_prefix . ($ab['main_img'] ?? 'assets/img/about us icon, image/about1.jpg') . '?v=' . time(); ?>" alt="عمل جماعي" class="img-fluid main-img">
            <div class="sub-img-wrapper">
              <img src="<?php echo $path_prefix . ($ab['sub_img'] ?? 'assets/img/about us icon, image/about2.png') . '?v=' . time(); ?>" alt="Beethoven City" class="img-fluid sub-img">
              <div class="dots-bg"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- about end -->

  <!-- services start -->
  <section class="services py-5" style="position: relative;">
    <?php if ($is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#servicesEditModal" title="تعديل الخدمات" style="position: absolute; top: 15px; right: 20px; z-index: 10;">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <h2 class="mb-5 sec-title"><?php echo htmlspecialchars($data['services_section_title'] ?? 'خدماتنا المميزة'); ?></h2>
      <div class="row g-4">
        <?php 
        $services = $data['services'] ?? []; 
        if (!empty($services)):
          foreach ($services as $service): 
        ?>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <a href="<?php echo htmlspecialchars($service['url'] ?? '#'); ?>" class="card-link text-decoration-none d-block">
              <div class="card" style="background: url('<?php echo ($service['img'] ?? 'assets/img/home/default.jpg') . '?t=' . time(); ?>') no-repeat center/cover;">
                <div class="card-info">
                  <h3><?php echo htmlspecialchars($service['title'] ?? 'عنوان الخدمة'); ?></h3>
                  <img src="assets/img/home/Arrow.svg" alt="Arrow">
                </div>
              </div>
            </a>
          </div>
        <?php 
          endforeach;
        else:
        ?>
          <!-- عرض أفتراضي في حال كانت القائمة فارغة -->
          <div class="col-lg-6 col-md-6 col-sm-12">
            <a href="education.php" class="card-link text-decoration-none">
              <div class="card" style="background: url('assets/img/home/education.jpg') no-repeat center/cover;">
                <div class="card-info">
                  <h3>التعليم العالي والجامعي</h3>
                  <img src="assets/img/home/Arrow.svg" alt="Arrow">
                </div>
              </div>
            </a>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <a href="job.php" class="card-link text-decoration-none">
              <div class="card" style="background: url('assets/img/home/traning.jpg') no-repeat center/cover;">
                <div class="card-info">
                  <h3>التأهيل المهني والعملي</h3>
                  <img src="assets/img/home/Arrow.svg" alt="Arrow">
                </div>
              </div>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- services end -->

  <!-- team start -->
  <section class="team py-5" style="position: relative;">
    <?php if ($is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#teamEditModal" title="تعديل فريق العمل" style="position: absolute; top: 15px; right: 20px; z-index: 10;">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="team-text mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($data['team_title'] ?? 'فريق العمل'); ?></h2>
        <p class="description main-p">
          <?php echo htmlspecialchars($data['team_desc'] ?? 'يسر فريق العمل تقديم خدمات عالية الجودة لتتناسب مع احتياجات العميل الكريم وتوقعاته.'); ?>
        </p>
      </div>
      
      <div class="swiper-container-wrapper">
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <?php 
            $team = $data['team_members'] ?? [];
            if (!empty($team)):
              foreach ($team as $member):
            ?>
              <div class="swiper-slide">
                <div class="team-card">
                  <img src="<?php echo $path_prefix . ($member['img'] ?? 'assets/img/team/member1.jpg') . '?v=' . time(); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" />
                  <div class="info">
                    <h5><?php echo htmlspecialchars($member['name']); ?></h5>
                    <p><?php echo htmlspecialchars($member['role']); ?></p>
                  </div>
                </div>
              </div>
            <?php 
              endforeach;
            else:
            ?>
              <!-- عناصر افتراضية -->
              <div class="swiper-slide">
                <div class="team-card">
                  <img src="assets/img/team/member1.jpg" alt="أحمد السالم" />
                  <div class="info">
                    <h5>أحمد السالم</h5>
                    <p>مدير المشروع</p>
                  </div>
                </div>
              </div>
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

  <!-- count start -->
  <section class="count" style="position: relative;">
    <?php if ($is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#countsEditModal" title="تعديل العدادات" style="position: absolute; top: 15px; right: 20px; z-index: 10;">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="row g-4">
        <?php 
        $counts = $data['about_counts'] ?? [];
        if (!empty($counts)):
          foreach ($counts as $c):
        ?>
          <div class="col-lg-3 col-md-6">
            <div class="count-card">
              <div class="count-img">
                <img src="<?php echo $path_prefix . ($c['img'] ?? 'assets/img/about us icon, image/Program Education.svg') . '?v=' . time(); ?>" alt="Icon">
              </div>
              <div class="count-info">
                <span><?php echo htmlspecialchars($c['number']); ?></span>
                <p><?php echo htmlspecialchars($c['title']); ?></p>
              </div>
            </div>
          </div>
        <?php 
          endforeach;
        else:
        ?>
          <!-- العدادات الافتراضية -->
          <div class="col-lg-3 col-md-6">
            <div class="count-card">
              <div class="count-img"><img src="assets/img/about us icon, image/Program Education.svg" alt="Programs"></div>
              <div class="count-info"><span>700K+</span><p>برنامج تعليمي</p></div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="count-card">
              <div class="count-img"><img src="assets/img/about us icon, image/Course Education.svg" alt="Courses"></div>
              <div class="count-info"><span>500+</span><p>كورس لغة ألمانية</p></div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="count-card">
              <div class="count-img"><img src="assets/img/about us icon, image/Educational institute.svg" alt="Institutes"></div>
              <div class="count-info"><span>300+</span><p>معهد تعليمي</p></div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="count-card">
              <div class="count-img"><img src="assets/img/about us icon, image/Experience Year.svg" alt="Experience"></div>
              <div class="count-info"><span>6Y+</span><p>سنوات خبرة</p></div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- count end -->

  <!-- partenar start -->
  <section class="partenar py-5" style="position: relative;">
    <?php if ($is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#partnersEditModal" title="تعديل الشركاء" style="position: absolute; top: 15px; right: 20px; z-index: 10;">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <h2 class="sec-title mb-5"><?php echo htmlspecialchars($data['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا'); ?></h2>
      <div class="row row-cols-2 row-cols-md-4 g-4 align-items-center justify-content-center">
        <?php 
        $partners = $data['partners_items'] ?? [];
        if (!empty($partners)):
          foreach ($partners as $partner):
        ?>
          <div class="col">
            <div class="partner-item">
              <img src="<?php echo $path_prefix . ($partner['img'] ?? '') . '?v=' . time(); ?>" alt="Partner" class="img-fluid" />
            </div>
          </div>
        <?php 
          endforeach;
        else:
        ?>
          <!-- شركاء أفتراضيين -->
          <div class="col"><div class="partner-item"><img src="assets/img/about us icon, image/image 9.jpg" alt="Partner" class="img-fluid" /></div></div>
          <div class="col"><div class="partner-item"><img src="assets/img/about us icon, image/image 2.jpg" alt="Partner" class="img-fluid" /></div></div>
          <div class="col"><div class="partner-item"><img src="assets/img/about us icon, image/image 3.jpg" alt="Partner" class="img-fluid" /></div></div>
          <div class="col"><div class="partner-item"><img src="assets/img/about us icon, image/image 5.jpg" alt="Partner" class="img-fluid" /></div></div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- partenar end -->

<?php 
// 2. استدعاء المودالات الخاصة بصفحة About إذا كان المستخدم مسؤول
if ($is_admin) {
    include_once 'includes/admin_about_modals.php';
}

// 3. استدعاء الفوتر الموحد
include_once 'includes/footer.php'; 
?>
