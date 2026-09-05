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
      <div class="custom-hero" style="background-image: url('<?php echo htmlspecialchars(get_image_url($guide_data['hero_img'] ?? 'assets/img/home/image(0).jpg')); ?>'); background-position: <?php echo htmlspecialchars($guide_data['hero_position'] ?? 'center center'); ?>;">
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
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="نجمة" class="ms-2" />
              <?php echo htmlspecialchars($guide_data['note_1_bold'] ?? ''); ?>
            </p>
            <p><span class="fw-bold">فصل الشتاء: </span><?php echo htmlspecialchars($guide_data['note_winter'] ?? ''); ?></p>
            <p><span class="fw-bold">فصل الصيف:</span><?php echo htmlspecialchars($guide_data['note_summer'] ?? ''); ?></p>
          </li>
          <li>
            <p class="fw-bold"> 
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="نجمة" class="ms-2" /> 
              <?php echo htmlspecialchars($guide_data['note_2_text'] ?? ''); ?>
            </p>
          </li>
          <li>
            <p class="fw-bold"> 
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="نجمة" class="ms-2" />
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
  <section class="study py-5">
    <div class="custom-container">
      <div class=" mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($guide_data['why_study_title'] ?? 'لماذا الدراسة في ألمانيا؟'); ?></h2>
        <p class="main-p"><?php echo htmlspecialchars($guide_data['why_study_desc'] ?? 'إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي'); ?></p>
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
                  ?>
                  <a href="#"><img src="<?php echo htmlspecialchars(get_image_url($card_img)); ?>" alt="" /></a>
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
  <section class="timeline-section py-5">
    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($guide_data['timeline_title'] ?? 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS'); ?></h2>
        <p class="main-p"><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? 'نرشدك من أول استشارة حتى استقرارك في ألمانيا — إليك كيف تتم العملية معنا.'); ?></p>
      </div>
      <div class="map-container d-none d-lg-block">
        <div class="map-box">
          <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector.png')); ?>" alt="base" class="line-base">
          <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector-1.png')); ?>" alt="active" class="line-active">
          
          <?php if (!empty($guide_data['timeline_steps']) && is_array($guide_data['timeline_steps'])): ?>
            <?php foreach ($guide_data['timeline_steps'] as $i => $step): ?>
              <?php $step_num = $i + 1; ?>
              <div class="step-wrapper step-<?php echo $step_num; ?>">
                <div class="step-img-num"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Group' . $step_num . '.png')); ?>" alt="0<?php echo $step_num; ?>"></div>
                <div class="icon-main"><img src="<?php echo htmlspecialchars(get_image_url($step['icon'] ?? 'assets/img/vector/Grouptime' . $step_num . '.png')); ?>" alt=""></div>
                <div class="info-content">
                  <h3><?php echo htmlspecialchars($step['title'] ?? ''); ?></h3>
                  <span class="dot <?php echo htmlspecialchars($step['dot_class'] ?? 'bg-blue'); ?>"></span>
                  <h4><?php echo htmlspecialchars($step['subtitle'] ?? ''); ?></h4>
                  <p><?php echo htmlspecialchars($step['desc'] ?? ''); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      
      <div class="mobile-timeline d-lg-none">
        <?php if (!empty($guide_data['timeline_steps']) && is_array($guide_data['timeline_steps'])): ?>
          <?php foreach ($guide_data['timeline_steps'] as $i => $step): ?>
            <?php 
              $step_num = $i + 1;
              $formatted_num = str_pad($step_num, 2, '0', STR_PAD_LEFT);
            ?>
            <div class="m-step">
                <div class="m-number-box"><span class="m-num"><?php echo $formatted_num; ?></span></div>
                <div class="m-content">
                    <div class="m-header">
                        <div class="m-icon"><img src="<?php echo htmlspecialchars(get_image_url($step['icon'] ?? 'assets/img/vector/Grouptime' . $step_num . '.png')); ?>" alt=""></div>
                        <h3><?php echo htmlspecialchars($step['title'] ?? ''); ?></h3>
                    </div>
                    <h4><?php echo htmlspecialchars($step['subtitle'] ?? ''); ?></h4>
                    <p><?php echo htmlspecialchars($step['desc'] ?? ''); ?></p>
                </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
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
