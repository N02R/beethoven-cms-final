  <!-- Breadcrumb start-->
  <div class="custom-container pt-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل مسار التنقل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '/'); ?>education">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($blocked_data['page_breadcrumb_url'] ?? '#'); ?>">
            <?php echo htmlspecialchars($blocked_data['page_breadcrumb'] ?? 'الضمانات المالية والحساب البنكي المغلق'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start-->
  <section class="custom-services py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="financial-hero custom-hero"
        style="background-image: url('<?php echo htmlspecialchars(get_image_url($blocked_data['hero_img'] ?? null, 'assets/img/education/servicesimg7.png')); ?>');">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      
      <!-- 1. العنوان الرئيسي والأهمية -->
      <div class="head-info pb-4 mb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف الرئيسي والأهمية">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($blocked_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($blocked_data['main_desc'] ?? '')); ?></p>
        
        <h5 class="advice-text mt-5"><?php echo htmlspecialchars($blocked_data['importance_title'] ?? 'أهمية إثبات الضمان المالي'); ?></h5>
        <p><?php echo nl2br(htmlspecialchars($blocked_data['importance_desc'] ?? '')); ?></p>
      </div>

      <!-- 2. خيارات الضمان المالي -->
      <div class="advice-check my-5 pb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedOptionsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل خيارات الضمان المالي">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($blocked_data['options_title'] ?? 'خيارات الضمان المالي للدراسة في ألمانيا'); ?></h5>
        <div class="d-flex flex-column gap-3">
          <?php foreach (($blocked_data['options_items'] ?? []) as $opt): ?>
            <p class="mb-0">✅️<?php echo htmlspecialchars($opt); ?></p>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. الحساب البنكي المغلق -->
      <div class="advice-stars pb-4 mb-4" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedAccountModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل نقاط الحساب المغلق">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($blocked_data['account_title'] ?? 'الحساب البنكي المغلق'); ?></h5>
        <ul class="star-list">
          <div class="d-flex flex-column gap-3">
            <?php foreach (($blocked_data['account_points'] ?? []) as $point): ?>
              <li>
                <p class="mb-0">
                  <img src="<?php echo get_image_url('assets/img/starList.svg.webp'); ?>" alt="نجمة" class="ms-2" width="25"/>
                  <?php echo htmlspecialchars($point); ?>
                </p>
              </li>
            <?php endforeach; ?>
          </div>
        </ul>
      </div>

  <!-- 4. الروابط الخارجية (Dr.WALTER, FINTIBA وغيرها) -->
  <div class="py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedLinksModal" style="position: absolute; top: -10px; right: 10px; z-index: 10;" title="تعديل الروابط والشركات">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php foreach (($blocked_data['service_links'] ?? []) as $index => $link): ?>
        <?php 
            // تحديد ما إذا كان العنصر مفعلًا بناءً على قيمة active في البيانات (أو اعتبار أول عنصر مفعل افتراضياً)
            $isActive = !empty($link['active']) ? ' active' : '';
            $marginTop = $index === 0 ? '' : ' mt-4';
        ?>
        <div class="link<?php echo $isActive . $marginTop; ?>">
          <a href="<?php echo htmlspecialchars($link['url'] ?? '#'); ?>" target="_blank" rel="noopener" class="text-decoration-none">
            <p class="text-center mb-0"><?php echo htmlspecialchars($link['text'] ?? ''); ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

    </div>
  </section>
  <!-- custom-services-info end-->

  <?php
    $financial_modals_file = __DIR__ . '/includes/admin_financial_modals.php';
    if (!empty($is_admin) && file_exists($financial_modals_file)) { 
        include_once $financial_modals_file; 
    }
  ?>
