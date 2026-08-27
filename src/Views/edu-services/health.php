  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($health_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($health_data['page_breadcrumb'] ?? 'التأمين الصحي'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الهيدر والصورة">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="health-hero custom-hero" style="background-image: url('<?php echo get_image_url($health_data['hero_img'] ?? null, 'assets/img/education/servicesimg6.png'); ?>');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

<!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. قسم العنوان الرئيسي والوصف -->
      <div class="head-info pb-4 mb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthMainTitleModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($health_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($health_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. قسم النصائح والإرشادات (لماذا التأمين الصحي مهم؟) -->
      <div class="advice-check py-4 mb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthTipsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل أهمية التأمين الصحي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($health_data['advice_title'] ?? 'لماذا التأمين الصحي مهم؟'); ?></h5>
        <div class="row">
          <?php foreach (($health_data['tips'] ?? []) as $tip): ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
              <p>✅ <?php echo htmlspecialchars($tip); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. قسم الوثائق المكملة -->
      <div class="advice-stars my-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الوثائق المكملة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($health_data['note_title'] ?? 'الوثائق المكملة'); ?></h5>
        <ul class="star-list">
          <div class="row">
            <?php foreach (($health_data['notes'] ?? []) as $note): ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                <li>
                  <p>
                    <img src="<?php echo get_image_url('assets/img/starList.svg.webp'); ?>" alt="" class="ms-2" width="25"/>
                    <?php echo $note; ?>
                  </p>
                </li>
              </div>
            <?php endforeach; ?>
          </div>
        </ul>
      </div>

      <!-- 4 & 5. قسم الوصف التمهيدي وروابط حجز الشركات (مدمج معاً بزر تعديل واحد) -->
      <div style="position: relative;" class="pt-2">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthLinksModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل وصف وروابط الحجز">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <!-- الوصف التمهيدي لروابط الحجز -->
        <div class="mb-4">
          <p class="advice-text mt-4"><?php echo nl2br(htmlspecialchars($health_data['intro_desc'] ?? '')); ?></p>
        </div>

        <!-- روابط حجز الشركات (Dr.WALTER & FINTIBA أو الروابط المدارة) -->
        <?php if (!empty($health_data['links'] ?? []) && is_array($health_data['links'])): ?>
          <?php foreach ($health_data['links'] as $link_item): ?>
          <?php
              $is_active_link = !empty($link_item['active']);
              $link_class = $is_active_link ? 'link active mt-4' : 'link mt-4';
            ?>
            <div class="<?php echo $link_class; ?>">
              <p class="text-center">
                <?php if (!empty($link_item['url'])): ?>
                  <a href="<?php echo htmlspecialchars($link_item['url']); ?>" target="_blank" class="text-decoration-none text-inherit" style="color: inherit;">
                    <?php echo htmlspecialchars($link_item['text'] ?? ''); ?>
                  </a>
                <?php else: ?>
                  <?php echo htmlspecialchars($link_item['text'] ?? ''); ?>
                <?php endif; ?>
              </p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->


<?php
    $health_modals_file = __DIR__ . '/includes/admin_health_modals.php';
    if (!empty($is_admin) && file_exists($health_modals_file)) { 
        include_once $health_modals_file; 
    }
?>
