<?php
declare(strict_types=1);

/**
 * Beethoven CMS - German Language Exam Prep Courses Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Vorbereitungskurs für Deutsch-Sprachzertifikate',
        'goals_title'   => 'Ziele des Vorbereitungskurses',
        'cost_title'    => 'Studienorte und Kosten',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_goals'    => 'Kursziele bearbeiten',
        'edit_warning'  => 'Hinweistext bearbeiten',
        'edit_cost'     => 'Studienorte & Kosten bearbeiten',
        'no_goals'      => 'Derzeit sind keine Kursziele hinterlegt.',
        'no_costs'      => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'German Language Certificate Preparatory Course',
        'goals_title'   => 'Preparatory Course Objectives',
        'cost_title'    => 'Enrollment Locations and Costs',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_goals'    => 'Edit Course Objectives',
        'edit_warning'  => 'Edit Warning & Conditions',
        'edit_cost'     => 'Edit Locations & Costs',
        'no_goals'      => 'No objectives are currently available.',
        'no_costs'      => 'No entries are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'الدورة التحضيرية لشهادات اللغة الألمانية',
        'goals_title'   => 'أهداف الدورة التحضيرية',
        'cost_title'    => 'أماكن الالتحاق والتكلفة',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_goals'    => 'تعديل أهداف الدورة',
        'edit_warning'  => 'تعديل نص التنبيه والشروط',
        'edit_cost'     => 'تعديل أماكن الالتحاق والتكاليف',
        'no_goals'      => 'لا توجد أهداف مضافة حالياً.',
        'no_costs'      => 'لا توجد بيانات مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$lang_data = is_array($lang_data ?? null) ? $lang_data : ($data['lang_data'] ?? ($data['courses_data'] ?? []));

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
<div class="custom-container pt-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#langBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $bc_url  = $lang_data['page_breadcrumb_url'] ?? '#';
  $bc_text = $lang_data['page_breadcrumb'] ?? $txt['default_bc'];
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
<!-- Breadcrumb end -->

<!-- ================= CUSTOM HERO SECTION START ================= -->
<section class="custom-services py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#langHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $hero_img = get_image_url($lang_data['hero_img'] ?? null, 'assets/img/education/servicesimg12.png');
  $hero_pos = htmlspecialchars((string)($lang_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
  ?>
  <div class="custom-container">
    <div class="coverLetter-hero custom-hero"
      style="background-image: url('<?php echo htmlspecialchars($hero_img, ENT_QUOTES, 'UTF-8'); ?>'); background-position: <?php echo $hero_pos; ?>;">
    </div>
  </div>
</section>
<!-- custom-services end -->

<!-- ================= CUSTOM SERVICES INFO SECTION START ================= -->
<section class="custom-services-info py-5" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="custom-container">
    
    <!-- 1. العنوان والوصف الرئيسي -->
    <div class="head-info pb-4 mb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#langMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <h2 class="main-text"><?php echo htmlspecialchars((string)($lang_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
      <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($lang_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
    </div>

    <!-- 2. أهداف الدورة التحضيرية -->
    <div class="advice-check py-4 mb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#langGoalsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_goals'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <h5 class="advice-text"><?php echo htmlspecialchars((string)($lang_data['goals_title'] ?? $txt['goals_title']), ENT_QUOTES, 'UTF-8'); ?></h5>
      <div class="row mt-3">
        <?php 
        $goals_items = $lang_data['goals'] ?? [];
        if (is_array($goals_items) && !empty($goals_items)):
            foreach ($goals_items as $goal): 
                if (empty($goal)) continue;
        ?>
          <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
            <p>✅ <?php echo htmlspecialchars((string)$goal, ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php 
            endforeach;
        else:
        ?>
          <div class="col-12 text-muted">
            <p><?php echo htmlspecialchars($txt['no_goals'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- 3. ملاحظة شروط القبول -->
    <div class="my-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#langWarningModal" style="position: absolute; top: -5px; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_warning'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <p class="red-text pt-2"><?php echo htmlspecialchars((string)($lang_data['warning_text'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <!-- 4. أماكن الالتحاق والتكلفة -->
    <div class="advice-list py-4 mt-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#langCostModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_cost'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($lang_data['cost_title'] ?? $txt['cost_title']), ENT_QUOTES, 'UTF-8'); ?></h5>
      <ul>
        <div class="row">
          <?php 
          $cost_items = $lang_data['cost_items'] ?? [];
          if (is_array($cost_items) && !empty($cost_items)):
              foreach ($cost_items as $item): 
                  if (!is_array($item)) continue;
          ?>
            <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
              <li><span><?php echo htmlspecialchars((string)($item['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span> <?php echo htmlspecialchars((string)($item['desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></li>
            </div>
          <?php 
              endforeach;
          else:
          ?>
            <div class="col-12 text-muted">
              <p><?php echo htmlspecialchars($txt['no_costs'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </ul>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
$courses_modals_file = __DIR__ . '/includes/admin_courses_modals.php';
if ($is_admin_active && file_exists($courses_modals_file)) { 
    include_once $courses_modals_file; 
}
?>
