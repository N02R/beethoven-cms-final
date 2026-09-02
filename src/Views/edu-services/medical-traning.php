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
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobAgrBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($parent_url); ?>"><?php echo htmlspecialchars($parent_name); ?></a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($job_agreements_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($job_agreements_data['page_breadcrumb'] ?? 'اتفاقيات البحث عن عمل'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->


  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobAgrHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars(get_image_url($job_agreements_data['hero_img'] ?? 'assets/img/job/servicesimg4.png')); ?>'); background-position: <?php echo htmlspecialchars($job_agreements_data['hero_position'] ?? 'center center'); ?>;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobAgrMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($job_agreements_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($job_agreements_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- ملاحظات هامة -->
      <div class="advice-stars pt-3 pb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobAgrNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h5 class="note-text">ملاحظة !!</h5>
        <ul class="star-list">
          <li>
            <p>
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="تنبيه" class="ms-2" />
              <?php 
                $note_text = $job_agreements_data['note_text'] ?? '';
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobAgrCardModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل ملف التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <?php 
              $download_items = $job_agreements_data['download_items'] ?? [];
              
              // التوافقية العكسية مع النظام المفرد القديم
              if (empty($download_items) && !empty($job_agreements_data['download_item'])) {
                  $download_items = [$job_agreements_data['download_item']];
              }
              
              // القيمة الافتراضية
              if (empty($download_items)) {
                  $download_items = [[
                      'type' => 'pdf',
                      'title' => 'عرض واتفاقيات العمل',
                      'sub' => 'Example',
                      'file' => 'assets/files/job_search_agreement.pdf'
                  ]];
              }
              
              $total_items = count($download_items);
              foreach ($download_items as $index => $item):
                  $file_type = strtolower($item['type'] ?? 'pdf');
                  $icon_img = ($file_type === 'word' || $file_type === 'docx') ? 'assets/img/education/Groupword.png' : 'assets/img/education/Grouppdf.png';
                  $alt_text = ($file_type === 'word' || $file_type === 'docx') ? 'ملف Word' : 'ملف PDF';
                  $is_last = ($index === $total_items - 1);
                  
                  $raw_file = $item['file'] ?? '';
                  $file_url = !empty($raw_file) ? htmlspecialchars(($path_prefix ?? '/') . ltrim($raw_file, '/')) : '#';
            ?>
              <div class="download-card <?php echo !$is_last ? 'mb-3' : ''; ?>">
                <div class="download-row">
                  <img src="<?php echo htmlspecialchars(get_image_url($icon_img), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8'); ?>" />
                  <div class="dl-info">
                    <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? 'عرض واتفاقيات العمل', ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="dl-sub"><?php echo htmlspecialchars($item['sub'] ?? 'Example', ENT_QUOTES, 'UTF-8'); ?></div>
                  </div>
                  <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.........................................................................................................................</span>
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
    $job_agreements_modals_file = __DIR__ . '/includes/admin_job_agreements_modals.php';
    if (!empty($is_admin) && file_exists($job_agreements_modals_file)) { 
        include_once $job_agreements_modals_file; 
    }
  ?>
