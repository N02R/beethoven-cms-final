<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Education Page View (Inline Editing Enabled)
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

<!-- 1. education start -->
<section class="education py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduHeroModal" title="تعديل الهيرو">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
    // 🌟 الاعتماد على الدالة الموحدة المعتمدة لتعدد اللغات
    $edu_hero = $getLocalizedData('edu_hero', []);
    
    $hero_title = $edu_hero['title'] ?? 'الدراسة في ألمانيا';
    $hero_desc  = $edu_hero['desc'] ?? '';
    $hero_btn_text = $edu_hero['btn_text'] ?? 'ابدأ الآن';
    $hero_btn_url  = $edu_hero['btn_url'] ?? '#';
    $hero_bg    = get_image_url($edu_hero['img'] ?? null, 'assets/img/education/hero.jpg');
    ?>
    <div class="row align-items-stretch g-5">
      <div class="col-lg-6">
        <div class="img-hero" style="background-image: url('<?php echo htmlspecialchars($hero_bg, ENT_QUOTES, 'UTF-8'); ?>'); background-size: cover; background-position: center; min-height: 350px; border-radius: 20px;"></div>
      </div>
      <div class="col-lg-6 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
        <div class="education-info pt-2">
          <h2 class="sec-title mb-3"><?php echo htmlspecialchars($hero_title, ENT_QUOTES, 'UTF-8'); ?></h2>
          <p class="main-p mb-4"><?php echo nl2br(htmlspecialchars($hero_desc, ENT_QUOTES, 'UTF-8')); ?></p>
          <a href="<?php echo htmlspecialchars($hero_btn_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-education">
            <?php echo htmlspecialchars($hero_btn_text, ENT_QUOTES, 'UTF-8'); ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- education end -->

