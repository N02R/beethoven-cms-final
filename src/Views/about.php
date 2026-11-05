<?php
declare(strict_types=1);

/**
 * Beethoven CMS - About View (Inline Editing Enabled)
 * النسخة المحدثة والنظيفة المطابقة لهيكلية home.php والدستور الهندسي الموحد.
 */

$current_lang = $current_lang ?? get_current_lang();
$current_dir = $current_dir ?? (($current_lang === 'ar') ? 'rtl' : 'ltr');

// دالة مساعدة داخلية موحدة لاستخراج بيانات القسم متعدد اللغات بسلامة تامة وحصانة ضد الـ null (DRY Principle)
$getLocalizedData = function(string $settingKey, mixed $default = []): mixed {
    global $current_lang;
    $raw = get_setting($settingKey, $default);
    
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $data = is_array($decoded) ? $decoded : $default;
    } else {
        $data = is_array($raw) ? $raw : $default;
    }

    if (!is_array($data)) {
        return $default;
    }

    $langKey = $current_lang ?? 'de';
    if (isset($data[$langKey]) && is_array($data[$langKey])) {
        return $data[$langKey];
    }
    if (isset($data['de']) && is_array($data['de'])) {
        return $data['de'];
    }
    if (isset($data['ar']) && is_array($data['ar'])) {
        return $data['ar'];
    }
    if (isset($data['en']) && is_array($data['en'])) {
        return $data['en'];
    }

    return $data;
};
?>

