<?php
declare(strict_types=1);

/**
 * Beethoven CMS - German Language Courses Page View (Gold Standard MVC, Multilingual & Security Hardened)
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

$i18nDefaults = [
    'de' => [
        'home'          => 'Startseite',
        'higher_edu'    => 'Höhere Bildung',
        'voc_training'  => 'Berufsausbildung',
        'default_bc'    => 'Deutsch-Sprachkurse',
        'levels_ttl'    => 'Verfügbare Sprachniveaus (nach GER / CEFR)',
        'features_ttl'  => 'Vorteile unserer Sprachkurse',
        'tips_ttl'      => 'Tipps für ein erfolgreiches Studium auf Deutsch',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_levels'   => 'Sprachniveaus bearbeiten',
        'edit_features' => 'Kursvorteile bearbeiten',
        'edit_tips'     => 'Erfolgstipps bearbeiten',
        'no_items'      => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'German Language Courses',
        'levels_ttl'    => 'Available Levels (According to CEFR)',
        'features_ttl'  => 'Features of Our Courses',
        'tips_ttl'      => 'Tips for Succeeding in Studying in German',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_levels'   => 'Edit Available Levels',
        'edit_features' => 'Edit Course Features',
        'edit_tips'     => 'Edit Success Tips',
        'no_items'      => 'No items are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'دورات اللغة الألمانية',
        'levels_ttl'    => 'المستويات المتوفرة (طبقًا ل CEFR)',
        'features_ttl'  => 'مميزات دوراتنا',
        'tips_ttl'      => 'نصائح للنجاح في الدراسة بالألمانية',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_levels'   => 'تعديل المستويات',
        'edit_features' => 'تعديل مميزات دوراتنا',
        'edit_tips'     => 'تعديل نصائح للنجاح',
        'no_items'      => 'لا توجد عناصر مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$german_data = is_array($german_data ?? null) ? $german_data : (is_array($germanlang_page ?? null) ? $germanlang_page : ($data['german_data'] ?? []));

$from = $_GET['from'] ?? 'education';
if ($from === 'job') {
    $parent_url  = '/' . $current_lang . '/job';
    $parent_name = $txt['voc_training'];
} else {
    $parent_url  = '/' . $current_lang . '/education';
    $parent_name = $txt['higher_edu'];
}

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= BREADCRUMB SECTION START ================= -->
<div class="custom-container pt-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $german_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $german_data['page_breadcrumb'] ?? $txt['default_bc'];
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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($german_data['hero_img'] ?? null, 'assets/img/education/servicesimg4.png');
    $hero_pos = htmlspecialchars((string)($german_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="custom-container">
      <div class="germanlang-hero custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars($hero_img, ENT_QUOTES, 'UTF-8'); ?>'); background-position: <?php echo $hero_pos; ?>;">
      </div>
    </div>
  </div>
</section>
<!-- custom-services end -->

<!-- ================= CUSTOM SERVICES INFO SECTION START ================= -->
<section class="custom-services-info py-5" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="custom-container">
    
    <!-- القسم الرئيسي (العنوان والوصف) -->
    <div class="head-info pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanMainModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars((string)($german_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($german_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>
    
    <!-- 1. المستويات المتوفرة -->
    <div class="advice-stars my-5 py-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanLevelsModal" title="<?php echo htmlspecialchars($txt['edit_levels'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
        $levels_sec   = is_array($german_data['levels_section'] ?? null) ? $german_data['levels_section'] : [];
        $levels_title = (string)($levels_sec['title'] ?? $txt['levels_ttl']);
        $levels_list  = is_array($levels_sec['levels_list'] ?? null) ? $levels_sec['levels_list'] : [];
        ?>
        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($levels_title, ENT_QUOTES, 'UTF-8'); ?></h5>
        <ul class="star-list list-unstyled">
          <div class="row">
            <?php if (!empty($levels_list)): ?>
              <?php foreach ($levels_list as $level): 
                  if (empty($level)) continue;
              ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                  <li>
                    <p class="mb-0 d-flex align-items-start">
                      <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 flex-shrink-0" width="25"/>
                      <span><?php echo htmlspecialchars((string)$level, ENT_QUOTES, 'UTF-8'); ?></span>
                    </p>
                  </li>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-12 text-muted">
                <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            <?php endif; ?>
          </div>
        </ul>
      </div>
    </div>

    <!-- 2. مميزات دوراتنا -->
    <div class="advice-check py-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanFeaturesModal" title="<?php echo htmlspecialchars($txt['edit_features'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
        $feat_sec   = is_array($german_data['features_section'] ?? null) ? $german_data['features_section'] : [];
        $feat_title = (string)($feat_sec['title'] ?? $txt['features_ttl']);
        $feat_list  = is_array($feat_sec['features_list'] ?? null) ? $feat_sec['features_list'] : [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($feat_title, ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="row">
          <?php if (!empty($feat_list)): ?>
            <?php foreach ($feat_list as $feature): 
                if (empty($feature)) continue;
            ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars((string)$feature, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12 text-muted">
              <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 3. نصائح للنجاح في الدراسة بالألمانية -->
    <div class="advice-tips py-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#germanTipsModal" title="<?php echo htmlspecialchars($txt['edit_tips'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
        $tips_sec   = is_array($german_data['tips_section'] ?? null) ? $german_data['tips_section'] : [];
        $tips_title = (string)($tips_sec['title'] ?? $txt['tips_ttl']);
        $tips_list  = is_array($tips_sec['tips_list'] ?? null) ? $tips_sec['tips_list'] : [];
        ?>
        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($tips_title, ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="row">
          <?php if (!empty($tips_list)): ?>
            <?php foreach ($tips_list as $tip): 
                if (empty($tip)) continue;
            ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars((string)$tip, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12 text-muted">
              <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
$german_modals_file = __DIR__ . '/includes/admin_german_modals.php';
if ($is_admin_active && file_exists($german_modals_file)) { 
    include_once $german_modals_file; 
}
?>
