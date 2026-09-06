  <!-- custom-guide start-->
  <section class="custom-services custom-guide py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2HeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container ">
      <?php 
        $hero_img_path = $guide_blog2_data['hero_img'] ?? 'assets/img/guide/image (1).jpg';
        $hero_real_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($hero_img_path, '/');
        $hero_version = file_exists($hero_real_path) ? filemtime($hero_real_path) : time();
      ?>
      <div class="custom-hero" style="background-image: url('<?php echo htmlspecialchars(get_image_url($hero_img_path) . '?v=' . $hero_version); ?>'); background-position: <?php echo htmlspecialchars($guide_blog2_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2MainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($guide_blog2_data['main_title'] ?? ''); ?></h2>
        <p class="par-text">
          <?php echo nl2br(htmlspecialchars($guide_blog2_data['main_desc'] ?? '')); ?>
        </p>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

  <!-- why study start -->
  <section class="study py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2WhyModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل قسم لماذا التخصصات التقنية">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($guide_blog2_data['why_study_title'] ?? ($guide_blog2_data['why_title'] ?? '')); ?></h2>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($guide_blog2_data['why_study_desc'] ?? ($guide_blog2_data['why_subtitle'] ?? '')); ?></p>
      </div>
      <div class="row g-3">
        <?php if (!empty($guide_blog2_data['content_sections']) && is_array($guide_blog2_data['content_sections'])): ?>
          <?php foreach ($guide_blog2_data['content_sections'] as $index => $section): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
              <div class="card choose-card h-100">
                <div class="card-body">
                  <?php 
                    $default_img_num = ($index % 12) + 1;
                    $card_img = !empty($section['icon']) ? $section['icon'] : 'assets/img/education/edu-services' . $default_img_num . '.png';
                    
                    $card_img_real_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($card_img, '/');
                    $card_img_version = file_exists($card_img_real_path) ? filemtime($card_img_real_path) : time();
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

  <!-- notes & faq section start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="advice-check py-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2NotesModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الملاحظات والأسئلة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars($guide_blog2_data['notes_title'] ?? 'ملاحظات هامة جداً'); ?></h5>
        <div class="row">
          <?php if (!empty($guide_blog2_data['note_1_bold'])): ?>
            <div class="col-12 mb-3">
              <p><strong><?php echo htmlspecialchars($guide_blog2_data['note_1_bold']); ?></strong></p>
            </div>
          <?php endif; ?>
          <?php if (!empty($guide_blog2_data['note_winter'])): ?>
            <div class="col-12 mb-2">
              <p>❄️ <?php echo htmlspecialchars($guide_blog2_data['note_winter']); ?></p>
            </div>
          <?php endif; ?>
          <?php if (!empty($guide_blog2_data['note_summer'])): ?>
            <div class="col-12 mb-2">
              <p>☀️ <?php echo htmlspecialchars($guide_blog2_data['note_summer']); ?></p>
            </div>
          <?php endif; ?>
          <?php if (!empty($guide_blog2_data['note_2_text'])): ?>
            <div class="col-12 mb-2">
              <p><?php echo htmlspecialchars($guide_blog2_data['note_2_text']); ?></p>
            </div>
          <?php endif; ?>
          <?php if (!empty($guide_blog2_data['note_3_title'])): ?>
            <div class="col-12 mb-2">
              <p>📌 <strong><?php echo htmlspecialchars($guide_blog2_data['note_3_title']); ?></strong></p>
            </div>
          <?php endif; ?>
          <?php if (!empty($guide_blog2_data['faq_1'])): ?>
            <div class="col-12 mb-2">
              <p><?php echo htmlspecialchars($guide_blog2_data['faq_1']); ?></p>
            </div>
          <?php endif; ?>
          <?php if (!empty($guide_blog2_data['faq_2_prefix']) || !empty($guide_blog2_data['faq_2_link_text'])): ?>
            <div class="col-12 mt-2">
              <p>
                <?php echo htmlspecialchars($guide_blog2_data['faq_2_prefix'] ?? ''); ?>
                <a href="<?php echo htmlspecialchars($guide_blog2_data['faq_2_url'] ?? 'contact'); ?>" class="text-primary text-decoration-underline">
                  <?php echo htmlspecialchars($guide_blog2_data['faq_2_link_text'] ?? ''); ?>
                </a>
                <?php echo htmlspecialchars($guide_blog2_data['faq_2_suffix'] ?? ''); ?>
              </p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  <!-- notes & faq section end -->

  <!-- time line start -->
  <section class="timeline-section py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2TimelineModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخطوات الزمنية">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($guide_blog2_data['timeline_title'] ?? 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS'); ?></h2>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($guide_blog2_data['timeline_desc'] ?? 'نرشدك من أول استشارة حتى استقرارك في ألمانيا — إليك كيف تتم العملية معنا.'); ?></p>
      </div>
      
      <div class="mobile-timeline">
        <?php if (!empty($guide_blog2_data['timeline_steps']) && is_array($guide_blog2_data['timeline_steps'])): 
          foreach ($guide_blog2_data['timeline_steps'] as $idx => $step): 
            $num = sprintf("%02d", $idx + 1);
        ?>
          <div class="m-step">
            <div class="m-number-box"><span class="m-num"><?php echo $num; ?></span></div>
            <div class="m-content">
              <h4><?php echo htmlspecialchars($step['title'] ?? ''); ?></h4>
              <?php if (!empty($step['subtitle'])): ?>
                <span class="text-muted d-block mb-1 small"><?php echo htmlspecialchars($step['subtitle']); ?></span>
              <?php endif; ?>
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
    $guide_blog2_modals_file = __DIR__ . '/includes/admin_guide_blog2_modals.php';
    if (!empty($is_admin) && file_exists($guide_blog2_modals_file)) { 
        include_once $guide_blog2_modals_file; 
    }
  ?>
