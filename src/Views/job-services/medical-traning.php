<?php
// تأمين المتغيرات الافتراضية
$path_prefix = '/';

$job_agreements_data = $data['job_search_package_page'] ?? [
    'page_breadcrumb'     => 'اتفاقيات البحث عن عمل',
    'page_breadcrumb_url' => '#',
    'hero_img'            => 'assets/img/job/servicesimg4.png',
    'hero_position'       => 'center center',
    'main_title'          => 'عرض وإتفاقية العمل',
    'main_desc'           => 'كل عرضٍ (حالة) له تكلفة الخدمة الخاصة به حيث أن كل عرض يتضمن خدمات مختلفة وبذلك يتطلب إجراءات ومراسلات وجهود مختلفة. للحصول على فكرةٍ عامة عن العرض الخاص بك وتكلفة الخدمات الخاصة به، تجد أدناه العروض الأكثر طلباً (مثال لكل عرض).',
    'note_text'           => 'جميع العروض والاتفاقيات تكتب وتملأ باللغة الإنجليزية، للإستفسار عن أي بند أو شرح أي معلومات، لا تتردد بالتواصل معنا.',
    'download_item'       => [
        'type'  => 'pdf',
        'title' => 'عرض واتفاقيات العمل',
        'sub'   => 'Example',
        'file'  => 'assets/files/job_search_agreement.pdf'
    ]
];
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobAgrBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>job">التدريب المهني</a></li>
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
           style="background-image: url('<?php echo htmlspecialchars($path_prefix . ltrim($job_agreements_data['hero_img'] ?? 'assets/img/job/servicesimg4.png', '/') . '?v=' . time()); ?>'); background-position: <?php echo htmlspecialchars($job_agreements_data['hero_position'] ?? 'center center'); ?>;">
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
              <img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/education/starList.svg'); ?>" alt="تنبيه" class="ms-2" />
              <?php 
                $note_text = $job_agreements_data['note_text'] ?? '';
                $safe_note = htmlspecialchars($note_text);
                $note_text_formatted = str_replace('بالتواصل معنا', '<a href="' . $path_prefix . 'contact" class="fw-bold" style="color: #66aeee; text-decoration: none;">بالتواصل معنا</a>', $safe_note);
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
            <div class="download-card">
              <div class="download-row">
                <?php 
                  $item = $job_agreements_data['download_item'] ?? [];
                  $file_type = strtolower($item['type'] ?? 'pdf');
                  $icon_img = ($file_type === 'word' || $file_type === 'docx') ? 'assets/img/education/Groupword.png' : 'assets/img/education/Grouppdf.png';
                  $alt_text = ($file_type === 'word' || $file_type === 'docx') ? 'ملف Word' : 'ملف PDF';
                ?>
                <img src="<?php echo htmlspecialchars($path_prefix . ltrim($icon_img, '/')); ?>" alt="<?php echo htmlspecialchars($alt_text); ?>" />
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? 'عرض واتفاقيات العمل'); ?></div>
                  <div class="dl-sub"><?php echo htmlspecialchars($item['sub'] ?? 'Example'); ?></div>
                </div>
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.........................................................................................................................</span>
                <a class="download-link" href="<?php echo htmlspecialchars($path_prefix . ltrim($item['file'] ?? 'assets/files/job_search_agreement.pdf', '/')); ?>" download>Download</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->