<!-- 1. about start -->
<section class="about py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#aboutEditModal" title="تعديل قسم من نحن">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
    $ab = $getLocalizedData('about_section', []);
    
    $ab_title       = $ab['title'] ?? 'من نحن';
    $ab_desc        = $ab['desc'] ?? '';
    $ab_btn_text    = $ab['btn_text'] ?? 'قراءة المزيد';
    $ab_btn_url     = $ab['btn_url'] ?? '#';
    
    $vision_title   = $ab['vision_title'] ?? 'رؤية الشركة';
    $vision_desc    = $ab['vision_desc'] ?? '';
    $message_title  = $ab['message_title'] ?? 'رسالة الشركة';
    $message_desc   = $ab['message_desc'] ?? '';

    $about_main_img = get_image_url($ab['main_img'] ?? null, 'assets/img/about/about1.jpg');
    $about_sub_img  = get_image_url($ab['sub_img'] ?? null, 'assets/img/about/about2.png');
    $vision_icon    = get_image_url($ab['vision_icon'] ?? null, 'assets/img/about/CompanyVision.svg.webp');
    $message_icon   = get_image_url($ab['message_icon'] ?? null, 'assets/img/about/CompanyMessage.svg.webp');
    ?>

    <div class="row align-items-center g-5">
      
      <!-- العمود الأول: النصوص (يأتي أولاً في الـ HTML، فيتوضع يمين بالعربي ويسار بالأجنبي تلقائياً) -->
      <div class="col-lg-6 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
        <h2 class="sec-title mb-3"><?php echo htmlspecialchars($ab_title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="about-par mb-4"><?php echo nl2br(htmlspecialchars($ab_desc, ENT_QUOTES, 'UTF-8')); ?></p>
        
        <div class="row g-3 mb-4">
          <!-- رؤية الشركة -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body p-0">
                <div class="title mb-2 d-flex align-items-center gap-2">
                  <span class="icon-wrap flex-shrink-0">
                    <img src="<?php echo htmlspecialchars($vision_icon, ENT_QUOTES, 'UTF-8'); ?>" alt="رؤية الشركة">
                  </span>
                  <span><?php echo htmlspecialchars($vision_title, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <p class="card-text"><?php echo htmlspecialchars($vision_desc, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          </div>

          <!-- رسالة الشركة -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body p-0">
                <div class="title mb-2 d-flex align-items-center gap-2">
                  <span class="icon-wrap flex-shrink-0">
                    <img src="<?php echo htmlspecialchars($message_icon, ENT_QUOTES, 'UTF-8'); ?>" alt="رسالة الشركة">
                  </span>
                  <span><?php echo htmlspecialchars($message_title, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <p class="card-text"><?php echo htmlspecialchars($message_desc, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          </div>
        </div>

        <a href="<?php echo htmlspecialchars($ab_btn_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-about">
          <?php echo htmlspecialchars($ab_btn_text, ENT_QUOTES, 'UTF-8'); ?>
        </a>
      </div>

      <!-- العمود الثاني: الصور المتراكبة (يأتي ثانياً في الـ HTML، فيتوضع يسار بالعربي ويمنين بالأجنبي تلقائياً) -->
      <div class="col-lg-6 position-relative">
        <div class="image-stack">
          <img src="<?php echo htmlspecialchars($about_main_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Main About Image" class="img-fluid main-img">
          <div class="sub-img-wrapper">
            <img src="<?php echo htmlspecialchars($about_sub_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Sub About Image" class="img-fluid sub-img">
            <div class="dots-bg"></div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- about end -->

<!-- 2. about services start (Independent) -->
<section class="services py-5 editable-wrapper" style="position: relative;">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#aboutServicesEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل خدمات من نحن">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 الاعتماد على المفتاح المستقل خاص بصفحة من نحن فقط
    $s_data = $getLocalizedData('about_services', []);
    
    $sec_title = $s_data['section_title'] ?? ($s_data['title'] ?? 'خدماتنا المميزة');
    $sec_desc  = $s_data['section_subtitle'] ?? ($s_data['desc'] ?? '');
    $services  = $s_data['items'] ?? [];
    ?>

    <h2 class="mb-3 sec-title"><?php echo htmlspecialchars($sec_title, ENT_QUOTES, 'UTF-8'); ?></h2>
    
    <?php if (!empty($sec_desc)): ?>
        <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars($sec_desc, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <div class="row g-4">
      <?php if (!empty($services) && is_array($services)): ?>
        <?php foreach ($services as $service): 
          $service_img   = get_image_url($service['img'] ?? null, 'assets/img/home/education.jpg');
          $service_title = $service['title'] ?? 'اسم الخدمة';
          $service_url   = $service['url'] ?? '#';
        ?>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <a href="<?php echo htmlspecialchars($service_url, ENT_QUOTES, 'UTF-8'); ?>" class="card-link text-decoration-none d-block">
              <div class="card" style="background: url('<?php echo htmlspecialchars($service_img, ENT_QUOTES, 'UTF-8'); ?>') no-repeat center/cover;">
                <div class="card-info">
                  <h3><?php echo htmlspecialchars($service_title, ENT_QUOTES, 'UTF-8'); ?></h3>
                  <img src="<?php echo htmlspecialchars(get_image_url('assets/img/home/ArrowLink.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" 
                       alt="Arrow" 
                       style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p>لا توجد خدمات مضافة حالياً.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- about services end -->

<!-- 3. team start -->
<section class="team py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#teamEditModal" title="تعديل فريق العمل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 الاعتماد حصرياً على الدالة الموحدة المتوافقة مع الهيكلية الذهبية
    $team_data  = $getLocalizedData('team', []);
    
    $team_title = $team_data['title'] ?? 'فريق العمل';
    $team_desc  = $team_data['desc'] ?? '';
    $team_items = $team_data['items'] ?? [];
    if (!is_array($team_items)) $team_items = [];
    ?>
    
    <div class="team-text mb-5">
      <h2 class="sec-title"><?php echo htmlspecialchars((string)$team_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($team_desc)): ?>
        <p class="description main-p"><?php echo htmlspecialchars((string)$team_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>
    
    <div class="swiper-container-wrapper">
      <div class="swiper mySwiper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
        <div class="swiper-wrapper">
          <?php if (!empty($team_items)): ?>
            <?php foreach ($team_items as $member): 
              $m_name = $member['name'] ?? '';
              $m_role = $member['role'] ?? '';
              $m_img  = get_image_url($member['img'] ?? null, 'assets/img/team/member1.jpg');
            ?>
              <div class="swiper-slide">
                <div class="team-card">
                  <img src="<?php echo htmlspecialchars($m_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($m_name, ENT_QUOTES, 'UTF-8'); ?>" />
                  <div class="info">
                    <h5><?php echo htmlspecialchars($m_name, ENT_QUOTES, 'UTF-8'); ?></h5>
                    <p><?php echo htmlspecialchars($m_role, ENT_QUOTES, 'UTF-8'); ?></p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="swiper-slide w-100 text-center py-4">
              <p class="text-muted">لا يوجد أعضاء مضافون حالياً.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="swiper-nav-wrapper">
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
  </div>
</section>
<!-- team end -->

<!-- 4. count start -->
<section class="count editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#countsEditModal" title="تعديل الإحصائيات">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 الاعتماد حصرياً على الدالة الموحدة لتعدد اللغات
    $counts_data  = $getLocalizedData('about_counts', []);
    $counts_items = $counts_data['items'] ?? ($counts_data ?? []); // دعم الهيكل القديم والجديد بمرونة
    if (!is_array($counts_items)) $counts_items = [];
    ?>
    <div class="row g-4">
      <?php if (!empty($counts_items) && is_array($counts_items)): ?>
        <?php foreach ($counts_items as $c): 
            $c_num   = $c['number'] ?? '';
            $c_title = $c['title'] ?? '';
            $c_img   = get_image_url($c['img'] ?? null);
        ?>
          <div class="col-lg-3 col-md-6">
            <div class="count-card">
              <?php if (!empty($c_img)): ?>
                <div class="count-img">
                  <img src="<?php echo htmlspecialchars($c_img, ENT_QUOTES, 'UTF-8'); ?>" alt="icon">
                </div>
              <?php endif; ?>
              <div class="count-info">
                <span><?php echo htmlspecialchars($c_num, ENT_QUOTES, 'UTF-8'); ?></span>
                <p><?php echo htmlspecialchars($c_title, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p>لا توجد إحصائيات مضافة حالياً.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- count end -->

<!-- 5. partenar start -->
<section class="partenar py-5 editable-wrapper" style="position: relative;">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#partnersEditModal" title="تعديل الشركاء">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $partners_data  = $getLocalizedData('partners', []);
    $partners_title = $partners_data['title'] ?? (get_setting('partners_title', 'شركاؤنا داخل وخارج ألمانيا'));
    $partners_items = $partners_data['items'] ?? (get_setting('partners_items', []));

    if (is_string($partners_items)) {
        $partners_items = json_decode($partners_items, true) ?? [];
    }
    ?>
    <h2 class="sec-title mb-5"><?php echo htmlspecialchars((string)$partners_title, ENT_QUOTES, 'UTF-8'); ?></h2>
    
    <div class="row row-cols-2 row-cols-md-4 g-4 align-items-center justify-content-center">
      <?php if (!empty($partners_items) && is_array($partners_items)): ?>
        <?php foreach ($partners_items as $p): 
          $p_img = get_image_url($p['img'] ?? null);
          if (empty($p_img)) continue;
        ?>
          <div class="col">
            <div class="partner-item">
              <img src="<?php echo htmlspecialchars($p_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Partner" class="img-fluid" />
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-3">
          <p>لا توجد شعارات شركاء مضافة حالياً.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- partenar end -->

<?php 
// تضمين موديلات التعديل الخاصة بصفحة من نحن للمشرف فقط
$about_modals_file = __DIR__ . '/admin/admin_about_modals.php';
if (!empty($is_admin) && $is_admin === true && file_exists($about_modals_file)) { 
    include_once $about_modals_file; 
} 
?>
