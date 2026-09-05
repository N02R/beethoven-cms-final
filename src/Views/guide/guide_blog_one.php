<?php
  // تحديد المصدر بناءً على الرابط القادم، مع وضع 'education' كقيمة افتراضية
  $from = $_GET['from'] ?? 'education';
  
  if ($from === 'job') {
      $parent_url = ($path_prefix ?? '') . 'job';
      $parent_name = 'التدريب المهني';
  } else {
      $parent_url = ($path_prefix ?? '') . 'education';
      $parent_name = 'التعليم العالي';
  }
?>

<!-- Breadcrumb start-->
<div class="custom-container pt-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb justify-content-start">
      <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>">الرئيسية</a></li>
      <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($parent_url); ?>"><?php echo htmlspecialchars($parent_name); ?></a></li>
      <li class="breadcrumb-item" aria-current="page">
        <a href="<?php echo htmlspecialchars($guide_data['page_breadcrumb_url'] ?? '#'); ?>">
          <?php echo htmlspecialchars($guide_data['page_breadcrumb'] ?? 'دليل الطالب'); ?>
        </a>
      </li>
    </ol>
  </nav>
</div>
<!-- Breadcrumb end-->

  <!-- custom-guide start-->
  <section class="custom-services custom-guide py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container ">
      <?php 
        $hero_img_path = $guide_data['hero_img'] ?? 'assets/img/home/image(0).jpg';
        $hero_full_path = public_path($hero_img_path);
        $hero_version = file_exists($hero_full_path) ? filemtime($hero_full_path) : time();
      ?>
      <div class="custom-hero" style="background-image: url('<?php echo htmlspecialchars(get_image_url($hero_img_path) . '?v=' . $hero_version); ?>'); background-position: <?php echo htmlspecialchars($guide_data['hero_position'] ?? 'center center'); ?>;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($guide_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($guide_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- الملاحظات الهامة -->
      <div class="advice-stars my-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-4 note-text"><?php echo htmlspecialchars($guide_data['notes_title'] ?? 'ملاحظات هامة جداً'); ?></h5>
        <ul class="star-list">
          <li>
            <p class="fw-bold">
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp')); ?>" alt="نجمة" class="ms-2" width="25"/>
              <?php echo htmlspecialchars($guide_data['note_1_bold'] ?? ''); ?>
            </p>
            <p><span class="fw-bold">فصل الشتاء: </span><?php echo htmlspecialchars($guide_data['note_winter'] ?? ''); ?></p>
            <p><span class="fw-bold">فصل الصيف:</span><?php echo htmlspecialchars($guide_data['note_summer'] ?? ''); ?></p>
          </li>
          <li>
            <p class="fw-bold"> 
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp')); ?>" alt="نجمة" class="ms-2" width="25" /> 
              <?php echo htmlspecialchars($guide_data['note_2_text'] ?? ''); ?>
            </p>
          </li>
          <li>
            <p class="fw-bold"> 
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp')); ?>" alt="نجمة" class="ms-2" width="25"/>
              <?php echo htmlspecialchars($guide_data['note_3_title'] ?? ''); ?>
            </p>
            <ul>
              <li>
                <p><?php echo htmlspecialchars($guide_data['faq_1'] ?? ''); ?></p>
              </li>
              <li>
                <p> 
                  <?php echo htmlspecialchars($guide_data['faq_2_prefix'] ?? '2. لمعرفة'); ?> 
                  <a href="<?php echo htmlspecialchars(($path_prefix ?? '/') . ltrim($guide_data['faq_2_url'] ?? 'contact', '/')); ?>" style="text-decoration: underline; color: #66aaee;"><?php echo htmlspecialchars($guide_data['faq_2_link_text'] ?? 'متطلبات تأشيرة الدراسة'); ?></a> 
                  <?php echo htmlspecialchars($guide_data['faq_2_suffix'] ?? ''); ?>
                </p>
              </li>
            </ul>
          </li>
        </ul>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

