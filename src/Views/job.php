<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Job Page View (Inline Editing Enabled)
 * النسخة المحدثة والنظيفة المطابقة للمعايير الذهبية والدستور الهندسي الموحد.
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

<!-- 1. job hero start -->
<section class="job py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobHeroModal" title="تعديل الهيرو">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
    // 🌟 جلب بيانات هيرو الوظائف بالمعيار الذهبي متعدد اللغات
    $job_hero = $getLocalizedData('job_hero', []);
    
    $hero_title = $job_hero['title'] ?? 'الوظائف والمهن في ألمانيا';
    $hero_desc  = $job_hero['desc'] ?? '';
    $hero_btn_text = $job_hero['btn_text'] ?? 'تواصل معنا';
    $hero_btn_url  = $job_hero['btn_url'] ?? 'contact';
    $hero_bg    = get_image_url($job_hero['img'] ?? null, 'assets/img/job/jobhero.png');
    ?>
    <div class="row align-items-stretch g-5">
      <div class="col-lg-6 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
        <div class="job-info pt-2">
          <h2 class="sec-title mb-3"><?php echo htmlspecialchars($hero_title, ENT_QUOTES, 'UTF-8'); ?></h2>
          <p class="main-p mb-4"><?php echo nl2br(htmlspecialchars($hero_desc, ENT_QUOTES, 'UTF-8')); ?></p>
          <a href="<?php echo htmlspecialchars($hero_btn_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-job">
            <?php echo htmlspecialchars($hero_btn_text, ENT_QUOTES, 'UTF-8'); ?>
          </a>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="img-hero" style="background-image: url('<?php echo htmlspecialchars($hero_bg, ENT_QUOTES, 'UTF-8'); ?>'); background-size: cover; background-position: center; min-height: 350px; border-radius: 20px;"></div>
      </div>
    </div>
  </div>
</section>
<!-- job hero end -->

<!-- 2. why job start -->
<section class="study py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobWhyModal" title="تعديل لماذا التدريب">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 جلب بيانات قسم لماذا التدريب متعددة اللغات
    $job_why = $getLocalizedData('job_why', []);
    
    $why_title = $job_why['title'] ?? 'لماذا التدريب والتوظيف في ألمانيا؟';
    $why_desc  = $job_why['desc'] ?? '';
    $why_items = $job_why['items'] ?? [];
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
<!-- why job end -->

<!-- 3. program start -->
<section class="program py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobProgramModal" title="تعديل أنواع التدريب">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 جلب بيانات برامج التدريب متعددة اللغات
    $job_programs = $getLocalizedData('job_programs', []);
    
    $prog_title = $job_programs['title'] ?? 'برامج التدريب المهني والتوظيف';
    $prog_desc  = $job_programs['desc'] ?? '';
    $prog_items = $job_programs['items'] ?? [];
    if (!is_array($prog_items)) $prog_items = [];
    ?>

    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3 fw-bold"><?php echo htmlspecialchars($prog_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($prog_desc)): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($prog_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>

    <div class="row g-4">
      <?php if (!empty($prog_items) && is_array($prog_items)): ?>
        <?php foreach ($prog_items as $p): 
          $item_img      = get_image_url($p['img'] ?? null);
          $item_title    = $p['title'] ?? '';
          $item_desc     = $p['desc'] ?? '';
          $item_btn_text = $p['btn_text'] ?? 'اطلب الآن';
          $item_btn_url  = $p['btn_url'] ?? '#';
          $is_dark       = !empty($p['is_dark']) && $p['is_dark'] === true;
        ?>
          <div class="col-md-6">
            <div class="program-info h-100 <?php echo $is_dark ? 'highlight-box' : ''; ?>" style="border-radius: 25px; position: relative;">
              <div class="program-content">
                <?php if (!empty($item_img)): ?>
                  <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?>" class="mb-3">
                <?php endif; ?>
                <h4 class="fw-bold mb-4 <?php echo $is_dark ? 'text-white' : ''; ?>"><?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?></h4>
                <p class="<?php echo $is_dark ? 'text-white' : ''; ?>">
                  <?php echo nl2br(htmlspecialchars($item_desc, ENT_QUOTES, 'UTF-8')); ?>
                </p>
              </div>
              <a href="<?php echo htmlspecialchars($item_btn_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn-info-wrapper mt-4 <?php echo $is_dark ? 'is-light' : ''; ?>">
                <h3 class="mb-0"><?php echo htmlspecialchars($item_btn_text, ENT_QUOTES, 'UTF-8'); ?></h3>
                <div class="arrow-icon">
                  <img src="<?php echo get_image_url('assets/img/home/ArrowLink.svg.webp'); ?>" alt="سهم">
                </div>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p>لا توجد برامج مضافة حالياً.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- program end -->

<!-- 4. time line start -->
<section class="timeline-section py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobTimelineModal" title="تعديل الخطوات">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 جلب بيانات خطوات التوظيف متعددة اللغات
    $job_timeline   = $getLocalizedData('job_timeline', []);
    $timeline_title = $job_timeline['title'] ?? 'خطوات التوظيف والتدريب في ألمانيا';
    $timeline_desc  = $job_timeline['desc'] ?? '';
    $steps_items    = $job_timeline['steps'] ?? ($job_timeline['items'] ?? []);
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

<!-- 5. job services start -->
<section class="edu-services py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobServicesModal" title="تعديل الخدمات">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 جلب بيانات خدمات الوظائف متعددة اللغات
    $job_services = $getLocalizedData('job_services', []);
    
    $services_title = $job_services['title'] ?? ($job_services['section_title'] ?? 'خدمات التوظيف');
    $services_desc  = $job_services['desc'] ?? ($job_services['section_subtitle'] ?? '');
    $services_items = $job_services['items'] ?? [];
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
                // 🌟 التأكد من توجيه الرابط إلى مسار job-services/ بدلاً من edu-services/
                if (!str_starts_with($slug, 'job-services/') && !str_starts_with($slug, 'edu-services/')) {
                    $slug = 'job-services/' . $slug;
                }
                $final_url = ($path_prefix ?? '') . $slug . '?from=job';
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
<!-- job services end -->

<?php 
// تضمين موديلات التعديل الخاصة بصفحة الوظائف للمشرف فقط
$job_modals_file = __DIR__ . '/admin/admin_job_modals.php';
if (!empty($is_admin) && $is_admin === true && file_exists($job_modals_file)) { 
    include_once $job_modals_file; 
} 
?>
