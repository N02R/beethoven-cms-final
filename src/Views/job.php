<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Job Page View (Gold Standard MVC, Multilingual & Security Hardened)
 * متطابق بالكامل مع المعيار الهندسي المعتمد في home.php و about.php و education.php مع توحيد الصور المشتركة عبر اللغات.
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
        'hero_title'     => 'Arbeiten und Ausbildung in Deutschland',
        'hero_btn'       => 'Kontaktieren Sie uns',
        'why_title'      => 'Warum Ausbildung und Arbeit in Deutschland?',
        'prog_title'     => 'Ausbildungs- und Arbeitsprogramme',
        'prog_btn'       => 'Jetzt anfragen',
        'timeline_title' => 'Schritte zur Ausbildung und Arbeit in Deutschland',
        'services_title' => 'Arbeits- & Ausbildungsservices',
        'no_items'       => 'Derzeit sind keine Einträge verfügbar.',
        'no_programs'    => 'Derzeit sind keine Programme verfügbar.',
        'no_steps'       => 'Derzeit sind keine Schritte hinterlegt.',
        'no_services'    => 'Derzeit sind keine Dienstleistungen verfügbar.',
        'edit_hero'      => 'Hero-Bereich bearbeiten',
        'edit_why'       => 'Vorteile bearbeiten',
        'edit_programs'  => 'Programme bearbeiten',
        'edit_timeline'  => 'Schritte bearbeiten',
        'edit_services'  => 'Dienstleistungen bearbeiten'
    ],
    'en' => [
        'hero_title'     => 'Jobs & Vocational Training in Germany',
        'hero_btn'       => 'Contact Us',
        'why_title'      => 'Why Training & Employment in Germany?',
        'prog_title'     => 'Vocational Training & Employment Programs',
        'prog_btn'       => 'Apply Now',
        'timeline_title' => 'Steps to Employment & Training in Germany',
        'services_title' => 'Employment Services',
        'no_items'       => 'No items are currently available.',
        'no_programs'    => 'No programs are currently available.',
        'no_steps'       => 'No steps are currently available.',
        'no_services'    => 'No services are currently available.',
        'edit_hero'      => 'Edit Hero Section',
        'edit_why'       => 'Edit Why Section',
        'edit_programs'  => 'Edit Programs Section',
        'edit_timeline'  => 'Edit Timeline Steps',
        'edit_services'  => 'Edit Job Services'
    ],
    'ar' => [
        'hero_title'     => 'الوظائف والمهن في ألمانيا',
        'hero_btn'       => 'تواصل معنا',
        'why_title'      => 'لماذا التدريب والتوظيف في ألمانيا؟',
        'prog_title'     => 'برامج التدريب المهني والتوظيف',
        'prog_btn'       => 'اطلب الآن',
        'timeline_title' => 'خطوات التوظيف والتدريب في ألمانيا',
        'services_title' => 'خدمات التوظيف',
        'no_items'       => 'لا توجد عناصر مضافة حالياً.',
        'no_programs'    => 'لا توجد برامج مضافة حالياً.',
        'no_steps'       => 'لا توجد خطوات مضافة حالياً.',
        'no_services'    => 'لا توجد خدمات مضافة حالياً.',
        'edit_hero'      => 'تعديل الهيرو',
        'edit_why'       => 'تعديل لماذا التدريب',
        'edit_programs'  => 'تعديل أنواع التدريب',
        'edit_timeline'  => 'تعديل الخطوات',
        'edit_services'  => 'تعديل الخدمات'
    ]
];
$txt = $i18nDefaults[$current_lang];

// تهيئة متغيرات البيانات والإعدادات بأمان تام
$data = is_array($data ?? null) ? $data : [];
$currentSettings = is_array($currentSettings ?? null) ? $currentSettings : (is_array($settings ?? null) ? $settings : []);

// 0. دالة مساعدة لفك الإعداد الخام كـ Array دون فقدان المفاتيح المشتركة
$decodeRawSetting = function(string $settingKey, mixed $default = []) use ($data, $currentSettings): mixed {
    $raw = $data[$settingKey] ?? ($currentSettings[$settingKey] ?? (function_exists('get_setting') ? get_setting($settingKey, $default) : $default));
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : $default;
    }
    return is_array($raw) ? $raw : $default;
};

