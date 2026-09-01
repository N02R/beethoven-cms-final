  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>education">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($pricelist_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($pricelist_data['page_breadcrumb'] ?? 'قائمة أسعار الخدمات'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars(get_image_url($pricelist_data['hero_img'] ?? null, 'assets/img/education/servicesimg15.png')); ?>'); background-position: <?php echo htmlspecialchars($pricelist_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- العنوان والوصف الرئيسي -->
      <div class="head-info pb-4 mb-4  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>education">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($pricelist_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($pricelist_data['page_breadcrumb'] ?? 'قائمة أسعار الخدمات'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars(get_image_url($pricelist_data['hero_img'] ?? null, 'assets/img/education/servicesimg15.png')); ?>'); background-position: <?php echo htmlspecialchars($pricelist_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- العنوان والوصف الرئيسي -->
      <div class="head-info pb-4 mb-4 border-bottom" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($pricelist_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($pricelist_data['main_desc'] ?? '')); ?></p>
      </div>
      
      <!-- جدول/كرت التحميل -->
      <div class="dl-card py-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListCardModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل ملف التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row mt-3">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="download-card">
              <div class="download-row">
                <?php 
                  $item = $pricelist_data['download_item'] ?? [];
                  $file_type = strtolower($item['type'] ?? 'pdf');
                  if ($file_type === 'word' || $file_type === 'docx') {
                      $icon_img = 'assets/img/education/Groupword.png';
                      $alt_text = 'ملف Word';
                  } else {
                      $icon_img = 'assets/img/education/Grouppdf.png';
                      $alt_text = 'ملف PDF';
                  }
                ?>
                <!-- الأيقونة -->
                <img src="<?php echo htmlspecialchars(get_image_url($icon_img)); ?>" alt="<?php echo htmlspecialchars($alt_text); ?>" class="dl-icon" />
                
                <!-- العنوان -->
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? 'قائمة الأسعار العامة'); ?></div>
                </div>
                
                <!-- النقاط الفاصلة -->
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................</span>
                
                <!-- زر التحميل -->
                <a class="download-link" href="<?php echo htmlspecialchars(($path_prefix ?? '/') . ltrim($item['file'] ?? 'assets/files/general_price_list.pdf', '/')); ?>" download>Download</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

  <?php
    $pricelist_modals_file = __DIR__ . '/includes/admin_pricelist_modals.php';
    if (!empty($is_admin) && file_exists($pricelist_modals_file)) { 
        include_once $pricelist_modals_file; 
    }
  ?>
" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($pricelist_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($pricelist_data['main_desc'] ?? '')); ?></p>
      </div>
      
      <!-- جدول/كرت التحميل -->
      <div class="dl-card py-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListCardModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل ملف التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row mt-3">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="download-card">
              <div class="download-row">
                <?php 
                  $item = $pricelist_data['download_item'] ?? [];
                  $file_type = strtolower($item['type'] ?? 'pdf');
                  if ($file_type === 'word' || $file_type === 'docx') {
                      $icon_img = 'assets/img/Groupword.webp';
                      $alt_text = 'ملف Word';
                  } else {
                      $icon_img = 'assets/img/Grouppdf.webp';
                      $alt_text = 'ملف PDF';
                  }
                ?>
                <!-- الأيقونة -->
                <img src="<?php echo htmlspecialchars(get_image_url($icon_img)); ?>" alt="<?php echo htmlspecialchars($alt_text); ?>" class="dl-icon" />
                
                <!-- العنوان -->
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? 'قائمة الأسعار العامة'); ?></div>
                </div>
                
                <!-- النقاط الفاصلة -->
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.......................................................................................................................................................................................................................</span>
                
                <!-- زر التحميل -->
                <a class="download-link" href="<?php echo htmlspecialchars(($path_prefix ?? '/') . ltrim($item['file'] ?? 'assets/files/general_price_list.pdf', '/')); ?>" download>Download</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

  <?php
    $pricelist_modals_file = __DIR__ . '/includes/admin_pricelist_modals.php';
    if (!empty($is_admin) && file_exists($pricelist_modals_file)) { 
        include_once $pricelist_modals_file; 
    }
  ?>