<!-- 2. why study start -->
<section class="study py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduWhyModal" title="تعديل لماذا الدراسة">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 جلب بيانات قسم لماذا الدراسة متعددة اللغات
    $edu_why = $getLocalizedData('edu_why', []);
    
    $why_title = $edu_why['title'] ?? 'لماذا الدراسة في ألمانيا؟';
    $why_desc  = $edu_why['desc'] ?? '';
    $why_items = $edu_why['items'] ?? [];
    if (!is_array($why_items)) $why_items = [];
    ?>

    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars($why_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($why_desc)): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($why_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>

    <div class="row g-3">
      <?php if (!empty($why_items) && is_array($why_items)): ?>
        <?php foreach ($why_items as $item): 
          $item_img   = get_image_url($item['img'] ?? null);
          $item_title = $item['title'] ?? '';
          $item_desc  = $item['desc'] ?? '';
        ?>
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="card choose-card h-100">
              <div class="card-body">
                <?php if (!empty($item_img)): ?>
                  <a href="#">
                    <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="icon" />
                  </a>
                <?php endif; ?>
                <h5 class="card-title"><?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($item_desc, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p>لا توجد عناصر مضافة حالياً.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- why study end -->

<!-- 3. time line start -->
<section class="timeline-section py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduTimelineModal" title="تعديل الخطوات">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 جلب بيانات قسم الخطوات متعددة اللغات بالمعيار الذهبي
    $edu_timeline   = $getLocalizedData('edu_timeline', []);
    $timeline_title = $edu_timeline['title'] ?? 'خطوات الدراسة في ألمانيا';
    $timeline_desc  = $edu_timeline['desc'] ?? '';
    $steps_items    = $edu_timeline['steps'] ?? ($edu_timeline['items'] ?? []);
    if (!is_array($steps_items)) $steps_items = [];
    ?>
    
    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars($timeline_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($timeline_desc)): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($timeline_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>
    
    <?php if (!empty($steps_items)): ?>
      <div class="map-container d-none d-lg-block">
        <div class="map-box">
          <img src="<?php echo get_image_url('assets/img/vector/Vector.png'); ?>" alt="base" class="line-base">
          <img src="<?php echo get_image_url('assets/img/vector/Vector-1.png'); ?>" alt="active" class="line-active">
          
          <?php 
          $dots = ['bg-blue', 'bg-green', 'bg-yellow', 'bg-orange', 'bg-orange', 'bg-red'];
          foreach ($steps_items as $idx => $step): 
              $num = sprintf("%02d", $idx + 1);
              $dotClass = $dots[$idx % count($dots)];
              
              $defaultIcon = 'assets/img/vector/Grouptime' . ($idx + 1) . '.png';
              $iconPath = get_image_url($step['icon'] ?? null, $defaultIcon);
              $groupNumImg = get_image_url('assets/img/vector/Group' . ($idx + 1) . '.png');
              
              $stepNumberClass = 'step-' . ($idx + 1);
          ?>
            <div class="step-wrapper <?php echo $stepNumberClass; ?>">
              <img src="<?php echo htmlspecialchars($groupNumImg, ENT_QUOTES, 'UTF-8'); ?>" class="step-img-num" alt="<?php echo $num; ?>">
              <div class="icon-main">
                <img src="<?php echo htmlspecialchars($iconPath, ENT_QUOTES, 'UTF-8'); ?>" alt="">
              </div>
              <div class="info-content">
                <h3><?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h3>
                <span class="dot <?php echo $dotClass; ?>"></span>
                <h4><?php echo htmlspecialchars($step['subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h4>
                <p><?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="mobile-timeline d-lg-none">
        <?php foreach ($steps_items as $idx => $step): 
            $num = sprintf("%02d", $idx + 1);
            $defaultIcon = 'assets/img/vector/Grouptime' . ($idx + 1) . '.png';
            $iconPath = get_image_url($step['icon'] ?? null, $defaultIcon);
        ?>
          <div class="m-step">
            <div class="m-number-box">
              <span class="m-num"><?php echo $num; ?></span>
            </div>
            <div class="m-content">
              <div class="m-header">
                <div class="m-icon">
                  <img src="<?php echo htmlspecialchars($iconPath, ENT_QUOTES, 'UTF-8'); ?>" alt="">
                </div>
                <h3><?php echo htmlspecialchars($step['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h3>
              </div>
              <h4><?php echo htmlspecialchars($step['subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h4>
              <p><?php echo htmlspecialchars($step['desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center text-muted py-4">
        <p>لا توجد خطوات مضافة حالياً.</p>
      </div>
    <?php endif; ?>
  </div>
</section>
<!-- time line end -->

<!-- 4. education services start -->
<section class="edu-services py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduServicesModal" title="تعديل خدمات التعليم">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 جلب بيانات خدمات التعليم متعددة اللغات بالمعيار الذهبي
    $edu_services = $getLocalizedData('edu_services', []);
    
    $services_title = $edu_services['title'] ?? ($edu_services['section_title'] ?? 'خدمات التعليم');
    $services_desc  = $edu_services['desc'] ?? ($edu_services['section_subtitle'] ?? '');
    $services_items = $edu_services['items'] ?? [];
    if (!is_array($services_items)) $services_items = [];
    ?>

    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars($services_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($services_desc)): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($services_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>
    
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 text-center">
      <?php if (!empty($services_items) && is_array($services_items)): ?>
        <?php foreach ($services_items as $item): 
            $raw_url = trim($item['url'] ?? '#');
            
            if ($raw_url !== '#' && !str_starts_with($raw_url, 'http')) {
                $slug = ltrim($raw_url, '/');
                if (!str_starts_with($slug, 'edu-services/')) {
                    $slug = 'edu-services/' . $slug;
                }
                $final_url = ($path_prefix ?? '') . $slug . '?from=education';
            } else {
                $final_url = $raw_url;
            }
            
            $bg_img = get_image_url($item['img'] ?? null);
            $item_title = $item['title'] ?? '';
        ?>
          <div class="col">
            <a href="<?php echo htmlspecialchars($final_url, ENT_QUOTES, 'UTF-8'); ?>" class="text-decoration-none">
              <div class="card service-card text-white border-0 rounded-5"
                style="background-image: url('<?php echo htmlspecialchars($bg_img, ENT_QUOTES, 'UTF-8'); ?>');">
                <div class="card-body d-flex align-items-end justify-content-center">
                  <h6 class="card-title m-0">
                    <?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?>
                  </h6>
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
<!-- education services end -->

<?php 
// تضمين موديلات التعديل الخاصة بصفحة التعليم للمشرف فقط
$edu_modals_file = __DIR__ . '/admin/admin_edu_modals.php';
if (!empty($is_admin) && $is_admin === true && file_exists($edu_modals_file)) { 
    include_once $edu_modals_file; 
} 
?>