// 1. دالة موحدة لاستخراج بيانات أي قسم متعدد اللغات بأمان تام
$getLocalizedData = function(string $settingKey, mixed $default = []) use ($current_lang, $decodeRawSetting): mixed {
    $parsed = $decodeRawSetting($settingKey, $default);

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

// 2. دالة تنقية الروابط وربطها ببادئة اللغة الحالية والبادئة الموحدة services + from (منع XSS و Open Redirect)
$buildLocalizedUrl = function(?string $rawUrl, bool $isJobService = false, string $fromSource = 'job') use ($current_lang): string {
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

    $parts = explode('?', $url, 2);
    $pathPart = $parts[0];
    $queryPart = $parts[1] ?? '';

    $cleanPath = preg_replace('/^(ar|en|de)\//i', '', ltrim($pathPart, '/'));
    $cleanPath = trim((string)$cleanPath, '/');
    if ($cleanPath === '') {
        return '#';
    }

    if ($isJobService) {
        // توحيد بادئات الخدمات القديمة أو الـ slug المباشر إلى services/{slug}
        $cleanPath = preg_replace('/^(job-services|edu-services|services)\//i', '', $cleanPath);
        $cleanPath = 'services/' . ltrim((string)$cleanPath, '/');
    }

    $localized = '/' . $current_lang . '/' . $cleanPath;

    parse_str($queryPart, $queryParams);
    if (!is_array($queryParams)) {
        $queryParams = [];
    }
    if ($isJobService && $fromSource !== '' && !isset($queryParams['from'])) {
        $queryParams['from'] = $fromSource;
    }

    if (!empty($queryParams)) {
        $localized .= '?' . http_build_query($queryParams);
    }

    return $localized;
};

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= 1. JOB HERO SECTION START ================= -->
<section class="job py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php
      $jobHeroRaw = $decodeRawSetting('job_hero', []);
      $jobHeroRaw = is_array($jobHeroRaw) ? $jobHeroRaw : [];
      $job_hero   = $getLocalizedData('job_hero', []);
      $job_hero   = is_array($job_hero) ? $job_hero : [];

      $hero_title    = $job_hero['title'] ?? $txt['hero_title'];
      $hero_desc     = $job_hero['desc'] ?? '';
      $hero_btn_text = $job_hero['btn_text'] ?? $txt['hero_btn'];
      $hero_btn_url  = $buildLocalizedUrl($job_hero['btn_url'] ?? '#');
      $sharedHeroImg = $jobHeroRaw['shared_img'] ?? ($job_hero['img'] ?? ($jobHeroRaw['de']['img'] ?? ($jobHeroRaw['en']['img'] ?? ($jobHeroRaw['ar']['img'] ?? null))));
      $hero_bg       = get_image_url($sharedHeroImg, 'assets/img/job/jobhero.png');
      ?>
      <div class="row align-items-stretch g-5">
        <div class="col-lg-6">
          <div class="img-hero" style="background-image: url('<?php echo htmlspecialchars($hero_bg, ENT_QUOTES, 'UTF-8'); ?>'); background-size: cover; background-position: center; min-height: 350px; border-radius: 20px;"></div>
        </div>
        <div class="col-lg-6 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
          <div class="job-info pt-2">
            <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$hero_title, ENT_QUOTES, 'UTF-8'); ?></h2>
            <p class="main-p mb-4"><?php echo nl2br(htmlspecialchars((string)$hero_desc, ENT_QUOTES, 'UTF-8')); ?></p>
            <a href="<?php echo htmlspecialchars($hero_btn_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-job">
              <?php echo htmlspecialchars((string)$hero_btn_text, ENT_QUOTES, 'UTF-8'); ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ================= JOB HERO SECTION END ================= -->

