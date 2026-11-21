<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Services Price List Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Preisliste der Leistungen',
        'default_title' => 'Allgemeine Preisliste',
        'download_lbl'  => 'Herunterladen',
        'pdf_alt'       => 'PDF-Datei',
        'word_alt'      => 'Word-Datei',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_card'     => 'Download-Datei bearbeiten',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Services Price List',
        'default_title' => 'General Price List',
        'download_lbl'  => 'Download',
        'pdf_alt'       => 'PDF File',
        'word_alt'      => 'Word File',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_card'     => 'Edit Download File',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'قائمة أسعار الخدمات',
        'default_title' => 'قائمة الأسعار العامة',
        'download_lbl'  => 'تحميل',
        'pdf_alt'       => 'ملف PDF',
        'word_alt'      => 'ملف Word',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_card'     => 'تعديل ملف التحميل',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$pricelist_data = is_array($pricelist_data ?? null)
    ? $pricelist_data
    : (is_array($pricelist_page ?? null) ? $pricelist_page : ($data['pricelist_data'] ?? []));

$prefix = '/' . $current_lang;
$from = $_GET['from'] ?? 'education';
if ($from === 'job') {
    $parent_url  = $prefix . '/job';
    $parent_name = $txt['voc_training'];
} else {
    $parent_url  = $prefix . '/education';
    $parent_name = $txt['higher_edu'];
}

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= BREADCRUMB SECTION START ================= -->
<div class="custom-container pt-5 editable-wrapper" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="edit-board-box">
    <?php if ($is_admin_active): ?>
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $pricelist_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $pricelist_data['page_breadcrumb'] ?? $txt['default_bc'];
    ?>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($prefix, ENT_QUOTES, 'UTF-8'); ?>/home"><?php echo htmlspecialchars($txt['home'], ENT_QUOTES, 'UTF-8'); ?></a></li>
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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($pricelist_data['hero_img'] ?? null, 'assets/img/education/servicesimg15.png');
    $hero_pos = htmlspecialchars((string)($pricelist_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="custom-container">
      <div class="custom-hero" 
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
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListMainModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars((string)($pricelist_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($pricelist_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>
    
    <!-- كرت التحميل -->
    <div class="dl-card py-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#priceListCardModal" title="<?php echo htmlspecialchars($txt['edit_card'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="row mt-3">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <?php
            $item = is_array($pricelist_data['download_item'] ?? null) && !empty($pricelist_data['download_item'])
                ? $pricelist_data['download_item']
                : (is_array($pricelist_data['download_items'][0] ?? null) ? $pricelist_data['download_items'][0] : []);

            $file_type = strtolower((string)($item['type'] ?? 'pdf'));
            $is_pdf    = ($file_type === 'pdf' || $file_type === '');
            $icon      = $is_pdf ? 'assets/img/Grouppdf.webp' : 'assets/img/Groupword.webp';
            $alt_text  = $is_pdf ? $txt['pdf_alt'] : $txt['word_alt'];

            $raw_file = trim((string)($item['file'] ?? '#'));
            $file_url = ($raw_file !== '' && $raw_file !== '#')
                ? htmlspecialchars('/' . ltrim($raw_file, '/'), ENT_QUOTES, 'UTF-8')
                : '#';
            ?>
            <div class="download-card">
              <div class="download-row">
                <!-- الأيقونة -->
                <img src="<?php echo htmlspecialchars(get_image_url($icon), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8'); ?>" class="dl-icon" />
                
                <!-- العنوان والنص الفرعي -->
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars((string)($item['title'] ?? $txt['default_title']), ENT_QUOTES, 'UTF-8'); ?></div>
                  <?php if (!empty($item['sub'])): ?>
                    <div class="dl-sub"><?php echo htmlspecialchars((string)$item['sub'], ENT_QUOTES, 'UTF-8'); ?></div>
                  <?php endif; ?>
                </div>
                
                <!-- النقاط الفاصلة -->
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.......................................................................................................................................................................................................................</span>
                
                <!-- زر التحميل -->
                <a class="download-link" href="<?php echo $file_url; ?>" download><?php echo htmlspecialchars($txt['download_lbl'], ENT_QUOTES, 'UTF-8'); ?></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
$pricelist_modals_file = __DIR__ . '/includes/admin_pricelist_modals.php';
if ($is_admin_active && file_exists($pricelist_modals_file)) { 
    include_once $pricelist_modals_file; 
}
?>
