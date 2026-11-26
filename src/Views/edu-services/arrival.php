<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Arrival Service Page View (Gold Standard MVC, Multilingual & Security Hardened)
 * متطابق بالكامل مع المعيار الهندسي المعتمد في education.php و job.php و home.php.
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
        'home'          => 'Startseite',
        'higher_edu'    => 'Höhere Bildung',
        'voc_training'  => 'Berufsausbildung',
        'default_bc'    => 'Flughafentransfer, Transport, Unterkunft & Wohnen',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bereich bearbeiten',
        'edit_main'     => 'Haupttitel bearbeiten',
        'edit_tips'     => 'Tipps & Empfehlungen bearbeiten',
        'edit_notes'    => 'Wichtige Hinweise bearbeiten',
        'no_tips'       => 'Derzeit sind keine Tipps verfügbar.',
        'no_notes'      => 'Derzeit sind keine Hinweise hinterlegt.'
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Airport Reception, Transportation, Accommodation & Housing',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Section',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_tips'     => 'Edit Tips & Recommendations',
        'edit_notes'    => 'Edit Important Notes',
        'no_tips'       => 'No tips are currently available.',
        'no_notes'      => 'No notes are currently available.'
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'الإستقبال في المطار، المواصلات، الإقامة والسكن',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل الهيدر والصورة',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_tips'     => 'تعديل التوصيات والنصائح',
        'edit_notes'    => 'تعديل الملاحظات الهامة',
        'no_tips'       => 'لا توجد نصائح مضافة حالياً.',
        'no_notes'      => 'لا توجد ملاحظات مضافة حالياً.'
    ]
];
$txt = $i18nDefaults[$current_lang];

// تهيئة متغيرات البيانات والإعدادات بأمان تام
$data = is_array($data ?? null) ? $data : [];
$currentSettings = is_array($currentSettings ?? null) ? $currentSettings : (is_array($settings ?? null) ? $settings : []);

