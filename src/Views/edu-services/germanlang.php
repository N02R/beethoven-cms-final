<?php
/**
 * صفحة دورات اللغة الألمانية - German Language Courses Page View
 */
?>
  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>education">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($german_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($german_data['page_breadcrumb'] ?? 'دورات اللغة الألمانية'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="germanlang-hero custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars(get_image_url($german_data['hero_img'] ?? null, 'assets/img/education/servicesimg4.png')); ?>'); background-position: <?php echo htmlspecialchars($german_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- القسم الرئيسي (العنوان والوصف) -->
      <div class="head-info pb-4 mb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($german_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($german_data['main_desc'] ?? '')); ?></p>
      </div>
      
      <!-- 1. المستويات المتوفرة -->
      <div class="advice-stars my-5 py-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanLevelsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل المستويات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $levels_sec = $german_data['levels_section'] ?? [];
          $levels_title = $levels_sec['title'] ?? 'المستويات المتوفرة (طبقًا ل CEFR)';
          $levels_list = $levels_sec['levels_list'] ?? [];
        ?>
        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($levels_title); ?></h5>
        <ul class="star-list">
          <div class="row">
            <?php if (!empty($levels_list)): ?>
              <?php foreach ($levels_list as $level): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                  <li>
                    <p class="mb-0">
                      <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="نجمة" class="ms-2"/>
                      <?php echo htmlspecialchars($level); ?>
                    </p>
                  </li>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </ul>
      </div>

      <!-- 2. مميزات دوراتنا -->
      <div class="advice-check py-4 mb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanFeaturesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل مميزات دوراتنا">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $feat_sec = $german_data['features_section'] ?? [];
          $feat_title = $feat_sec['title'] ?? 'مميزات دوراتنا';
          $feat_list = $feat_sec['features_list'] ?? [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($feat_title); ?></h5>
        <div class="row">
          <?php if (!empty($feat_list)): ?>
            <?php foreach ($feat_list as $feature): ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars($feature); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- 3. نصائح للنجاح في الدراسة بالألمانية -->
      <div class="advice-tips py-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanTipsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل نصائح للنجاح">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
          $tips_sec = $german_data['tips_section'] ?? [];
          $tips_title = $tips_sec['title'] ?? 'نصائح للنجاح في الدراسة بالألمانية';
          $tips_list = $tips_sec['tips_list'] ?? [];
        ?>
        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($tips_title); ?></h5>
        <div class="row">
          <?php if (!empty($tips_list)): ?>
            <?php foreach ($tips_list as $tip): ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars($tip); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

  <?php
    $german_modals_file = __DIR__ . '/includes/admin_germanlang_modals.php';
    if (!empty($is_admin) && file_exists($german_modals_file)) { 
        include_once $german_modals_file; 
    }
  ?>
