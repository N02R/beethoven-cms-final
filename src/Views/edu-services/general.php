<?php
declare(strict_types=1);

/**
 * Beethoven CMS - General Visa Requirements Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Visabestimmungen',
        'default_notes' => 'Hinweis !!',
        'download_lbl'  => 'Herunterladen',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Titel & Beschreibung bearbeiten',
        'edit_notes'    => 'Hinweise bearbeiten',
        'edit_download' => 'Download-Dateien verwalten',
        'no_items'      => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Visa Requirements',
        'default_notes' => 'Note !!',
        'download_lbl'  => 'Download',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Title & Description',
        'edit_notes'    => 'Edit Notes',
        'edit_download' => 'Manage Download Files',
        'no_items'      => 'No items are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'متطلبات التأشيرة',
        'default_notes' => 'ملاحظة !!',
        'download_lbl'  => 'تحميل',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف',
        'edit_notes'    => 'تعديل الملاحظات',
        'edit_download' => 'إدارة ملفات التحميل',
        'no_items'      => 'لا توجد عناصر مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$visa_data = is_array($visa_data ?? null) ? $visa_data : (is_array($general_page ?? null) ? $general_page : ($data['visa_data'] ?? []));

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
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $bc_url  = $visa_data['page_breadcrumb_url'] ?? '#';
  $bc_text = $visa_data['page_breadcrumb'] ?? $txt['default_bc'];
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
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $hero_img = get_image_url($visa_data['hero_img'] ?? null, 'assets/img/education/servicesimg14.png');
  $hero_pos = htmlspecialchars((string)($visa_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
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
    
    <!-- 1. العنوان الرئيسي والوصف -->
    <div class="head-info pb-4 mb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <h2 class="main-text"><?php echo htmlspecialchars((string)($visa_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
      <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($visa_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
    </div>

    <!-- 2. الملاحظات (ديناميكية) -->
    <div class="advice-stars py-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_notes'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <?php 
      $notes_sec  = is_array($visa_data['notes_section'] ?? null) ? $visa_data['notes_section'] : [];
      $note_title = (string)($notes_sec['title'] ?? $txt['default_notes']);
      $notes_list = is_array($notes_sec['notes_list'] ?? null) ? $notes_sec['notes_list'] : [];
      ?>

      <h5 class="mb-3 note-text"><?php echo htmlspecialchars($note_title, ENT_QUOTES, 'UTF-8'); ?></h5>
      <ul class="star-list list-unstyled">
        <?php if (!empty($notes_list)): ?>
          <?php foreach ($notes_list as $note_text): 
              if (empty($note_text)) continue;
          ?>
            <li class="mb-2">
              <p class="mb-0 d-flex align-items-start">
                <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 flex-shrink-0" width="25" />
                <span><?php echo htmlspecialchars((string)$note_text, ENT_QUOTES, 'UTF-8'); ?></span>
              </p>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li class="text-muted">
            <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- 3. قائمة الملفات المتاحة للتحميل (ديناميكية) -->
    <div class="py-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#visaDownloadModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_download'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <div class="row">
        <?php 
        $download_items = $visa_data['download_items'] ?? [];
        if (is_array($download_items) && !empty($download_items)):
            foreach ($download_items as $item): 
                if (!is_array($item)) continue;
                $file_type = strtolower((string)($item['type'] ?? 'pdf'));
                $icon_file = ($file_type === 'word') ? 'Groupword.webp' : 'Grouppdf.webp'; 
                $icon_url  = get_image_url('assets/img/' . $icon_file);
                $rawFile   = trim((string)($item['file'] ?? '#'));
                $safeHref  = ($rawFile !== '' && $rawFile !== '#')
                    ? get_image_url($rawFile)
                    : '#';
        ?>
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="download-card mb-3">
              <div class="download-row">
                <!-- الأيقونة -->
                <img src="<?php echo htmlspecialchars($icon_url, ENT_QUOTES, 'UTF-8'); ?>" alt="ملف التحميل" class="dl-icon" />
                
                <!-- العنوان -->
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars((string)($item['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                
                <!-- النقاط الفاصلة -->
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................................................................</span>
                
                <!-- زر التحميل -->
                <a class="download-link" href="<?php echo htmlspecialchars($safeHref, ENT_QUOTES, 'UTF-8'); ?>" download><?php echo htmlspecialchars($txt['download_lbl'], ENT_QUOTES, 'UTF-8'); ?></a>
              </div>
            </div>
          </div>
        <?php 
            endforeach;
        endif;
        ?>
      </div>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
$visa_modals_file = __DIR__ . '/includes/admin_general_modals.php';
if ($is_admin_active && file_exists($visa_modals_file)) { 
    include_once $visa_modals_file; 
}
?>
