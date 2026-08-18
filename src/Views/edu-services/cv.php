<?php 
  // جلب البيانات الخاصة بالصفحة إن وجدت
  $cv_data = $data['cv_page'] ?? [];
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>education">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($cv_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($cv_data['page_breadcrumb'] ?? 'السيرة الذاتية CV'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo htmlspecialchars(get_image_url($cv_data['hero_img'] ?? null, '../assets/img/education/servicesimg2.jpg')); ?>'); background-position: center -30px;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($cv_data['main_title'] ?? 'السيرة الذاتية "CV"'); ?></h2>
        <?php if (!empty($cv_data['main_desc'])): ?>
            <p class="par-text"><?php echo nl2br(htmlspecialchars($cv_data['main_desc'])); ?></p>
        <?php else: ?>
            <p class="par-text">السيرة الذاتيّة (CV) هي الجزء المكتوب عنك والذي يريد معرفتهُ الطرف الآخر (الشخص الذي سيُجري المقابلة معك) وتُكتب بشكل مختصر جداً (صفحة واحدة أو إثنتان كأقصى حد، باللغة الألمانية أو الإنجليزية) . وهي أيضاً وثيقة قيمة ومهمة جداً لأنها ستكون أول ما تُعبر به عن نفسك وربما تكون أداة الاتصال الوحيدة المباشرة مع الطرف الآخر (في هذه الحالة السفارة / القنصلية الألمانية أو موظف القبول في الشركة أو الأستاذ المشرف في الجامعة، الخ).</p>
        <?php endif; ?>
      </div>

      <!-- 2. النصائح -->
      <div class="advice-check pt-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvAdviceModal" style="position: absolute; top: 30px; right: 0; z-index: 10;" title="تعديل النصائح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars($cv_data['advice_title'] ?? 'نصائح سريعة لكتابة CV فعّال'); ?></h5>
        
        <div class="row">
          <?php 
            $default_advice = [
                '✅️ استخدم تنسيق بسيط ومرتب',
                '✅ رتب المعلومات من الأحدث إلى الأقدم',
                '✅ اجعلها صفحة أو صفحتين بحد أقصى',
                '✅ ركز على ما يهم الجهة المستلمة',
                '✅تجنب الزخرفة أو الألوان الغير رسمية.',
                '✅رجع اللغة والإملاء جيداً .'
            ];
            $advice_list = !empty($cv_data['advice_points']) ? $cv_data['advice_points'] : $default_advice;
            foreach ($advice_list as $point):
          ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <p><?php echo htmlspecialchars($point); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. ملاحظات هامة (إن وجدت) -->
      <?php if (!empty($cv_data['notes'])): ?>
      <div class="advice-check py-4 mb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="note-text mb-4"><?php echo htmlspecialchars($cv_data['note_title'] ?? 'ملاحظات هامة !!'); ?></h5>
        <div class="row">
          <?php foreach ($cv_data['notes'] as $note): ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-2">
              <p>✅ <?php echo htmlspecialchars($note); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- 4. التحميل (PDF & Word) -->
      <div class="row mt-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvDownloadModal" style="position: absolute; top: -20px; right: 0; z-index: 10;" title="تعديل نماذج التحميل">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $default_downloads = [
              ['type' => 'pdf', 'title' => 'السيرة الذاتية "CV"', 'sub' => 'Example', 'file' => '#'],
              ['type' => 'word', 'title' => 'السيرة الذاتية "CV"', 'sub' => 'Example', 'file' => '#'],
              ['type' => 'word', 'title' => 'السيرة الذاتية "CV"', 'sub' => 'Example', 'file' => '#']
          ];
          $download_items = !empty($cv_data['download_items']) ? $cv_data['download_items'] : $default_downloads;

          foreach ($download_items as $index => $item):
              $is_pdf = (strtolower($item['type'] ?? '') === 'pdf');
              $img_src = $is_pdf ? '../assets/img/education/Grouppdf.png' : '../assets/img/education/Groupword.png';
              $is_last = ($index === count($download_items) - 1);
        ?>
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="download-card <?php echo !$is_last ? 'mb-3' : ''; ?>">
              <div class="download-row">
                <img src="<?php echo htmlspecialchars($img_src); ?>" alt="" />
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars($item['title'] ?? 'السيرة الذاتية "CV"'); ?></div>
                  <div class="dl-sub"><?php echo htmlspecialchars($item['sub'] ?? 'Example'); ?></div>
                </div>
                <span class="leader d-lg-block d-md-none d-sm-none"
                  aria-hidden="true">................................................................................................................</span>
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
    // تضمين الـ Modals الخاصة بلوحة التحكم في حال كان المستخدم مديراً
    $cv_modals_file = __DIR__ . '/includes/admin_cv_modals.php';
    if (!empty($is_admin) && file_exists($cv_modals_file)) { 
        include_once $cv_modals_file; 
    }
?>
