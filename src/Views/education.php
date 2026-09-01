<?php
/**
 * صفحة الدراسة في ألمانيا - Education Page View
 */
?>

<!-- 1. education start -->
<section class="education py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الهيرو">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
    $edu_hero = $data['edu_hero'] ?? [];
    $hero_bg = get_image_url($edu_hero['img'] ?? null, 'assets/img/education/hero.jpg');
    ?>
    <div class="row align-items-stretch g-5">
      <div class="col-lg-6">
        <div class="img-hero" style="background-image: url('<?php echo htmlspecialchars($hero_bg); ?>'); background-size: cover; background-position: center; min-height: 350px; border-radius: 20px;"></div>
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
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduWhyModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل لماذا الدراسة">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="mb-5">
      <h2 class="sec-title"><?php echo htmlspecialchars($data['edu_why_title'] ?? ''); ?></h2>
      <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($data['edu_why_desc'] ?? ''); ?></p>
    </div>
    <div class="row g-3">
      <?php foreach (($data['edu_why_items'] ?? []) as $item): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#">
                <img src="<?php echo get_image_url($item['img'] ?? null); ?>" alt="icon" />
              </a>
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
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduTimelineModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخطوات">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="mb-5">
      <h2 class="sec-title"><?php echo htmlspecialchars($data['edu_timeline_title'] ?? ''); ?></h2>
      <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($data['edu_timeline_desc'] ?? ''); ?></p>
    </div>
    
    <div class="map-container d-none d-lg-block">
      <div class="map-box">
        <img src="<?php echo get_image_url('assets/img/vector/Vector.png'); ?>" alt="base" class="line-base">
        <img src="<?php echo get_image_url('assets/img/vector/Vector-1.png'); ?>" alt="active" class="line-active">
        
        <?php 
        $dots = ['bg-blue', 'bg-green', 'bg-yellow', 'bg-orange', 'bg-orange', 'bg-red'];
        foreach (($data['edu_timeline_steps'] ?? []) as $idx => $step): 
            $num = sprintf("%02d", $idx + 1);
            $dotClass = $dots[$idx % count($dots)];
            
            // تحديد مسار الأيقونة الداخلية وحساب مسار الصورة الخلفية للرقم
            $defaultIcon = 'assets/img/vector/Grouptime' . ($idx + 1) . '.png';
            $iconPath = get_image_url($step['icon'] ?? null, $defaultIcon);
            $groupNumImg = get_image_url('assets/img/vector/Group' . ($idx + 1) . '.png');
            
            $stepNumberClass = 'step-' . ($idx + 1);
        ?>
          <div class="step-wrapper <?php echo $stepNumberClass; ?>">
            <img src="<?php echo htmlspecialchars($groupNumImg); ?>" class="step-img-num" alt="<?php echo $num; ?>">
            <div class="icon-main">
              <img src="<?php echo htmlspecialchars($iconPath); ?>" alt="">
            </div>
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
      <?php foreach (($data['edu_timeline_steps'] ?? []) as $idx => $step): 
          $num = sprintf("%02d", $idx + 1);
          $defaultIcon = 'assets/img/vector/Grouptime' . ($idx + 1) . '.png';
          $iconPath = get_image_url($step['icon'] ?? null, $defaultIcon);
      ?>
        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num"><?php echo $num; ?></span>
          </div>
          <div class="m-content">
            <div class="m-header">
              <div class="m-icon">
                <img src="<?php echo htmlspecialchars($iconPath); ?>" alt="">
              </div>
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
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduServicesModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل خدمات التعليم">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="container custom-container">
    <h2 class="sec-title mb-3"><?php echo htmlspecialchars($data['edu_services_title'] ?? ''); ?></h2>
    <p class="mb-5 main-p" style="max-width: 700px;"><?php echo htmlspecialchars($data['edu_services_desc'] ?? ''); ?></p>
    
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 text-center">
      <?php foreach (($data['edu_services_items'] ?? []) as $item): 
          $raw_url = trim($item['url'] ?? '#');
          
          if ($raw_url !== '#' && !str_starts_with($raw_url, 'http')) {
              $slug = ltrim($raw_url, '/');
              if (!str_starts_with($slug, 'edu-services/')) {
                  $slug = 'edu-services/' . $slug;
              }
              // إضافة معامل المصدر الخاص بالتعليم هنا
              $final_url = ($path_prefix ?? '') . $slug . '?from=education';
          } else {
              $final_url = $raw_url;
          }
          
          $bg_img = get_image_url($item['img'] ?? null);
      ?>

        <div class="col">
          <a href="<?php echo htmlspecialchars($final_url); ?>" class="text-decoration-none">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('<?php echo htmlspecialchars($bg_img); ?>');">
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
$edu_modals_file = __DIR__ . '/admin/admin_edu_modals.php';
if (!empty($is_admin) && file_exists($edu_modals_file)) { 
    include_once $edu_modals_file; 
}
?>
