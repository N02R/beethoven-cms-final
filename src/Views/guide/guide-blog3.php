  <!-- custom-guide start-->
  <section class="custom-services custom-guide py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog3HeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container ">
      <div class="custom-hero" style="background-image: url('<?php echo htmlspecialchars(get_image_url($guide_blog3_data['hero_img'] ?? 'assets/img/guide/image.jpg')); ?>'); background-position: <?php echo htmlspecialchars($guide_blog3_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog3MainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($guide_blog3_data['main_title'] ?? ''); ?></h2>
        <p class="par-text">
          <?php echo nl2br(htmlspecialchars($guide_blog3_data['main_desc'] ?? '')); ?>
        </p>
      </div>

      <div class="advice-stars" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog3DiffModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل مميزات المعالجة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($guide_blog3_data['diff_title'] ?? ''); ?></h5>
        <ul class="star-list">
          <li>
            <p><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="" class="ms-2" /><span class="fw-bold"><?php echo htmlspecialchars($guide_blog3_data['diff_1_bold'] ?? ''); ?></span>
              <?php echo htmlspecialchars($guide_blog3_data['diff_1_text'] ?? ''); ?></p>
          </li>
          <li>
            <p> <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="" class="ms-2" /><span class="fw-bold"><?php echo htmlspecialchars($guide_blog3_data['diff_2_bold'] ?? ''); ?></span>
               <?php echo htmlspecialchars($guide_blog3_data['diff_2_text'] ?? ''); ?></p>
          </li>
          <li>
            <p> <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="" class="ms-2" /><span class="fw-bold"><?php echo htmlspecialchars($guide_blog3_data['diff_3_bold'] ?? ''); ?></span>
              <?php echo htmlspecialchars($guide_blog3_data['diff_3_text'] ?? ''); ?></p>
          </li>
          <li>
            <p> <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="" class="ms-2" /><span class="fw-bold"><?php echo htmlspecialchars($guide_blog3_data['diff_4_bold'] ?? ''); ?></span>
              <?php echo htmlspecialchars($guide_blog3_data['diff_4_text'] ?? ''); ?></p>
          </li>
        </ul>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

  <!-- time line start -->
  <section class="timeline-section py-5">
    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title"><?php echo htmlspecialchars($guide_blog3_data['timeline_title'] ?? ''); ?></h2>
        <p class="main-p"><?php echo htmlspecialchars($guide_blog3_data['timeline_subtitle'] ?? ''); ?></p>
      </div>
      <div class="mobile-timeline">

        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num">01</span>
          </div>
          <div class="m-content">
            <h4>استشارة مبدئية مجانية</h4>
            <p>تحديد التخصصات والفرص المناسبة لمؤهلاتك ورغباتك.</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num">02</span>
          </div>
          <div class="m-content">
            <h4>تجهيز المستندات</h4>
            <p>نرشدك لترجمة وتصديق أوراقك وتجهيز السيرة الذاتية وخطاب الدافع باللغة الألمانية</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num">03</span>
          </div>
          <div class="m-content">
            <h4>تعلم اللغة الالمانية</h4>
            <p>نساعدك في اختيار البرامج المناسبة للوصول لمستوى اللغة المطلوب (B1 أو B2 غالبًا).</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num">04</span>
          </div>
          <div class="m-content">
            <h4>التقديم على الفرص المناسبة</h4>
            <p>نقوم بالتقديم على المعاهد المهنية أو الجامعات المناسبة لك، ومتابعة طلبك خطوة بخطوة</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num">05</span>
          </div>
          <div class="m-content">
            <h4>القبول والتجهيز للسفر</h4>
            <p>نساعدك في الحصول على القبول وتحديد المواعيد لدى السفارة والتجهيز للسفر والإقامة في ألمانيا</p>
          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- time line end -->

  <!-- contact-guide start -->
  <section class="contact-guide py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog3ContactModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل قسم التواصل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text"><?php echo htmlspecialchars($guide_blog3_data['contact_title'] ?? ''); ?></h2>
        <p class="par-text">
          <?php echo htmlspecialchars($guide_blog3_data['contact_text_prefix'] ?? ''); ?>
          <a href="<?php echo htmlspecialchars(($path_prefix ?? '/') . ltrim($guide_blog3_data['contact_url'] ?? 'contact', '/')); ?>" style="color: #66aaee;" class="fw-bold">
            <?php echo htmlspecialchars($guide_blog3_data['contact_link_text'] ?? 'تواصل معنا'); ?>
          </a>
        </p>
      </div>
    </div>
  </section>
  <!-- contact-guide end -->

  <?php
    $guide_blog3_modals_file = __DIR__ . '/includes/admin_guide_blog3_modals.php';
    if (!empty($is_admin) && file_exists($guide_blog3_modals_file)) { 
        include_once $guide_blog3_modals_file; 
    }
  ?>
