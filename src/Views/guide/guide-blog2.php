<!-- Breadcrumb start-->
<?php
    $from = $_GET['from'] ?? 'education';
    
    if ($from === 'job') {
        $parent_url = ($path_prefix ?? '') . 'job';
        $parent_name = 'التدريب المهني';
    } else {
        $parent_url = ($path_prefix ?? '') . 'education';
        $parent_name = 'التعليم العالي';
    }
?>
<div class="custom-container pt-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb justify-content-start">
      <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '../'); ?>">الرئيسية</a></li>
      <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($parent_url); ?>"><?php echo htmlspecialchars($parent_name); ?></a></li>
      <li class="breadcrumb-item" aria-current="page">
        <a href="<?php echo htmlspecialchars($guide_data['page_breadcrumb_url'] ?? '#'); ?>">
          <?php echo htmlspecialchars($guide_data['page_breadcrumb'] ?? 'دليل بيتهوفن'); ?>
        </a>
      </li>
    </ol>
  </nav>
</div>
<!-- Breadcrumb end-->

<!-- custom-guide start-->
<section class="custom-services custom-guide py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>
  <div class="custom-container">
    <?php 
        $raw_hero_img = $guide_data['hero_img'] ?? '../assets/img/home/image(0).jpg';
        $hero_resolved_url = function_exists('get_image_url') ? get_image_url($raw_hero_img, '../assets/img/home/image(0).jpg') : $raw_hero_img;
        if (!preg_match('/^http/', $hero_resolved_url) && $hero_resolved_url[0] !== '/') {
            $hero_resolved_url = '/' . ltrim($hero_resolved_url, './');
        }
        $hero_resolved_url .= '?v=' . time();
    ?>
    <div class="custom-hero" style="background-image: url('<?php echo htmlspecialchars($hero_resolved_url, ENT_QUOTES, 'UTF-8'); ?>');">
    </div>
  </div>
</section>
<!-- custom-services end-->

<!-- custom-services-info start -->
<section class="custom-services-info py-5">
  <div class="custom-container">
    
    <!-- العنوان والوصف -->
    <div class="head-info" style="position: relative;">
      <?php if (!empty($is_admin)): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>
      <h2 class="main-text"><?php echo htmlspecialchars($guide_data['main_title'] ?? 'التعليم والعمل في ألمانيا: فرص جديدة لحياة أفضل'); ?></h2>
      <?php if (!empty($guide_data['main_desc'])): ?>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($guide_data['main_desc'])); ?></p>
      <?php endif; ?>
    </div>

    <!-- قسم الخدمات أو النقاط (ماذا تقدم لك بيتهوفن) -->
    <div class="advice-stars my-5" style="position: relative;">
      <?php if (!empty($is_admin)): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>
      <h5 class="mb-4 note-text"><?php echo htmlspecialchars($guide_data['services_advice_title'] ?? 'ماذا تقدم لك بيتهوفن سيتي للخدمات الطلابية؟'); ?></h5>
      <ul class="star-list">
        <?php foreach (($guide_data['services_advice_points'] ?? []) as $point): ?>
          <li>
            <p class="fw-bold">
              <img src="assets/img/starList.svg.webp" alt="" class="ms-2" width="25"/>
              <?php echo htmlspecialchars(is_array($point) ? ($point['title'] ?? '') : $point); ?>
            </p>
            <?php if (is_array($point) && !empty($point['desc'])): ?>
              <p><?php echo htmlspecialchars($point['desc']); ?></p>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<!-- why study start -->
<section class="study py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideWhyModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>
  <div class="custom-container">
    <div class="mb-5">
      <h2 class="sec-title"><?php echo htmlspecialchars($guide_data['why_title'] ?? 'لماذا الدراسة في ألمانيا؟'); ?></h2>
      <p class="main-p"><?php echo htmlspecialchars($guide_data['why_desc'] ?? 'إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي'); ?></p>
    </div>
    <div class="row g-3">
      <?php foreach (($guide_data['why_cards'] ?? []) as $card): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url($card['img'] ?? null, '../assets/img/education/edu-services1.png')); ?>" alt="" /></a>
              <h5 class="card-title"><?php echo htmlspecialchars($card['title'] ?? ''); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($card['text'] ?? ''); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!-- why study end -->

<!-- time line start -->
<section class="timeline-section py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideTimelineModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>
  <div class="custom-container">
    <div class="mb-5">
      <h2 class="sec-title"><?php echo htmlspecialchars($guide_data['timeline_title'] ?? 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS'); ?></h2>
      <p class="main-p"><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? 'نرشدك من أول استشارة حتى استقرارك في ألمانيا — إليك كيف تتم العملية معنا.'); ?></p>
    </div>

    <!-- Desktop Map Timeline -->
    <div class="map-container d-none d-lg-block">
      <div class="map-box">
        <img src="../assets/img/vector/Vector.png" alt="base" class="line-base">
        <img src="../assets/img/vector/Vector-1.png" alt="active" class="line-active">
        
        <?php 
          $steps = $guide_data['timeline_steps'] ?? [];
          foreach ($steps as $index => $step): 
              $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        ?>
          <div class="step-wrapper step-<?php echo $index + 1; ?>">
            <div class="step-img-num"><img src="<?php echo htmlspecialchars($step['num_img'] ?? "../assets/img/vector/Group" . ($index + 1) . ".png"); ?>" alt="<?php echo $num; ?>"></div>
            <div class="icon-main"><img src="<?php echo htmlspecialchars($step['icon'] ?? "../assets/img/vector/Grouptime" . ($index + 1) . ".png"); ?>" alt=""></div>
            <div class="info-content">
              <h3><?php echo htmlspecialchars($step['title'] ?? ''); ?></h3>
              <span class="dot <?php echo htmlspecialchars($step['dot_class'] ?? 'bg-blue'); ?>"></span>
              <h4><?php echo htmlspecialchars($step['subtitle'] ?? ''); ?></h4>
              <p><?php echo htmlspecialchars($step['desc'] ?? ''); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Mobile Timeline -->
    <div class="mobile-timeline d-lg-none">
      <?php foreach ($steps as $index => $step): 
          $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
      ?>
        <div class="m-step">
            <div class="m-number-box">
                <span class="m-num"><?php echo $num; ?></span>
            </div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="<?php echo htmlspecialchars($step['icon'] ?? "../assets/img/vector/Grouptime" . ($index + 1) . ".png"); ?>" alt=""></div>
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

<?php 
    $guide_modals_file = __DIR__ . '/includes/admin_guide_blog2_modals.php';
    if (!empty($is_admin) && file_exists($guide_modals_file)) { 
        include_once $guide_modals_file; 
    }
?>