<!-- ================= 2. WHY JOB SECTION START ================= -->
<section class="study py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobWhyModal" title="<?php echo htmlspecialchars($txt['edit_why'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php
      $jobWhyRaw     = $decodeRawSetting('job_why', []);
      $jobWhyRaw     = is_array($jobWhyRaw) ? $jobWhyRaw : [];
      $sharedWhyImgs = is_array($jobWhyRaw['shared_items_img'] ?? null) ? $jobWhyRaw['shared_items_img'] : [];
      $job_why       = $getLocalizedData('job_why', []);
      $job_why       = is_array($job_why) ? $job_why : [];

      $why_title = $job_why['title'] ?? $txt['why_title'];
      $why_desc  = $job_why['desc'] ?? '';
      $why_items = is_array($job_why['items'] ?? null) ? $job_why['items'] : [];
      ?>

      <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
        <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$why_title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <?php if (!empty($why_desc)): ?>
          <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars((string)$why_desc, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
      </div>

      <div class="row g-3">
        <?php if (!empty($why_items)): ?>
          <?php foreach ($why_items as $wIdx => $item):
            if (!is_array($item)) continue;
            $rawWhyImg  = $sharedWhyImgs[$wIdx] ?? ($item['img'] ?? null);
            $item_img   = get_image_url($rawWhyImg, '');
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
  </div>
</section>
<!-- ================= WHY JOB SECTION END ================= -->

<!-- ================= 3. PROGRAMS SECTION START ================= -->
<section class="program py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobProgramModal" title="<?php echo htmlspecialchars($txt['edit_programs'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php
      $jobProgramsRaw = $decodeRawSetting('job_programs', []);
      $jobProgramsRaw = is_array($jobProgramsRaw) ? $jobProgramsRaw : [];
      $sharedProgImgs = is_array($jobProgramsRaw['shared_items_img'] ?? null) ? $jobProgramsRaw['shared_items_img'] : [];
      $job_programs   = $getLocalizedData('job_programs', []);
      $job_programs   = is_array($job_programs) ? $job_programs : [];

      $prog_title = $job_programs['title'] ?? $txt['prog_title'];
      $prog_desc  = $job_programs['desc'] ?? '';
      $prog_items = is_array($job_programs['items'] ?? null) ? $job_programs['items'] : [];
      ?>

      <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
        <h2 class="sec-title mb-3 fw-bold"><?php echo htmlspecialchars((string)$prog_title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <?php if (!empty($prog_desc)): ?>
          <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars((string)$prog_desc, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
      </div>

      <div class="row g-4">
        <?php if (!empty($prog_items)): ?>
          <?php foreach ($prog_items as $pIdx => $p):
            if (!is_array($p)) continue;
            $rawProgImg    = $sharedProgImgs[$pIdx] ?? ($p['img'] ?? null);
            $item_img      = get_image_url($rawProgImg, '');
            $item_title    = $p['title'] ?? '';
            $item_desc     = $p['desc'] ?? '';
            $item_btn_text = $p['btn_text'] ?? $txt['prog_btn'];
            $item_btn_url  = $buildLocalizedUrl($p['btn_url'] ?? '#');
            $is_dark       = !empty($p['is_dark']) && ($p['is_dark'] === true || $p['is_dark'] === '1' || $p['is_dark'] === 1);
          ?>
            <div class="col-md-6">
              <div class="program-info h-100 <?php echo $is_dark ? 'highlight-box' : ''; ?>" style="border-radius: 25px; position: relative;">
                <div class="program-content">
                  <?php if (!empty($item_img)): ?>
                    <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string)$item_title, ENT_QUOTES, 'UTF-8'); ?>" class="mb-3">
                  <?php endif; ?>
                  <h4 class="fw-bold mb-4 <?php echo $is_dark ? 'text-white' : ''; ?>"><?php echo htmlspecialchars((string)$item_title, ENT_QUOTES, 'UTF-8'); ?></h4>
                  <p class="<?php echo $is_dark ? 'text-white' : ''; ?>">
                    <?php echo nl2br(htmlspecialchars((string)$item_desc, ENT_QUOTES, 'UTF-8')); ?>
                  </p>
                </div>
                <a href="<?php echo htmlspecialchars($item_btn_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn-info-wrapper mt-4 <?php echo $is_dark ? 'is-light' : ''; ?>">
                  <h3 class="mb-0"><?php echo htmlspecialchars((string)$item_btn_text, ENT_QUOTES, 'UTF-8'); ?></h3>
                  <div class="arrow-icon">
                    <img src="<?php echo htmlspecialchars(get_image_url('assets/img/home/ArrowLink.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>"
                         alt="Arrow"
                         style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
                  </div>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center text-muted py-4">
            <p><?php echo htmlspecialchars($txt['no_programs'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<!-- ================= PROGRAMS SECTION END ================= -->

<!-- ================= 4. TIMELINE SECTION START ================= -->
<section class="timeline-section py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobTimelineModal" title="<?php echo htmlspecialchars($txt['edit_timeline'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php
      $jobTimelineRaw = $decodeRawSetting('job_timeline', []);
      $jobTimelineRaw = is_array($jobTimelineRaw) ? $jobTimelineRaw : [];
      $sharedStepImgs = is_array($jobTimelineRaw['shared_steps_img'] ?? null)
          ? $jobTimelineRaw['shared_steps_img']
          : (is_array($jobTimelineRaw['shared_items_img'] ?? null) ? $jobTimelineRaw['shared_items_img'] : []);
      $job_timeline   = $getLocalizedData('job_timeline', []);
      $job_timeline   = is_array($job_timeline) ? $job_timeline : [];
      $timeline_title = $job_timeline['title'] ?? $txt['timeline_title'];
      $timeline_desc  = $job_timeline['desc'] ?? '';
      $rawSteps       = $job_timeline['steps'] ?? ($job_timeline['items'] ?? []);
      $steps_items    = is_array($rawSteps) ? $rawSteps : [];
      ?>

      <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
        <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$timeline_title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <?php if (!empty($timeline_desc)): ?>
          <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars((string)$timeline_desc, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
      </div>

      <?php if (!empty($steps_items)): ?>
        <?php
        $dots = ['bg-blue', 'bg-green', 'bg-yellow', 'bg-orange', 'bg-orange', 'bg-red'];
        $preparedSteps = [];
        foreach ($steps_items as $idx => $step) {
            if (!is_array($step)) continue;
            $stepIndex = (int)$idx + 1;
            $defaultIcon = 'assets/img/vector/Grouptime' . $stepIndex . '.png';
            $rawStepImg  = $sharedStepImgs[$idx] ?? ($step['icon'] ?? ($step['img'] ?? null));
            $preparedSteps[] = [
                'num'            => sprintf('%02d', $stepIndex),
                'dot_class'      => $dots[(int)$idx % count($dots)],
                'icon_path'      => get_image_url($rawStepImg, $defaultIcon),
                'group_num_img'  => get_image_url('assets/img/vector/Group' . $stepIndex . '.png'),
                'step_num_class' => 'step-' . $stepIndex,
                'title'          => (string)($step['title'] ?? ''),
                'subtitle'       => (string)($step['subtitle'] ?? ''),
                'desc'           => (string)($step['desc'] ?? ''),
            ];
        }

        $renderTimelineSteps = static function (array $items): void {
            foreach ($items as $pStep):
        ?>
              <div class="step-wrapper <?php echo htmlspecialchars($pStep['step_num_class'], ENT_QUOTES, 'UTF-8'); ?>">
                <img src="<?php echo htmlspecialchars($pStep['group_num_img'], ENT_QUOTES, 'UTF-8'); ?>" class="step-img-num" alt="<?php echo htmlspecialchars($pStep['num'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="icon-main">
                  <img src="<?php echo htmlspecialchars($pStep['icon_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
                </div>
                <div class="info-content">
                  <h3><?php echo htmlspecialchars($pStep['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                  <span class="dot <?php echo htmlspecialchars($pStep['dot_class'], ENT_QUOTES, 'UTF-8'); ?>"></span>
                  <h4><?php echo htmlspecialchars($pStep['subtitle'], ENT_QUOTES, 'UTF-8'); ?></h4>
                  <p><?php echo htmlspecialchars($pStep['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
              </div>
        <?php
            endforeach;
        };
        ?>

        <!-- عرض الديسكتوب (أفقي) -->
        <div class="map-container desktop-timeline d-none d-lg-block">
          <div class="map-box">
            <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="base" class="line-base">
            <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector-1.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="active" class="line-active">
            <?php $renderTimelineSteps($preparedSteps); ?>
          </div>
        </div>

        <!-- عرض الموبايل (الفيكتور واقف عمودياً من الأعلى للأسفل وقممه/قيعانه يميناً ويساراً) -->
        <div class="map-container mobile-timeline d-lg-none">
          <div class="map-box">
            <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="base" class="line-base">
            <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector-1.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="active" class="line-active">
            <?php $renderTimelineSteps($preparedSteps); ?>
          </div>
        </div>
      <?php else: ?>
        <div class="text-center text-muted py-4">
          <p><?php echo htmlspecialchars($txt['no_steps'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ================= TIMELINE SECTION END ================= -->

<!-- ================= 5. JOB SERVICES SECTION START ================= -->
<section class="edu-services py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#jobServicesModal" title="<?php echo htmlspecialchars($txt['edit_services'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <?php
      $jobServicesRaw = $decodeRawSetting('job_services', []);
      $jobServicesRaw = is_array($jobServicesRaw) ? $jobServicesRaw : [];
      $sharedSrvImgs  = is_array($jobServicesRaw['shared_items_img'] ?? null) ? $jobServicesRaw['shared_items_img'] : [];
      $job_services   = $getLocalizedData('job_services', []);
      $job_services   = is_array($job_services) ? $job_services : [];

      $services_title = $job_services['title'] ?? ($job_services['section_title'] ?? $txt['services_title']);
      $services_desc  = $job_services['desc'] ?? ($job_services['section_subtitle'] ?? '');
      $services_items = is_array($job_services['items'] ?? null) ? $job_services['items'] : [];
      ?>

      <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
        <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$services_title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <?php if (!empty($services_desc)): ?>
          <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars((string)$services_desc, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
      </div>

      <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 text-center">
        <?php if (!empty($services_items)): ?>
          <?php foreach ($services_items as $sIdx => $item):
              if (!is_array($item)) continue;
              $final_url  = $buildLocalizedUrl($item['url'] ?? '#', true, 'job');
              $rawSrvImg  = $sharedSrvImgs[$sIdx] ?? ($item['img'] ?? null);
              $bg_img     = get_image_url($rawSrvImg, 'assets/img/home/education.jpg');
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
  </div>
</section>
<!-- ================= JOB SERVICES SECTION END ================= -->

<?php
// تضمين موديلات التعديل الخاصة بصفحة الوظائف للمشرف فقط
$job_modals_file = __DIR__ . '/admin/admin_job_modals.php';
if ($is_admin_active && is_file($job_modals_file)) {
    include_once $job_modals_file;
}
?>
