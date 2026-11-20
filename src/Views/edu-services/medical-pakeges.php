<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Medical Training Packages Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Medizinisches Praktikum-Paket',
        'note_ttl'      => 'Wichtige Hinweise !!',
        'contact_phrase'=> 'Kontakt aufnehmen',
        'download_lbl'  => 'Herunterladen',
        'pdf_alt'       => 'PDF-Datei',
        'word_alt'      => 'Word-Datei',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_notes'    => 'Hinweise bearbeiten',
        'edit_card'     => 'Download-Dateien bearbeiten',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Medical Training Package',
        'note_ttl'      => 'Important Notes !!',
        'contact_phrase'=> 'contact us',
        'download_lbl'  => 'Download',
        'pdf_alt'       => 'PDF File',
        'word_alt'      => 'Word File',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_notes'    => 'Edit Notes',
        'edit_card'     => 'Edit Download Files',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'باقة التدريب الطبي',
        'note_ttl'      => 'ملاحظات هامة !!',
        'contact_phrase'=> 'بالتواصل معنا',
        'download_lbl'  => 'تحميل',
        'pdf_alt'       => 'ملف PDF',
        'word_alt'      => 'ملف Word',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف',
        'edit_notes'    => 'تعديل الملاحظات',
        'edit_card'     => 'تعديل ملف التحميل',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$medical_data = is_array($medical_data ?? null)
    ? $medical_data
    : (is_array($medical_packages_page ?? null) ? $medical_packages_page : ($data['medical_data'] ?? []));

$from = $_GET['from'] ?? 'education';
if ($from === 'job') {
    $parent_url  = '/' . $current_lang . '/job';
    $parent_name = $txt['voc_training'];
} else {
    $parent_url  = '/' . $current_lang . '/education';
    $parent_name = $txt['higher_edu'];
}

$is_admin_active = !empty($is_admin) && $is_admin === true;
$contact_url     = '/' . $current_lang . '/contact';

$formatNoteWithContact = static function (string $rawNote) use ($contact_url, $txt): string {
    $safeNote = htmlspecialchars($rawNote, ENT_QUOTES, 'UTF-8');
    $contactAnchor = '<a href="' . htmlspecialchars($contact_url, ENT_QUOTES, 'UTF-8') . '" class="fw-bold" style="color: #66aeee; text-decoration: none;">' . htmlspecialchars($txt['contact_phrase'], ENT_QUOTES, 'UTF-8') . '</a>';
    $phrases = ['بالتواصل معنا', 'contact us', 'Kontakt aufnehmen'];
    return nl2br(str_ireplace($phrases, $contactAnchor, $safeNote));
};
?>

<!-- ================= BREADCRUMB SECTION START ================= -->
<div class="custom-container pt-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $bc_url  = $medical_data['page_breadcrumb_url'] ?? '#';
  $bc_text = $medical_data['page_breadcrumb'] ?? $txt['default_bc'];
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
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $hero_img = get_image_url($medical_data['hero_img'] ?? null, 'assets/img/job/servicesimg3.png');
  $hero_pos = htmlspecialchars((string)($medical_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
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
    
    <!-- العنوان والوصف الرئيسي -->
    <div class="head-info pb-4 mb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>
      <h2 class="main-text"><?php echo htmlspecialchars((string)($medical_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
      <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($medical_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
    </div>

    <!-- ملاحظات هامة (قائمة متعددة ديناميكية) -->
    <div class="advice-stars pt-3 pb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_notes'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>
      <h5 class="note-text mb-3"><?php echo htmlspecialchars((string)($medical_data['note_title'] ?? $txt['note_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
      <?php
      $notesList = is_array($medical_data['notes_list'] ?? null) ? $medical_data['notes_list'] : [];
      if (empty($notesList) && !empty($medical_data['note_text'])) {
          $notesList = [(string)$medical_data['note_text']];
      }
      ?>
      <ul class="star-list list-unstyled">
        <?php foreach ($notesList as $noteItem):
            if (trim((string)$noteItem) === '') continue;
        ?>
          <li class="mb-2">
            <p class="mb-0 d-flex align-items-start">
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 mt-1 flex-shrink-0" width="25"/>
              <span><?php echo $formatNoteWithContact((string)$noteItem); ?></span>
            </p>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- كرت التحميل -->
    <div class="dl-card py-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#medicalCardModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_card'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <?php 
          $download_items = $medical_data['download_items'] ?? [];
          if (empty($download_items) && !empty($medical_data['download_item'])) {
              $download_items = [$medical_data['download_item']];
          }
          if (empty($download_items)) {
              $download_items = [[
                  'type'  => 'pdf',
                  'title' => $txt['default_bc'],
                  'sub'   => 'Example',
                  'file'  => 'assets/files/medical_training_agreement.pdf'
              ]];
          }

          $total_items = count($download_items);
          foreach ($download_items as $index => $item):
              if (!is_array($item)) continue;
              $file_type = strtolower((string)($item['type'] ?? 'pdf'));
              $is_pdf    = ($file_type === 'pdf');
              $icon_img  = $is_pdf ? 'assets/img/Grouppdf.webp' : 'assets/img/Groupword.webp';
              $alt_text  = $is_pdf ? $txt['pdf_alt'] : $txt['word_alt'];
              $is_last   = ($index === $total_items - 1);

              $raw_file = trim((string)($item['file'] ?? '#'));
              $file_url = ($raw_file !== '' && $raw_file !== '#')
                  ? htmlspecialchars('/' . ltrim($raw_file, '/'), ENT_QUOTES, 'UTF-8')
                  : '#';
          ?>
            <div class="download-card <?php echo !$is_last ? 'mb-3' : ''; ?>">
              <div class="download-row">
                <img src="<?php echo htmlspecialchars(get_image_url($icon_img), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8'); ?>" class="dl-icon" />
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars((string)($item['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
                  <?php if (!empty($item['sub'])): ?>
                    <div class="dl-sub"><?php echo htmlspecialchars((string)$item['sub'], ENT_QUOTES, 'UTF-8'); ?></div>
                  <?php endif; ?>
                </div>
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">............................................................................................................................................................................................</span>
                <a class="download-link" href="<?php echo $file_url; ?>" download><?php echo htmlspecialchars($txt['download_lbl'], ENT_QUOTES, 'UTF-8'); ?></a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
$medical_modals_file = __DIR__ . '/includes/admin_medical_modals.php';
if ($is_admin_active && file_exists($medical_modals_file)) { 
    include_once $medical_modals_file; 
}
?>
