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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($parent_url); ?>"><?php echo htmlspecialchars($parent_name); ?></a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($medical_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($medical_data['page_breadcrumb'] ?? 'باقة التدريب الطبي'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->


  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars(get_image_url($medical_data['hero_img'] ?? 'assets/img/job/servicesimg3.png')); ?>'); background-position: <?php echo htmlspecialchars($medical_data['hero_position'] ?? 'center center'); ?>;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($medical_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($medical_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- ملاحظات هامة -->
      <div class="advice-stars pt-3 pb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h5 class="note-text">ملاحظات هامة !!</h5>
        <ul class="star-list">
          <li>
            <p>
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp')); ?>" alt="تنبيه" class="ms-2" width="25"/>
              <?php 
                $note_text = $medical_data['note_text'] ?? '';
                $safe_note = htmlspecialchars($note_text);
                $note_text_formatted = str_replace('بالتواصل معنا', '<a href="' . htmlspecialchars(($path_prefix ?? '/') . 'contact') . '" class="fw-bold" style="color: #66aeee; text-decoration: none;">بالتواصل معنا</a>', $safe_note);
                echo $note_text_formatted;
              ?>
            </p>
          </li>
        </ul>
      </div>

<!-- كرت التحميل -->
<div class="dl-card py-4" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalCardModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل ملف التحميل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <?php 
        $download_items = $medical_data['download_items'] ?? [];
        
        // دعم التوافقية العكسية: إذا كان النظام القديم يعتمد على مفرد $download_item
        if (empty($download_items) && !empty($medical_data['download_item'])) {
            $download_items = [$medical_data['download_item']];
        }
        
        // القيمة الافتراضية في حال كانت المصفوفة فارغة تماماً
        if (empty($download_items)) {
            $download_items = [[
                'type' => 'pdf',
                'title' => 'عرض واتفاقية التدريب الطبي',
                'sub' => 'Example',
                'file' => 'assets/files/medical_training_agreement.pdf'
            ]];
        }
        
        $total_items = count($download_items);
        foreach ($download_items as $index => $item):
            $file_type = strtolower($item['type'] ?? 'pdf');
            $is_pdf = ($file_type === 'pdf');
            $icon_img = $is_pdf ? 'assets/img/Grouppdf.webp' : 'assets/img/Groupword.webp';
            $alt_text = $is_pdf ? 'ملف PDF' : 'ملف Word';
            $is_last = ($index === $total_items - 1);
            
            // معالجة مسار الملف بذكاء ليدعم البداية الصحيحة
            $raw_file = $item['file'] ?? '';
            $file_url = !empty($raw_file) ? htmlspecialchars(($path_prefix ?? '/') . ltrim($raw_file, '/')) : '#';
      ?>
        <div class="download-card <?php echo !$is_last ? 'mb-3' : ''; ?>">
          <div class="download-row">
            <!-- الأيقونة -->
            <img src="<?php echo htmlspecialchars(get_image_url($icon_img), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8'); ?>" class="dl-icon" />
            
            <!-- العنوان والنص الفرعي -->
            <div class="dl-info">
              <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? 'عرض واتفاقية التدريب الطبي', ENT_QUOTES, 'UTF-8'); ?></div>
              <div class="dl-sub"><?php echo htmlspecialchars($item['sub'] ?? 'Example', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            
            <!-- النقاط الفاصلة المرنة -->
            <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">...........................................................................................................</span>
            
            <!-- زر التحميل -->
            <a class="download-link" href="<?php echo $file_url; ?>" download>Download</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

    </div>
  </section>
  <!-- custom-services-info end -->

  <?php
    $medical_modals_file = __DIR__ . '/includes/admin_medical_modals.php';
    if (!empty($is_admin) && file_exists($medical_modals_file)) { 
        include_once $medical_modals_file; 
    }
  ?>