// 0. قراءة الإعداد الخام كما هو (بما في ذلك shared_img و shared_items_img)
$decodeRawSetting = static function (string $settingKey, mixed $default = []) use ($data, $currentSettings): mixed {
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

// 2. استخراج صورة خدمة محددة (مثل arrival) من قسم الخدمات الموحد (edu_services أو job_services)
$resolveServiceSharedImage = static function (string $slugNeedle, array $servicesRaw, array $servicesLocalized): ?string {
    $sharedImgs = is_array($servicesRaw['shared_items_img'] ?? null) ? $servicesRaw['shared_items_img'] : [];
    $items = is_array($servicesLocalized['items'] ?? null) ? $servicesLocalized['items'] : [];

    foreach ($items as $idx => $item) {
        if (!is_array($item)) {
            continue;
        }
        $url = strtolower(trim((string)($item['url'] ?? '')));
        if ($slugNeedle !== '' && str_contains($url, strtolower($slugNeedle))) {
            $candidate = $sharedImgs[$idx] ?? ($item['img'] ?? null);
            if (is_string($candidate) && trim($candidate) !== '') {
                return trim($candidate);
            }
        }
    }

    return null;
};

// تحديد المصدر بناءً على الرابط القادم بأمان
$from = $_GET['from'] ?? 'education';
if ($from === 'job') {
    $parent_url = '/' . $current_lang . '/job';
    $parent_name = $txt['voc_training'];
    $serviceSectionKey = 'job_services';
} else {
    $parent_url = '/' . $current_lang . '/education';
    $parent_name = $txt['higher_edu'];
    $serviceSectionKey = 'edu_services';
}

$is_admin_active = !empty($is_admin) && $is_admin === true;

// جلب بيانات صفحة الوصول المترجمة + صورة الخدمة الموحدة من قسم الخدمات
$arrival_data = $getLocalizedData('arrival_data', is_array($arrival_data ?? null) ? $arrival_data : []);
$arrival_data = is_array($arrival_data) ? $arrival_data : [];

$servicesRaw = $decodeRawSetting($serviceSectionKey, []);
$servicesRaw = is_array($servicesRaw) ? $servicesRaw : [];
$servicesLocalized = $getLocalizedData($serviceSectionKey, []);
$servicesLocalized = is_array($servicesLocalized) ? $servicesLocalized : [];

$matchedServiceImg = $resolveServiceSharedImage('arrival', $servicesRaw, $servicesLocalized);
?>

<!-- ================= BREADCRUMB SECTION START ================= -->
<div class="custom-container pt-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $arrival_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $arrival_data['page_breadcrumb'] ?? $txt['default_bc'];
    ?>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="/<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>/home"><?php echo htmlspecialchars($txt['home'], ENT_QUOTES, 'UTF-8'); ?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($parent_url, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($parent_name, ENT_QUOTES, 'UTF-8'); ?></a></li>
        <li class="breadcrumb-item" aria-current="page">
          <a href="<?php echo htmlspecialchars($bc_url, ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($bc_text, ENT_QUOTES, 'UTF-8'); ?>
          </a>
        </li>
      </ol>
    </nav>
  </div>
</div>
<!-- Breadcrumb end -->

<!-- ================= CUSTOM HERO SECTION START ================= -->
<section class="custom-services py-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $rawHeroSource = $matchedServiceImg ?? ($arrival_data['hero_img'] ?? null);
    $hero_img = get_image_url($rawHeroSource, 'assets/img/education/servicesimg9.png');
    ?>
    <div class="custom-container">
      <div class="arrival-hero custom-hero" style="background-image: url('<?php echo htmlspecialchars($hero_img, ENT_QUOTES, 'UTF-8'); ?>');">
      </div>
    </div>
  </div>
</section>
<!-- custom-services end -->

<!-- ================= CUSTOM SERVICES INFO SECTION START ================= -->
<section class="custom-services-info py-5" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="custom-container">
    
    <!-- 1. قسم العنوان الرئيسي والوصف -->
    <div class="head-info pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalMainTitleModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text mb-3"><?php echo htmlspecialchars((string)($arrival_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($arrival_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 2. قسم النصائح والإرشادات -->
    <div class="advice-check py-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalTipsModal" title="<?php echo htmlspecialchars($txt['edit_tips'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-3"><?php echo htmlspecialchars((string)($arrival_data['advice_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h5>
        <p class="mb-3"><?php echo htmlspecialchars((string)($arrival_data['advice_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
        
        <div class="row mt-3">
          <?php 
          $tips_items = $arrival_data['tips'] ?? [];
          if (is_array($tips_items) && !empty($tips_items)):
              foreach ($tips_items as $tip): 
                  if (empty($tip)) continue;
          ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
              <p>✅ <?php echo htmlspecialchars((string)$tip, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php 
              endforeach; 
          else:
          ?>
            <div class="col-12 text-muted">
              <p><?php echo htmlspecialchars($txt['no_tips'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 3. قسم الملاحظات الهامة بالنجوم -->
    <div class="advice-stars my-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#arrivalNotesModal" title="<?php echo htmlspecialchars($txt['edit_notes'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-4 note-text"><?php echo htmlspecialchars((string)($arrival_data['note_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h5>
        <ul class="star-list list-unstyled">
          <?php 
          $notes_items = $arrival_data['notes'] ?? [];
          if (is_array($notes_items) && !empty($notes_items)):
              foreach ($notes_items as $note): 
                  if (empty($note)) continue;
          ?>
            <li class="mb-2">
              <p class="d-flex align-items-start">
                <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 flex-shrink-0" width="25">
                <span><?php echo (string)$note; // يُترك كما هو في حال احتوى على وسوم HTML آمنة مثل الرابط الأصلي ?></span>
              </p>
            </li>
          <?php 
              endforeach; 
          else:
          ?>
            <li class="text-muted">
              <p><?php echo htmlspecialchars($txt['no_notes'], ENT_QUOTES, 'UTF-8'); ?></p>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
// تضمين المودالات الإدارية الخاصة بصفحة الوصول للمشرف فقط
$arrival_modals_file = __DIR__ . '/includes/admin_arrival_modals.php';
if ($is_admin_active && is_file($arrival_modals_file)) { 
    include_once $arrival_modals_file; 
}
?>
