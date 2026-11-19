<?php
declare(strict_types=1);

/**
 * Beethoven CMS - CV Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Lebenslauf (CV)',
        'default_title' => 'Lebenslauf „CV“',
        'advice_title'  => 'Schnelle Tipps für einen effektiven Lebenslauf',
        'download_lbl'  => 'Herunterladen',
        'default_sub'   => 'Beispiel',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Titel & Beschreibung bearbeiten',
        'edit_advice'   => 'Tipps bearbeiten',
        'edit_download' => 'Download-Elemente bearbeiten',
        'no_items'      => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Curriculum Vitae (CV)',
        'default_title' => 'Curriculum Vitae "CV"',
        'advice_title'  => 'Quick Tips for Writing an Effective CV',
        'download_lbl'  => 'Download',
        'default_sub'   => 'Example',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Title & Description',
        'edit_advice'   => 'Edit Tips',
        'edit_download' => 'Edit Download Items',
        'no_items'      => 'No items are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'السيرة الذاتية CV',
        'default_title' => 'السيرة الذاتية "CV"',
        'advice_title'  => 'نصائح سريعة لكتابة CV فعّال',
        'download_lbl'  => 'تحميل',
        'default_sub'   => 'نموذج',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_advice'   => 'تعديل النصائح',
        'edit_download' => 'تعديل عناصر التحميل',
        'no_items'      => 'لا توجد عناصر مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$cv_data = is_array($cv_data ?? null) ? $cv_data : (is_array($cv_page ?? null) ? $cv_page : ($data['cv_data'] ?? []));

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
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $bc_url  = $cv_data['page_breadcrumb_url'] ?? '#';
  $bc_text = $cv_data['page_breadcrumb'] ?? $txt['default_bc'];
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
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $hero_img = get_image_url($cv_data['hero_img'] ?? null, 'assets/img/education/servicesimg2.jpg');
  $hero_pos = htmlspecialchars((string)($cv_data['hero_position'] ?? 'center -30px'), ENT_QUOTES, 'UTF-8');
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
    
    <!-- 1. العنوان والوصف -->
    <div class="head-info pb-4 mb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <h2 class="main-text"><?php echo htmlspecialchars((string)($cv_data['main_title'] ?? $txt['default_title']), ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($cv_data['main_desc'])): ?>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)$cv_data['main_desc'], ENT_QUOTES, 'UTF-8')); ?></p>
      <?php endif; ?>
    </div>

    <!-- 2. النصائح -->
    <div class="advice-check pt-5 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvAdviceModal" style="position: absolute; top: 30px; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_advice'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <h5 class="advice-text"><?php echo htmlspecialchars((string)($cv_data['advice_title'] ?? $txt['advice_title']), ENT_QUOTES, 'UTF-8'); ?></h5>
      
      <div class="row mt-3">
        <?php 
        $advice_points = $cv_data['advice_points'] ?? [];
        if (is_array($advice_points) && !empty($advice_points)):
            foreach ($advice_points as $point): 
                if (empty($point)) continue;
        ?>
          <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
            <p>✅ <?php echo htmlspecialchars((string)$point, ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php 
            endforeach;
        else:
        ?>
          <div class="col-12 text-muted">
            <p><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- 3. التحميل (PDF & Word) -->
    <div class="row mt-5 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#cvDownloadModal" style="position: absolute; top: -20px; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_download'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <?php 
      $download_items = $cv_data['download_items'] ?? [];
      $total_items = is_array($download_items) ? count($download_items) : 0;
      if ($total_items > 0):
          foreach ($download_items as $index => $item):
              if (!is_array($item)) continue;
              $is_pdf  = (strtolower((string)($item['type'] ?? '')) === 'pdf');
              $icon    = $is_pdf ? 'assets/img/Grouppdf.webp' : 'assets/img/Groupword.webp';
              $is_last = ($index === $total_items - 1);
              $fileUrl = (string)($item['file'] ?? '#');
              $safeHref = ($fileUrl !== '' && $fileUrl !== '#')
                  ? get_image_url($fileUrl)
                  : '#';
      ?>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card <?php echo !$is_last ? 'mb-3' : ''; ?>">
            <div class="download-row">
              <!-- الأيقونة -->
              <img src="<?php echo htmlspecialchars(get_image_url($icon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="dl-icon" />
              
              <!-- العنوان والنص الفرعي -->
              <div class="dl-info">
                <div class="dl-title"><?php echo htmlspecialchars((string)($item['title'] ?? $txt['default_title']), ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="dl-sub"><?php echo htmlspecialchars((string)($item['sub'] ?? $txt['default_sub']), ENT_QUOTES, 'UTF-8'); ?></div>
              </div>
              
              <!-- النقاط الفاصلة المرنة -->
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.......................................................................................................................................................................................................................</span>
              
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
</section>
<!-- custom-services-info end -->

<?php 
$cv_modals_file = __DIR__ . '/includes/admin_cv_modals.php';
if ($is_admin_active && file_exists($cv_modals_file)) { 
    include_once $cv_modals_file; 
}
?>
