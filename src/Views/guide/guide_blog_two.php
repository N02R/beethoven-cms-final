  <!-- custom-guide start-->
  <section class="custom-services custom-guide py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container ">
      <?php 
        $hero_img_path = $guide_data['hero_img'] ?? 'assets/img/guide/image (1).jpg';
        $hero_real_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($hero_img_path, '/');
        $hero_version = file_exists($hero_real_path) ? filemtime($hero_real_path) : time();
      ?>
      <div class="custom-hero" style="background-image: url('<?php echo htmlspecialchars(get_image_url($hero_img_path) . '?v=' . $hero_version); ?>'); background-position: <?php echo htmlspecialchars($guide_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($guide_data['main_title'] ?? ''); ?></h2>
        <p class="par-text">
          <?php echo nl2br(htmlspecialchars($guide_data['main_desc'] ?? '')); ?>
        </p>
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
        <h2 class="sec-title"><?php echo htmlspecialchars($guide_data['why_study_title'] ?? ''); ?></h2>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($guide_data['why_study_desc'] ?? ''); ?></p>
      </div>
      <div class="row g-3">
        <?php if (!empty($guide_data['content_sections']) && is_array($guide_data['content_sections'])): ?>
          <?php foreach ($guide_data['content_sections'] as $index => $section): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
              <div class="card choose-card h-100">
                <div class="card-body">
                  <?php 
                    $card_img = !empty($section['icon']) ? $section['icon'] : 'assets/img/education/edu-services1.png';
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

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideAdviceModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخدمات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="advice-check py-5">
        <h5 class="advice-text"><?php echo htmlspecialchars($guide_data['advice_title'] ?? ''); ?></h5>
        <div class="row">
          <?php if (!empty($guide_data['advice_items']) && is_array($guide_data['advice_items'])): ?>
            <?php foreach ($guide_data['advice_items'] as $item): ?>
              <div class="col-lg-6 col-md-6 col-sm-12">
                <p><?php echo htmlspecialchars($item); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end-->

  <!-- time line start -->
  <section class="timeline-section py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideTimelineModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخطوات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($guide_data['timeline_title'] ?? ''); ?></h2>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? ''); ?></p>
      </div>
      <div class="mobile-timeline">
        <?php if (!empty($guide_data['timeline_steps']) && is_array($guide_data['timeline_steps'])): 
          foreach ($guide_data['timeline_steps'] as $idx => $step): 
            $num = sprintf("%02d", $idx + 1);
        ?>
          <div class="m-step">
            <div class="m-number-box">
              <span class="m-num"><?php echo $num; ?></span>
            </div>
            <div class="m-content">
              <h4><?php echo htmlspecialchars($step['title'] ?? ''); ?></h4>
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

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideNotesModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الضمانات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="advice-stars my-5">
        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($guide_data['guarantee_title'] ?? ''); ?></h5>
        <ul class="star-list">
          <?php if (!empty($guide_data['guarantees_list']) && is_array($guide_data['guarantees_list'])): ?>
            <?php foreach ($guide_data['guarantees_list'] as $item): ?>
              <li>
                <p>
                  <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="" class="ms-2" />
                  <span class="fw-bold"><?php echo htmlspecialchars($item['bold'] ?? ''); ?>: </span>
                  <?php echo htmlspecialchars($item['text'] ?? ''); ?>
                </p>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

  <?php
    $guide_modals_file = __DIR__ . '/includes/admin_guide_two_modals.php';
    if (!empty($is_admin) && file_exists($guide_modals_file)) { 
        include_once $guide_modals_file; 
    }
  ?>
