<?php
/**
 * صفحة خطاب الطلب - Cover Letter Page View
 */
?>
  <!-- Breadcrumb start-->
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
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medSpecBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($parent_url); ?>"><?php echo htmlspecialchars($parent_name); ?></a></li>
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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo htmlspecialchars(get_image_url($cover['hero_img'] ?? null, 'assets/img/education/servicesimg1.jpg')); ?>'); background-position: center -30px;">
      </div>
    </div>
</section>
<!-- custom-services end -->

<!-- custom-services-info start -->
<section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. العنوان والوصف -->
      <div class="head-info pb-4 mb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($cover['main_title'] ?? 'رسالة تعريف/خطاب طلب احترافي'); ?></h2>
        <?php if (!empty($cover['main_desc'])): ?>
            <p class="par-text"><?php echo nl2br(htmlspecialchars($cover['main_desc'])); ?></p>
        <?php endif; ?>
      </div>

      <!-- 2. النصائح -->
      <div class="advice-stars my-4 pb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverAdviceModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل النقاط والنصائح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars($cover['advice_title'] ?? 'النقاط التي يجب مراعاتها'); ?></h5>
        <div class="row star-list mt-4">
          <?php foreach (($cover['advice_points'] ?? []) as $point): ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-3">
              <div class="d-flex align-items-center">
                <img src="<?php echo get_image_url('assets/img/starList.svg.webp'); ?>" class="ms-2" alt="نجمة" width="25">
                <p class="mb-0"><?php echo htmlspecialchars($point); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. ملاحظات -->
      <div class="advice-check py-4 mb-4 " style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات الهامة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="note-text mb-4"><?php echo htmlspecialchars($cover['note_title'] ?? 'ملاحظات هامة !!'); ?></h5>
        <div class="row">
          <?php foreach (($cover['notes'] ?? []) as $note): ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-2"><p>✅ <?php echo htmlspecialchars($note); ?></p></div>
          <?php endforeach; ?>
        </div>
      </div>

<!-- 4. التحميل -->
<div class="row pt-2" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverDownloadModal" style="position: absolute; top: -10px; right: 10px; z-index: 10;" title="تعديل عناصر التحميل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php foreach (($cover['download_items'] ?? []) as $item): 
      $is_pdf = (strtolower($item['type'] ?? '') === 'pdf');
      $icon = $is_pdf ? 'assets/img/Grouppdf.webp' : 'assets/img/Groupword.webp';
  ?>
    <div class="col-lg-12">
      <div class="download-card mb-3">
        <div class="download-row">
          <!-- الأيقونة -->
          <img src="<?php echo get_image_url($icon); ?>" alt="icon" class="dl-icon" />
          
          <!-- العنوان والنص الفرعي -->
          <div class="dl-info">
            <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? ''); ?></div>
            <div class="dl-sub"><?php echo htmlspecialchars($item['sub'] ?? 'Example'); ?></div>
          </div>
          
          <!-- النقاط الفاصلة المرنة -->
          <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.......................................................................................................................................................................................................................</span>
          
          <!-- زر التحميل -->
          <a class="download-link" href="<?php echo htmlspecialchars($item['file'] ?? '#'); ?>" download>Download</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

    </div>
</section>
<!-- custom-services-info end -->

<?php 
    $cover_modals_file = __DIR__ . '/includes/admin_cover_modals.php';
    if (!empty($is_admin) && file_exists($cover_modals_file)) { 
        include_once $cover_modals_file; 
    }
?>
