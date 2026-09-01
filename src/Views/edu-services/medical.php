  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medSpecBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix); ?>job">التدريب المهني</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($medical_spec_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($medical_spec_data['page_breadcrumb'] ?? 'التخصصات الطبية'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medSpecHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars(get_image_url($medical_spec_data['hero_img'] ?? 'assets/img/job/servicesimg1.png')); ?>'); background-position: <?php echo htmlspecialchars($medical_spec_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- العنوان والوصف الرئيسي -->
      <div class="head-info pb-4 mb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medSpecMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($medical_spec_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($medical_spec_data['main_desc'] ?? '')); ?></p>
      </div>
      
      <!-- كرت التحميل -->
      <div class="dl-card py-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medSpecCardModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل ملفات التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row">
          <?php 
            $download_items = $medical_spec_data['download_items'] ?? [];
            // للتوافق مع البيانات القديمة إن وجدت كمصفوفة مفردة
            if (empty($download_items) && isset($medical_spec_data['download_item'])) {
                $download_items = [$medical_spec_data['download_item']];
            }
            $total_items = count($download_items);
            foreach ($download_items as $index => $item):
                $file_type = strtolower($item['type'] ?? 'pdf');
                $is_pdf = ($file_type === 'pdf');
                $icon_img = $is_pdf ? 'assets/img/Grouppdf.webp' : 'assets/img/Groupword.webp';
                $alt_text = $is_pdf ? 'ملف PDF' : 'ملف Word';
                $is_last = ($index === $total_items - 1);
          ?>
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="download-card <?php echo (!$is_last && $total_items > 1) ? 'mb-3' : ''; ?>">
                <div class="download-row">
                  <img src="<?php echo htmlspecialchars(get_image_url($icon_img)); ?>" alt="<?php echo htmlspecialchars($alt_text); ?>" class="dl-icon" />
                  <div class="dl-info">
                    <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? 'قائمة أكثر التخصصات الطبية انتشارا'); ?></div>
                    <div class="dl-sub"><?php echo htmlspecialchars($item['sub'] ?? 'اختر تخصصك الطبي'); ?></div>
                  </div>
                  <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.......................................................................................................................................................................................................................</span>
                  <a class="download-link" href="<?php echo htmlspecialchars($item['file'] ?? '#'); ?>" download>Download</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>


    </div>
  </section>
  <!-- custom-services-info end -->

  <?php
    $med_spec_modals_file = __DIR__ . '/includes/admin_medical_specialties_modals.php';
    if (!empty($is_admin) && file_exists($med_spec_modals_file)) { 
        include_once $med_spec_modals_file; 
    }
  ?>
