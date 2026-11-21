<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Motivation Letter Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Motivationsschreiben',
        'advice_ttl'    => 'Kurze Tipps zum Verfassen eines Motivationsschreibens',
        'download_lbl'  => 'Herunterladen',
        'pdf_alt'       => 'PDF-Datei',
        'word_alt'      => 'Word-Datei',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_advice'   => 'Tipps zum Motivationsschreiben bearbeiten',
        'edit_dl'       => 'Download-Dateien verwalten',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Motivation Letter',
        'advice_ttl'    => 'Quick Tips for Writing a Motivation Letter',
        'download_lbl'  => 'Download',
        'pdf_alt'       => 'PDF File',
        'word_alt'      => 'Word File',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_advice'   => 'Edit Motivation Letter Tips',
        'edit_dl'       => 'Manage Download Files',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'خطاب الدافع / التحفيز',
        'advice_ttl'    => 'نصائح سريعة لكتابة خطاب الدافع',
        'download_lbl'  => 'تحميل',
        'pdf_alt'       => 'ملف PDF',
        'word_alt'      => 'ملف Word',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_advice'   => 'تعديل نصائح كتابة خطاب الدافع',
        'edit_dl'       => 'إدارة ملفات التحميل',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$motivation_data = is_array($motivation_data ?? null)
    ? $motivation_data
    : (is_array($motivation_page ?? null) ? $motivation_page : ($data['motivation_data'] ?? []));

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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $motivation_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $motivation_data['page_breadcrumb'] ?? $txt['default_bc'];
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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($motivation_data['hero_img'] ?? null, 'assets/img/education/servicesimg3.png');
    $hero_pos = htmlspecialchars((string)($motivation_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="custom-container">
      <div class="motivition-hero custom-hero" 
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
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationMainModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars((string)($motivation_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($motivation_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- قسم النصائح -->
    <div class="advice-check py-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationAdviceModal" title="<?php echo htmlspecialchars($txt['edit_advice'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
        $advice_sec   = is_array($motivation_data['advice_section'] ?? null) ? $motivation_data['advice_section'] : [];
        $advice_title = (string)($advice_sec['title'] ?? $txt['advice_ttl']);
        $advice_items = is_array($advice_sec['items'] ?? null) ? $advice_sec['items'] : [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($advice_title, ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="row gy-3">
          <?php if (!empty($advice_items)): ?>
            <?php foreach ($advice_items as $item):
                if (trim((string)$item) === '') continue;
            ?>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <p class="mb-0">✅ <?php echo htmlspecialchars((string)$item, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- قسم ملفات التحميل -->
    <div class="row gy-3 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#motivationDownloadModal" title="<?php echo htmlspecialchars($txt['edit_dl'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
        $downloads = is_array($motivation_data['download_items'] ?? null) ? $motivation_data['download_items'] : [];
        if (!empty($downloads)):
            foreach ($downloads as $dl):
                if (!is_array($dl)) continue;
                $dlType   = strtolower((string)($dl['type'] ?? 'pdf'));
                $is_pdf   = ($dlType === 'pdf');
                $icon_img = $is_pdf ? 'assets/img/Grouppdf.webp' : 'assets/img/Groupword.webp';
                $alt_text = $is_pdf ? $txt['pdf_alt'] : $txt['word_alt'];
                $rawFile  = trim((string)($dl['file'] ?? '#'));
                $fileUrl  = ($rawFile !== '' && $rawFile !== '#')
                    ? htmlspecialchars('/' . ltrim($rawFile, '/'), ENT_QUOTES, 'UTF-8')
                    : '#';
        ?>
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="download-card">
              <div class="download-row">
                <!-- الأيقونة -->
                <img src="<?php echo htmlspecialchars(get_image_url($icon_img), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8'); ?>" class="dl-icon" />
                
                <!-- العنوان والنص الفرعي -->
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars((string)($dl['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
                  <?php if (!empty($dl['sub'])): ?>
                    <div class="dl-sub"><?php echo htmlspecialchars((string)$dl['sub'], ENT_QUOTES, 'UTF-8'); ?></div>
                  <?php endif; ?>
                </div>
                
                <!-- النقاط الفاصلة المرنة -->
                <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................................................................................................................................</span>
                
                <!-- زر التحميل -->
                <a class="download-link" href="<?php echo $fileUrl; ?>" download><?php echo htmlspecialchars($txt['download_lbl'], ENT_QUOTES, 'UTF-8'); ?></a>
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
$motivation_modals_file = __DIR__ . '/includes/admin_motivation_modals.php';
if ($is_admin_active && file_exists($motivation_modals_file)) { 
    include_once $motivation_modals_file; 
}
?>
