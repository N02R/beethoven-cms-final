<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Check Certificate Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Zeugnisanerkennung prüfen',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_notes'    => 'Wichtige Hinweise bearbeiten',
        'edit_links'    => 'Links & Anweisungen bearbeiten',
        'no_notes'      => 'Derzeit sind keine Hinweise hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Check Your Educational Certificates',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_notes'    => 'Edit Important Notes',
        'edit_links'    => 'Edit Links & Instructions',
        'no_notes'      => 'No notes are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'تحقق من شهاداتك التعليمية',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_notes'    => 'تعديل الملاحظات الهامة',
        'edit_links'    => 'تعديل الروابط والفقرات التوضيحية',
        'no_notes'      => 'لا توجد ملاحظات مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$check_data = is_array($check_data ?? null) ? $check_data : ($data['check_data'] ?? []);

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
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $bc_url  = $check_data['page_breadcrumb_url'] ?? '#';
  $bc_text = $check_data['page_breadcrumb'] ?? $txt['default_bc'];
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
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $hero_img = get_image_url($check_data['hero_img'] ?? null, 'assets/img/education/servicesimg13.png');
  $hero_pos = htmlspecialchars((string)($check_data['hero_position'] ?? 'center -9rem'), ENT_QUOTES, 'UTF-8');
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
    
    <!-- 1. قسم العنوان الرئيسي والوصف -->
    <div class="head-info pb-4 mb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkMainContentModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <h2 class="main-text"><?php echo htmlspecialchars((string)($check_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
      <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($check_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
    </div>

    <!-- 2. قسم الملاحظات الهامة (النجوم) -->
    <div class="advice-stars my-4 pb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_notes'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <h5 class="mb-4 note-text"><?php echo htmlspecialchars((string)($check_data['note_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h5>
      <ul class="star-list list-unstyled">
        <?php 
        $notes_items = $check_data['notes'] ?? [];
        if (is_array($notes_items) && !empty($notes_items)):
            foreach ($notes_items as $note): 
                if (empty($note)) continue;
        ?>
          <li class="mb-2">
            <p class="d-flex align-items-start">
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 flex-shrink-0" width="25"/>
              <span><?php echo (string)$note; ?></span>
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

    <!-- 3. قسم الروابط والتعليمات والنتيجة النهائية -->
    <div class="links pt-2 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#checkLinksModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_links'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <p class="mt-3"><?php echo htmlspecialchars((string)($check_data['links_intro'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
      
      <?php if (!empty($check_data['anabin_url'])): ?>
        <a href="<?php echo htmlspecialchars((string)$check_data['anabin_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars((string)$check_data['anabin_url'], ENT_QUOTES, 'UTF-8'); ?></a>
      <?php endif; ?>
      
      <?php if (!empty($check_data['uniassist_url'])): ?>
        <a href="<?php echo htmlspecialchars((string)$check_data['uniassist_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars((string)$check_data['uniassist_url'], ENT_QUOTES, 'UTF-8'); ?></a>
      <?php endif; ?>

      <p class="mt-5"><?php echo htmlspecialchars((string)($check_data['uni_contact_intro'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
      
      <span class="span-list"><?php echo htmlspecialchars((string)($check_data['condition_1'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
      <span class="span-list"><?php echo htmlspecialchars((string)($check_data['condition_2'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
      
      <!-- طباعة مباشرة لدعم وسوم الـ HTML المخصصة والتلوين بأمان -->
      <p class="mt-5"><?php echo (string)($check_data['conclusion_text'] ?? ''); ?></p>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
$check_modals_file = __DIR__ . '/includes/admin_check_modals.php';
if ($is_admin_active && file_exists($check_modals_file)) { 
    include_once $check_modals_file; 
}
?>
