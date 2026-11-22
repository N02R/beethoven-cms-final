<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Medical Specialties Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'voc_training'  => 'Berufsausbildung',
        'default_bc'    => 'Medizinische Fachrichtungen',
        'default_title' => 'Liste der häufigsten medizinischen Fachrichtungen',
        'default_sub'   => 'Wählen Sie Ihre medizinische Fachrichtung',
        'download_lbl'  => 'Herunterladen',
        'pdf_alt'       => 'PDF-Datei',
        'word_alt'      => 'Word-Datei',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_card'     => 'Download-Dateien bearbeiten',
    ],
    'en' => [
        'home'          => 'Home',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Medical Specialties',
        'default_title' => 'List of Most Common Medical Specialties',
        'default_sub'   => 'Choose your medical specialty',
        'download_lbl'  => 'Download',
        'pdf_alt'       => 'PDF File',
        'word_alt'      => 'Word File',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_card'     => 'Edit Download Files',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'التخصصات الطبية',
        'default_title' => 'قائمة أكثر التخصصات الطبية انتشارا',
        'default_sub'   => 'اختر تخصصك الطبي',
        'download_lbl'  => 'تحميل',
        'pdf_alt'       => 'ملف PDF',
        'word_alt'      => 'ملف Word',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف',
        'edit_card'     => 'تعديل ملفات التحميل',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$medical_spec_data = is_array($medical_spec_data ?? null)
    ? $medical_spec_data
    : (is_array($medical_specialties_page ?? null) ? $medical_specialties_page : ($data['medical_spec_data'] ?? []));

$is_admin_active = !empty($is_admin) && $is_admin === true;
$prefix = '/' . $current_lang;
?>

<!-- ================= BREADCRUMB SECTION START ================= -->
<div class="custom-container pt-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#medSpecBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $medical_spec_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $medical_spec_data['page_breadcrumb'] ?? $txt['default_bc'];
    ?>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($prefix, ENT_QUOTES, 'UTF-8'); ?>/home"><?php echo htmlspecialchars($txt['home'], ENT_QUOTES, 'UTF-8'); ?></a></li>
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($prefix, ENT_QUOTES, 'UTF-8'); ?>/job"><?php echo htmlspecialchars($txt['voc_training'], ENT_QUOTES, 'UTF-8'); ?></a></li>
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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#medSpecHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($medical_spec_data['hero_img'] ?? null, 'assets/img/job/servicesimg1.png');
    $hero_pos = htmlspecialchars((string)($medical_spec_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="custom-container">
      <div class="coverLetter-hero custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars($hero_img, ENT_QUOTES, 'UTF-8'); ?>'); background-position: <?php echo $hero_pos; ?>;">
      </div>
    </div>
  </div>
</section>
<!-- custom-services end -->

<!-- ================= CUSTOM SERVICES INFO SECTION START ================= -->
<section class="custom-services-info py-5" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="custom-container">
    
    <!-- العنوان والوصف الرئيسي -->
    <div class="head-info pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#medSpecMainModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars((string)($medical_spec_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($medical_spec_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>
    
    <!-- كرت التحميل -->
    <div class="dl-card py-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#medSpecCardModal" title="<?php echo htmlspecialchars($txt['edit_card'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row">
          <?php 
          $download_items = $medical_spec_data['download_items'] ?? [];
          if (empty($download_items) && isset($medical_spec_data['download_item'])) {
              $download_items = [$medical_spec_data['download_item']];
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
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="download-card <?php echo (!$is_last && $total_items > 1) ? 'mb-3' : ''; ?>">
                <div class="download-row">
                  <img src="<?php echo htmlspecialchars(get_image_url($icon_img), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8'); ?>" class="dl-icon" />
                  <div class="dl-info">
                    <div class="dl-title"><?php echo htmlspecialchars((string)($item['title'] ?? $txt['default_title']), ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php if (!empty($item['sub'])): ?>
                      <div class="dl-sub"><?php echo htmlspecialchars((string)$item['sub'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                  </div>
                  <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.......................................................................................................................................................................................................................</span>
                  <a class="download-link" href="<?php echo $file_url; ?>" download><?php echo htmlspecialchars($txt['download_lbl'], ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
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
$med_spec_modals_file = __DIR__ . '/includes/admin_medical_specialties_modals.php';
if ($is_admin_active && file_exists($med_spec_modals_file)) { 
    include_once $med_spec_modals_file; 
}
?>
