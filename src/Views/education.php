<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Education Page View (Gold Standard MVC, Multilingual & Security Hardened)
 * متطابق بالكامل مع المعيار الهندسي المعتمد في home.php و about.php.
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

$supported_langs = ['de', 'en', 'ar'];
$current_lang = $current_lang ?? (function_exists('get_current_lang') ? get_current_lang() : ($_SESSION['site_lang'] ?? 'de'));
if (!in_array($current_lang, $supported_langs, true)) {
    $current_lang = 'de';
}
$current_dir = $current_dir ?? (($current_lang === 'ar') ? 'rtl' : 'ltr');

// نصوص احتياطية مترجمة للغات الثلاث في حال خلو الحقول أو الأقسام
$i18nDefaults = [
    'de' => [
        'hero_title'     => 'Studieren in Deutschland',
        'hero_btn'       => 'Jetzt starten',
        'why_title'      => 'Warum in Deutschland studieren?',
        'timeline_title' => 'Schritte zum Studium in Deutschland',
        'services_title' => 'Bildungsdienstleistungen',
        'no_items'       => 'Derzeit sind keine Einträge verfügbar.',
        'no_steps'       => 'Derzeit sind keine Schritte hinterlegt.',
        'no_services'    => 'Derzeit sind keine Bildungsdienstleistungen verfügbar.',
        'edit_hero'      => 'Hero-Bereich bearbeiten',
        'edit_why'       => 'Vorteile bearbeiten',
        'edit_timeline'  => 'Schritte bearbeiten',
        'edit_services'  => 'Bildungsdienstleistungen bearbeiten'
    ],
    'en' => [
        'hero_title'     => 'Study in Germany',
        'hero_btn'       => 'Get Started',
        'why_title'      => 'Why Study in Germany?',
        'timeline_title' => 'Steps to Study in Germany',
        'services_title' => 'Education Services',
        'no_items'       => 'No items are currently available.',
        'no_steps'       => 'No steps are currently available.',
        'no_services'    => 'No education services are currently available.',
        'edit_hero'      => 'Edit Hero Section',
        'edit_why'       => 'Edit Why Study Section',
        'edit_timeline'  => 'Edit Timeline Steps',
        'edit_services'  => 'Edit Education Services'
    ],
    'ar' => [
        'hero_title'     => 'الدراسة في ألمانيا',
        'hero_btn'       => 'ابدأ الآن',
        'why_title'      => 'لماذا الدراسة في ألمانيا؟',
        'timeline_title' => 'خطوات الدراسة في ألمانيا',
        'services_title' => 'خدمات التعليم',
        'no_items'       => 'لا توجد عناصر مضافة حالياً.',
        'no_steps'       => 'لا توجد خطوات مضافة حالياً.',
        'no_services'    => 'لا توجد خدمات مضافة حالياً.',
        'edit_hero'      => 'تعديل الهيرو',
        'edit_why'       => 'تعديل لماذا الدراسة',
        'edit_timeline'  => 'تعديل الخطوات',
        'edit_services'  => 'تعديل خدمات التعليم'
    ]
];
$txt = $i18nDefaults[$current_lang];

// تهيئة متغيرات البيانات والإعدادات بأمان تام
$data = is_array($data ?? null) ? $data : [];
$currentSettings = is_array($currentSettings ?? null) ? $currentSettings : (is_array($settings ?? null) ? $settings : []);

// 1. دالة موحدة لاستخراج بيانات أي قسم متعدد اللغات بأمان تام
$getLocalizedData = function(string $settingKey, mixed $default = []) use ($current_lang, $data, $currentSettings): mixed {
    $raw = $data[$settingKey] ?? ($currentSettings[$settingKey] ?? get_setting($settingKey, $default));

    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $parsed = is_array($decoded) ? $decoded : $default;
    } else {
        $parsed = is_array($raw) ? $raw : $default;
    }

    if (!is_array($parsed)) {
        return $default;
    }

    if (isset($parsed[$current_lang]) && is_array($parsed[$current_lang])) {
        return $parsed[$current_lang];
    }
    if (isset($parsed['de']) && is_array($parsed['de'])) {
        return $parsed['de'];
    }
    if (isset($parsed['en']) && is_array($parsed['en'])) {
        return $parsed['en'];
    }
    if (isset($parsed['ar']) && is_array($parsed['ar'])) {
        return $parsed['ar'];
    }

    return $parsed;
};