<!-- why study start -->
  <section class="study py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideWhyStudyModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل قسم لماذا الدراسة">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($guide_data['why_study_title'] ?? 'لماذا الدراسة في ألمانيا؟'); ?></h2>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($guide_data['why_study_desc'] ?? 'إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي'); ?></p>
      </div>
      <div class="row g-3">
        <?php if (!empty($guide_data['content_sections']) && is_array($guide_data['content_sections'])): ?>
          <?php foreach ($guide_data['content_sections'] as $index => $section): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
              <div class="card choose-card h-100">
                <div class="card-body">
                  <?php 
                    $default_img_num = ($index % 12) + 1;
                    $card_img = !empty($section['icon']) ? $section['icon'] : 'assets/img/education/edu-services' . $default_img_num . '.png';
                    
                    // منع التخزين المؤقت للأيقونات المحدثة
                    $card_img_full_path = public_path($card_img);
                    $card_img_version = file_exists($card_img_full_path) ? filemtime($card_img_full_path) : time();
                  ?>
                  <a href="#">
                    <img src="<?php echo htmlspecialchars(get_image_url($card_img) . '?v=' . $card_img_version); ?>" alt="icon" />
                  </a>
                  <h5 class="card-title"><?php echo htmlspecialchars($section['heading'] ?? ''); ?></h5>
                  <p class="card-text"><?php echo htmlspecialchars($section['body'] ?? ''); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <!-- why study end -->


  <!-- time line start -->
  <section class="timeline-section py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideTimelineModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخطوات الزمنية">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($guide_data['timeline_title'] ?? 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS'); ?></h2>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? 'نرشدك من أول استشارة حتى استقرارك في ألمانيا — إليك كيف تتم العملية معنا.'); ?></p>
      </div>
      
      <div class="map-container d-none d-lg-block">
        <div class="map-box">
          <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector.png')); ?>" alt="base" class="line-base">
          <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector-1.png')); ?>" alt="active" class="line-active">
          
          <?php 
          $dots = ['bg-blue', 'bg-green', 'bg-yellow', 'bg-orange', 'bg-orange', 'bg-red'];
          if (!empty($guide_data['timeline_steps']) && is_array($guide_data['timeline_steps'])): 
            foreach ($guide_data['timeline_steps'] as $idx => $step): 
              $num = sprintf("%02d", $idx + 1);
              $dotClass = !empty($step['dot_class']) ? $step['dot_class'] : $dots[$idx % count($dots)];
              
              $defaultIcon = 'assets/img/vector/Grouptime' . ($idx + 1) . '.png';
              $timeline_icon_src = $step['icon'] ?? $defaultIcon;
              $timeline_icon_full_path = public_path($timeline_icon_src);
              $timeline_icon_version = file_exists($timeline_icon_full_path) ? filemtime($timeline_icon_full_path) : time();
              
              $iconPath = get_image_url($timeline_icon_src, $defaultIcon);
              $groupNumImg = get_image_url('assets/img/vector/Group' . ($idx + 1) . '.png');
              
              $stepNumberClass = 'step-' . ($idx + 1);
          ?>
            <div class="step-wrapper <?php echo $stepNumberClass; ?>">
              <img src="<?php echo htmlspecialchars($groupNumImg); ?>" class="step-img-num" alt="<?php echo $num; ?>">
              <div class="icon-main">
                <img src="<?php echo htmlspecialchars($iconPath . '?v=' . $timeline_icon_version); ?>" alt="">
              </div>
              <div class="info-content">
                <h3><?php echo htmlspecialchars($step['title'] ?? ''); ?></h3>
                <span class="dot <?php echo htmlspecialchars($dotClass); ?>"></span>
                <h4><?php echo htmlspecialchars($step['subtitle'] ?? ''); ?></h4>
                <p><?php echo htmlspecialchars($step['desc'] ?? ''); ?></p>
              </div>
            </div>
          <?php 
            endforeach; 
          endif; 
          ?>
        </div>
      </div>
      
      <div class="mobile-timeline d-lg-none">
        <?php if (!empty($guide_data['timeline_steps']) && is_array($guide_data['timeline_steps'])): 
          foreach ($guide_data['timeline_steps'] as $idx => $step): 
            $num = sprintf("%02d", $idx + 1);
            $defaultIcon = 'assets/img/vector/Grouptime' . ($idx + 1) . '.png';
            $m_timeline_icon_src = $step['icon'] ?? $defaultIcon;
            $m_timeline_icon_full_path = public_path($m_timeline_icon_src);
            $m_timeline_icon_version = file_exists($m_timeline_icon_full_path) ? filemtime($m_timeline_icon_full_path) : time();
            
            $iconPath = get_image_url($m_timeline_icon_src, $defaultIcon);
        ?>
          <div class="m-step">
              <div class="m-number-box">
                <span class="m-num"><?php echo $num; ?></span>
              </div>
              <div class="m-content">
                  <div class="m-header">
                      <div class="m-icon">
                        <img src="<?php echo htmlspecialchars($iconPath . '?v=' . $m_timeline_icon_version); ?>" alt="">
                      </div>
                      <h3><?php echo htmlspecialchars($step['title'] ?? ''); ?></h3>
                  </div>
                  <h4><?php echo htmlspecialchars($step['subtitle'] ?? ''); ?></h4>
                  <p><?php echo htmlspecialchars($step['desc'] ?? ''); ?></p>
              </div>
          </div>
        <?php 
          endforeach; 
        endif; 
        ?>
      </div>
    </div>
  </section>
  <!-- time line end -->

  <?php
    $guide_modals_file = __DIR__ . '/includes/admin_guide_modals.php';
    if (!empty($is_admin) && file_exists($guide_modals_file)) { 
        include_once $guide_modals_file; 
    }
  ?>
