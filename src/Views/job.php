<!-- 1. job start -->
<section class="job py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الهيرو">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
    $hero_bg = !empty($job_hero['img']) ? $path_prefix . ltrim($job_hero['img'], '/') . '?v=' . time() : $path_prefix . 'assets/img/job/hero.jpg';
    ?>
    <div class="row align-items-stretch g-5">
      <div class="col-lg-6">
        <div class="img-hero" style="background-image: url('<?php echo htmlspecialchars($hero_bg); ?>'); background-size: cover; background-position: center; min-height: 350px; border-radius: 20px;"></div>
      </div>
      <div class="col-lg-6">
        <div class="job-info pt-2">
          <h2 class="sec-title"><?php echo htmlspecialchars($job_hero['title'] ?? ''); ?></h2>
          <p class="main-p"><?php echo nl2br(htmlspecialchars($job_hero['desc'] ?? '')); ?></p>
          <a href="<?php echo htmlspecialchars($job_hero['btn_url'] ?? '#'); ?>" class="btn btn-job">
            <?php echo htmlspecialchars($job_hero['btn_text'] ?? 'ابدأ الآن'); ?>
          </a>
        </div>
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
      <h2 class="sec-title"><?php echo htmlspecialchars($job_why_title ?? ''); ?></h2>
      <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($job_why_desc ?? ''); ?></p>
    </div>
    <div class="row g-3">
      <?php foreach ($job_why_items as $item): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#">
                <img src="<?php echo htmlspecialchars($path_prefix . ltrim($item['img'] ?? '', '/') . '?v=' . time()); ?>" alt="icon" />
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

<!-- 3. program start -->
<section class="program py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobProgramModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل أنواع التدريب">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="mb-5">
      <h2 class="sec-title fw-bold"><?php echo htmlspecialchars($job_program_title ?? ''); ?></h2>
      <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($job_program_desc ?? ''); ?></p>
    </div>
    <div class="row g-4">
      <?php foreach ($job_program_types as $p): ?>
        <div class="col-md-6">
          <div class="program-info h-100 <?php echo !empty($p['is_dark']) ? 'highlight-box' : ''; ?>">
            <div class="program-content">
              <img src="<?php echo htmlspecialchars($path_prefix . ltrim($p['img'] ?? '', '/') . '?v=' . time()); ?>" alt="<?php echo htmlspecialchars($p['title'] ?? ''); ?>" class="mb-3">
              <h4 class="fw-bold mb-4 <?php echo !empty($p['is_dark']) ? 'text-white' : ''; ?>"><?php echo htmlspecialchars($p['title'] ?? ''); ?></h4>
              <p class="<?php echo !empty($p['is_dark']) ? 'text-white' : ''; ?>">
                <?php echo nl2br(htmlspecialchars($p['desc'] ?? '')); ?>
              </p>
            </div>

            <a href="<?php echo htmlspecialchars($p['btn_url'] ?? '#'); ?>" class="btn-info-wrapper mt-4 <?php echo !empty($p['is_dark']) ? 'is-light' : ''; ?>">
              <h3 class="mb-0"><?php echo htmlspecialchars($p['btn_text'] ?? 'اطلب الآن'); ?></h3>
              <div class="arrow-icon">
                <img src="<?php echo $path_prefix; ?>assets/img/home/Arrow.svg" alt="سهم">
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
      <h2 class="sec-title"><?php echo htmlspecialchars($job_timeline_title ?? ''); ?></h2>
      <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($job_timeline_desc ?? ''); ?></p>
    </div>
    
    <div class="map-container d-none d-lg-block">
      <div class="map-box">
        <img src="<?php echo htmlspecialchars($path_prefix); ?>assets/img/vector/Vector.png" alt="base" class="line-base">
        <img src="<?php echo htmlspecialchars($path_prefix); ?>assets/img/vector/Vector-1.png" alt="active" class="line-active">
        
        <?php 
        $dots = ['bg-blue', 'bg-green', 'bg-yellow', 'bg-orange', 'bg-orange', 'bg-red'];
        foreach ($job_timeline_steps as $idx => $step): 
            $num = sprintf("%02d", $idx + 1);
            $dotClass = $dots[$idx % count($dots)];
            $stepNumber = $idx + 1;
            
            $iconPath = !empty($step['icon']) 
                ? $path_prefix . ltrim($step['icon'], '/') 
                : $path_prefix . 'assets/img/vector/Grouptime' . $stepNumber . '.png';
                
            $groupNumImg = $path_prefix . 'assets/img/vector/Group' . $stepNumber . '.png';
            $stepNumberClass = 'step-' . $stepNumber;
        ?>
          <div class="step-wrapper <?php echo $stepNumberClass; ?>">
            <img src="<?php echo htmlspecialchars($groupNumImg); ?>" class="step-img-num" alt="<?php echo $num; ?>">
            <div class="icon-main">
              <img src="<?php echo htmlspecialchars($iconPath . '?v=' . time()); ?>" alt="">
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
      <?php foreach ($job_timeline_steps as $idx => $step): 
          $num = sprintf("%02d", $idx + 1);
          $stepNumber = $idx + 1;
          $iconPath = !empty($step['icon']) 
              ? $path_prefix . ltrim($step['icon'], '/') 
              : $path_prefix . 'assets/img/vector/Grouptime' . $stepNumber . '.png';
      ?>
        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num"><?php echo $num; ?></span>
          </div>
          <div class="m-content">
            <div class="m-header">
              <div class="m-icon">
                <img src="<?php echo htmlspecialchars($iconPath . '?v=' . time()); ?>" alt="">
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

<!-- 5. education services start -->
<section class="edu-services py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobServicesModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخدمات">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="container custom-container">
    <h2 class="sec-title mb-3"><?php echo htmlspecialchars($job_services_title ?? ''); ?></h2>
    <p class="mb-5 main-p" style="max-width: 700px;"><?php echo htmlspecialchars($job_services_desc ?? ''); ?></p>
    
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 text-center">
      <?php foreach ($job_services_items as $item): 
          $raw_url = $item['url'] ?? '#';
          $final_url = ($raw_url !== '#' && !str_starts_with($raw_url, 'http')) ? ($path_prefix ?? '/') . ltrim($raw_url, '/') : $raw_url;
      ?>
        <div class="col">
          <a href="<?php echo htmlspecialchars($final_url); ?>" class="text-decoration-none">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('<?php echo htmlspecialchars(($path_prefix ?? '/') . ltrim($item['img'] ?? '', '/') . '?v=' . time()); ?>');">
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
if (!empty($is_admin) && file_exists(__DIR__ . '/admin/admin_job_modals.php')) { 
    include_once __DIR__ . '/admin/admin_job_modals.php'; 
}
?>