// 2. دالة تنقية الروابط وربطها ببادئة اللغة الحالية (منع XSS و Open Redirect)
$buildLocalizedUrl = function(?string $rawUrl, bool $isEduService = false) use ($current_lang): string {
    $url = trim((string)($rawUrl ?? '#'));
    if ($url === '' || $url === '#') {
        return '#';
    }
    if (preg_match('/^(javascript|data|vbscript|file):/i', $url)) {
        return '#';
    }
    if (preg_match('/^(https?:\/\/|mailto:|tel:|#)/i', $url)) {
        return $url;
    }

    $cleanPath = preg_replace('/^(ar|en|de)\//i', '', ltrim($url, '/'));
    if ($isEduService && !str_starts_with($cleanPath, 'edu-services/')) {
        $cleanPath = 'edu-services/' . $cleanPath;
    }

    $localized = '/' . $current_lang . '/' . $cleanPath;
    if ($isEduService && !str_contains($localized, '?')) {
        $localized .= '?from=education';
    }
    return $localized;
};

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= 1. EDUCATION HERO SECTION START ================= -->
<section class="education py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
    $edu_hero = $getLocalizedData('edu_hero', []);
    
    $hero_title    = $edu_hero['title'] ?? $txt['hero_title'];
    $hero_desc     = $edu_hero['desc'] ?? '';
    $hero_btn_text = $edu_hero['btn_text'] ?? $txt['hero_btn'];
    $hero_btn_url  = $buildLocalizedUrl($edu_hero['btn_url'] ?? '#');
    $hero_bg       = get_image_url($edu_hero['img'] ?? null, '/assets/img/education/hero.jpg');
    ?>
    <div class="row align-items-stretch g-5">
      <div class="col-lg-6">
        <div class="img-hero" style="background-image: url('<?php echo htmlspecialchars($hero_bg, ENT_QUOTES, 'UTF-8'); ?>'); background-size: cover; background-position: center; min-height: 350px; border-radius: 20px;"></div>
      </div>
      <div class="col-lg-6 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
        <div class="education-info pt-2">
          <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$hero_title, ENT_QUOTES, 'UTF-8'); ?></h2>
          <p class="main-p mb-4"><?php echo nl2br(htmlspecialchars((string)$hero_desc, ENT_QUOTES, 'UTF-8')); ?></p>
          <a href="<?php echo htmlspecialchars($hero_btn_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-education">
            <?php echo htmlspecialchars((string)$hero_btn_text, ENT_QUOTES, 'UTF-8'); ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ================= EDUCATION HERO SECTION END ================= -->

