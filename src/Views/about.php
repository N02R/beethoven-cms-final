<?php
/**
 * صفحة من نحن - About Page View
 */
?>

<!-- 1. about start -->
<section class="about py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#aboutEditModal" title="تعديل قسم من نحن">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
    $ab = $data['about_section'] ?? ($data['about'] ?? []);
    $about_main_img = !empty($ab['main_img']) ? $ab['main_img'] . '?v=' . time() : 'assets/img/about us icon, image/about1.jpg';
    $about_sub_img = !empty($ab['sub_img']) ? $ab['sub_img'] . '?v=' . time() : 'assets/img/about us icon, image/about2.png';
    $vision_icon = !empty($ab['vision_icon']) ? $ab['vision_icon'] . '?v=' . time() : 'assets/img/About us Icon, image/Company vision.svg';
    $message_icon = !empty($ab['message_icon']) ? $ab['message_icon'] . '?v=' . time() : 'assets/img/About us Icon, image/Company message.svg';
    ?>
    <div class="row align-items-center g-5">
      <div class="col-lg-6 order-2 order-lg-1">
        <h2 class="sec-title mb-3"><?php echo htmlspecialchars($ab['title'] ?? 'من نحن'); ?></h2>
        <p class="about-par mb-4"><?php echo nl2br(htmlspecialchars($ab['desc'] ?? '')); ?></p>
        
        <div class="row g-3 mb-4">
          <!-- رؤية الشركة -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body p-0">
                <div class="title mb-2">
                  <span class="icon-wrap">
                    <img src="<?php echo htmlspecialchars($vision_icon); ?>" alt="رؤية الشركة">
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
                    <img src="<?php echo htmlspecialchars($message_icon); ?>" alt="رسالة الشركة">
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
          <img src="<?php echo htmlspecialchars($about_main_img); ?>" alt="Main About Image" class="img-fluid main-img">
          <div class="sub-img-wrapper">
            <img src="<?php echo htmlspecialchars($about_sub_img); ?>" alt="Sub About Image" class="img-fluid sub-img">
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
    <h2 class="mb-3 sec-title"><?php echo htmlspecialchars($data['services_section_title'] ?? 'خدماتنا المميزة'); ?></h2>
    
    <?php if (!empty($data['services_section_desc'])): ?>
        <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars($data['services_section_desc']); ?>
        </p>
    <?php endif; ?>

    <div class="row g-4">
      <?php 
      $services = $data['services'] ?? []; 
      foreach ($services as $service): 
      ?>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <a href="<?php echo htmlspecialchars($service['url'] ?? '#'); ?>" class="card-link text-decoration-none d-block">
            <div class="card" style="background: url('<?php echo htmlspecialchars(($service['img'] ?? 'assets/img/home/education.jpg') . '?t=' . time()); ?>') no-repeat center/cover;">
              <div class="card-info">
                <h3><?php echo htmlspecialchars($service['title'] ?? 'اسم الخدمة'); ?></h3>
                <img src="/assets/img/home/ArrowLink.svg.webp" alt="Arrow">
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
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#teamEditModal" title="تعديل فريق العمل">
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
          <?php 
          $team_members = $data['team_items'] ?? ($data['team_members'] ?? []);
          if (!empty($team_members)): 
          ?>
            <?php foreach ($team_members as $member): ?>
              <div class="swiper-slide">
                <div class="team-card">
                  <img src="<?php echo htmlspecialchars(($member['img'] ?? 'assets/img/team/member1.jpg') . '?v=' . time()); ?>" alt="<?php echo htmlspecialchars($member['name'] ?? ''); ?>" />
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
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#countsEditModal" title="تعديل الإحصائيات">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="row g-4">
      <?php foreach (($data['about_counts'] ?? []) as $c): ?>
        <div class="col-lg-3 col-md-6">
          <div class="count-card">
            <div class="count-img">
              <img src="<?php echo htmlspecialchars(($c['img'] ?? '') . '?v=' . time()); ?>" alt="icon">
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
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#partnersEditModal" title="تعديل الشركاء">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <h2 class="sec-title mb-5"><?php echo htmlspecialchars($data['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا'); ?></h2>
    <div class="row row-cols-2 row-cols-md-4 g-4 align-items-center justify-content-center">
      <?php foreach (($data['partners_items'] ?? []) as $p): ?>
        <div class="col">
          <div class="partner-item">
            <img src="<?php echo htmlspecialchars(($p['img'] ?? '') . '?v=' . time()); ?>" alt="Partner" class="img-fluid" />
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!-- partenar end -->

<?php 
// تضمين موديلات التعديل الخاصة بصفحة من نحن للمشرف فقط
$about_modals_file = __DIR__ . '/admin/admin_about_modals.php';
if (!empty($is_admin) && file_exists($about_modals_file)) { 
    include_once $about_modals_file; 
} 
?>