<!-- ================= 2. WHY STUDY SECTION START ================= -->
<section class="study py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduWhyModal" title="<?php echo htmlspecialchars($txt['edit_why'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $edu_why = $getLocalizedData('edu_why', []);
    
    $why_title = $edu_why['title'] ?? $txt['why_title'];
    $why_desc  = $edu_why['desc'] ?? '';
    $why_items = $edu_why['items'] ?? [];
    if (!is_array($why_items)) {
        $why_items = [];
    }
    ?>

    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$why_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($why_desc)): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars((string)$why_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>

    <div class="row g-3">
      <?php if (!empty($why_items)): ?>
        <?php foreach ($why_items as $item): 
          if (!is_array($item)) continue;
          $item_img   = get_image_url($item['img'] ?? null, '');
          $item_title = $item['title'] ?? '';
          $item_desc  = $item['desc'] ?? '';
        ?>
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="card choose-card h-100">
              <div class="card-body">
                <?php if (!empty($item_img)): ?>
                  <a href="#">
                    <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string)$item_title, ENT_QUOTES, 'UTF-8'); ?>" />
                  </a>
                <?php endif; ?>
                <h5 class="card-title"><?php echo htmlspecialchars((string)$item_title, ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars((string)$item_desc, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ================= WHY STUDY SECTION END ================= -->

<!-- ================= 3. TIMELINE SECTION START ================= -->
<section class="timeline-section py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduTimelineModal" title="<?php echo htmlspecialchars($txt['edit_timeline'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $edu_timeline   = $getLocalizedData('edu_timeline', []);
    $timeline_title = $edu_timeline['title'] ?? $txt['timeline_title'];
    $timeline_desc  = $edu_timeline['desc'] ?? '';
    $steps_items    = $edu_timeline['steps'] ?? ($edu_timeline['items'] ?? []);
    if (!is_array($steps_items)) {
        $steps_items = [];
    }
    ?>
    
    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$timeline_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($timeline_desc)): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars((string)$timeline_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>
    
    <?php if (!empty($steps_items)): ?>
      <div class="map-container d-none d-lg-block">
        <div class="map-box">
          <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="base" class="line-base">
          <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector-1.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="active" class="line-active">
          
          <?php 
          $dots = ['bg-blue', 'bg-green', 'bg-yellow', 'bg-orange', 'bg-orange', 'bg-red'];
          foreach ($steps_items as $idx => $step): 
              if (!is_array($step)) continue;
              $num = sprintf("%02d", (int)$idx + 1);
              $dotClass = $dots[(int)$idx % count($dots)];
              
              $defaultIcon = 'assets/img/vector/Grouptime' . ((int)$idx + 1) . '.png';
              $iconPath = get_image_url($step['icon'] ?? null, $defaultIcon);
              $groupNumImg = get_image_url('assets/img/vector/Group' . ((int)$idx + 1) . '.png');
              
              $stepNumberClass = 'step-' . ((int)$idx + 1);
          ?>
            <div class="step-wrapper <?php echo htmlspecialchars($stepNumberClass, ENT_QUOTES, 'UTF-8'); ?>">
              <img src="<?php echo htmlspecialchars($groupNumImg, ENT_QUOTES, 'UTF-8'); ?>" class="step-img-num" alt="<?php echo $num; ?>">
              <div class="icon-main">
                <img src="<?php echo htmlspecialchars($iconPath, ENT_QUOTES, 'UTF-8'); ?>" alt="">
              </div>
              <div class="info-content">
                <h3><?php echo htmlspecialchars((string)($step['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
                <span class="dot <?php echo $dotClass; ?>"></span>
                <h4><?php echo htmlspecialchars((string)($step['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h4>
                <p><?php echo htmlspecialchars((string)($step['desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="mobile-timeline d-lg-none">
        <?php foreach ($steps_items as $idx => $step): 
            if (!is_array($step)) continue;
            $num = sprintf("%02d", (int)$idx + 1);
            $defaultIcon = 'assets/img/vector/Grouptime' . ((int)$idx + 1) . '.png';
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
                <h3><?php echo htmlspecialchars((string)($step['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
              </div>
              <h4><?php echo htmlspecialchars((string)($step['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h4>
              <p><?php echo htmlspecialchars((string)($step['desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center text-muted py-4">
        <p><?php echo htmlspecialchars($txt['no_steps'], ENT_QUOTES, 'UTF-8'); ?></p>
      </div>
    <?php endif; ?>
  </div>
</section>
<!-- ================= TIMELINE SECTION END ================= -->

<!-- ================= 4. EDUCATION SERVICES SECTION START ================= -->
<section class="edu-services py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#eduServicesModal" title="<?php echo htmlspecialchars($txt['edit_services'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $edu_services = $getLocalizedData('edu_services', []);
    
    $services_title = $edu_services['title'] ?? ($edu_services['section_title'] ?? $txt['services_title']);
    $services_desc  = $edu_services['desc'] ?? ($edu_services['section_subtitle'] ?? '');
    $services_items = $edu_services['items'] ?? [];
    if (!is_array($services_items)) {
        $services_items = [];
    }
    ?>

    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$services_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($services_desc)): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars((string)$services_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>
    
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 text-center">
      <?php if (!empty($services_items)): ?>
        <?php foreach ($services_items as $item): 
            if (!is_array($item)) continue;
            $final_url  = $buildLocalizedUrl($item['url'] ?? '#', true);
            $bg_img     = get_image_url($item['img'] ?? null, '/assets/img/home/education.jpg');
            $item_title = $item['title'] ?? '';
        ?>
          <div class="col">
            <a href="<?php echo htmlspecialchars($final_url, ENT_QUOTES, 'UTF-8'); ?>" class="text-decoration-none">
              <div class="card service-card text-white border-0 rounded-5"
                style="background-image: url('<?php echo htmlspecialchars($bg_img, ENT_QUOTES, 'UTF-8'); ?>');">
                <div class="card-body d-flex align-items-end justify-content-center">
                  <h6 class="card-title m-0">
                    <?php echo htmlspecialchars((string)$item_title, ENT_QUOTES, 'UTF-8'); ?>
                  </h6>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p><?php echo htmlspecialchars($txt['no_services'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ================= EDUCATION SERVICES SECTION END ================= -->

<?php 
// تضمين موديلات التعديل الخاصة بصفحة التعليم للمشرف فقط
$edu_modals_file = __DIR__ . '/admin/admin_edu_modals.php';
if ($is_admin_active && is_file($edu_modals_file)) { 
    include_once $edu_modals_file; 
} 
?>
