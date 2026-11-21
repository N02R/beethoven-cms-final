<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Cover Letter Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Anschreiben',
        'default_title' => 'Professionelles Anschreiben / Motivationsschreiben',
        'advice_title'  => 'Wichtige Punkte zum Beachten',
        'note_title'    => 'Wichtige Hinweise !!',
        'download_lbl'  => 'Herunterladen',
        'default_sub'   => 'Beispiel',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Titel & Beschreibung bearbeiten',
        'edit_advice'   => 'Punkte & Tipps bearbeiten',
        'edit_notes'    => 'Wichtige Hinweise bearbeiten',
        'edit_download' => 'Download-Elemente bearbeiten',
        'no_items'      => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Cover Letter',
        'default_title' => 'Professional Cover Letter / Application Letter',
        'advice_title'  => 'Key Points to Consider',
        'note_title'    => 'Important Notes !!',
        'download_lbl'  => 'Download',
        'default_sub'   => 'Example',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Title & Description',
        'edit_advice'   => 'Edit Points & Tips',
        'edit_notes'    => 'Edit Important Notes',
        'edit_download' => 'Edit Download Items',
        'no_items'      => 'No items are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'خطاب الطلب',
        'default_title' => 'رسالة تعريف / خطاب طلب احترافي',
        'advice_title'  => 'النقاط التي يجب مراعاتها',
        'note_title'    => 'ملاحظات هامة !!',
        'download_lbl'  => 'تحميل',
        'default_sub'   => 'نموذج',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_advice'   => 'تعديل النقاط والنصائح',
        'edit_notes'    => 'تعديل الملاحظات الهامة',
        'edit_download' => 'تعديل عناصر التحميل',
        'no_items'      => 'لا توجد عناصر مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$cover = is_array($cover ?? null) ? $cover : (is_array($coverletter_page ?? null) ? $coverletter_page : ($data['coverletter_page'] ?? []));

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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $cover['page_breadcrumb_url'] ?? '#';
    $bc_text = $cover['page_breadcrumb'] ?? $txt['default_bc'];
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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($cover['hero_img'] ?? null, 'assets/img/education/servicesimg1.jpg');
    $hero_pos = htmlspecialchars((string)($cover['hero_position'] ?? 'center -30px'), ENT_QUOTES, 'UTF-8');
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
    
    <!-- 1. العنوان والوصف -->
    <div class="head-info pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverMainModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars((string)($cover['main_title'] ?? $txt['default_title']), ENT_QUOTES, 'UTF-8'); ?></h2>
        <?php if (!empty($cover['main_desc'])): ?>
          <p class="par-text"><?php echo nl2br(htmlspecialchars((string)$cover['main_desc'], ENT_QUOTES, 'UTF-8')); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- 2. النصائح والنقاط -->
    <div class="advice-stars my-4 pb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverAdviceModal" title="<?php echo htmlspecialchars($txt['edit_advice'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars((string)($cover['advice_title'] ?? $txt['advice_title']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="row star-list mt-4">
          <?php 
          $advice_points = $cover['advice_points'] ?? [];
          if (is_array($advice_points) && !empty($advice_points)):
              foreach ($advice_points as $point): 
                  if (empty($point)) continue;
          ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-3">
              <div class="d-flex align-items-center">
                <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" class="ms-2 me-2 flex-shrink-0" alt="" width="25">
                <p class="mb-0"><?php echo htmlspecialchars((string)$point, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          <?php 
              endforeach;
          else:
          ?>
            <div class="col-12 text-muted">
              <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 3. ملاحظات هامة -->
    <div class="advice-check py-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverNotesModal" title="<?php echo htmlspecialchars($txt['edit_notes'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="note-text mb-4"><?php echo htmlspecialchars((string)($cover['note_title'] ?? $txt['note_title']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="row">
          <?php 
          $notes_items = $cover['notes'] ?? [];
          if (is_array($notes_items) && !empty($notes_items)):
              foreach ($notes_items as $note): 
                  if (empty($note)) continue;
          ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-2">
              <p>✅ <?php echo htmlspecialchars((string)$note, ENT_QUOTES, 'UTF-8'); ?></p>
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
    </div>

    <!-- 4. عناصر التحميل -->
    <div class="row pt-2 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#coverDownloadModal" title="<?php echo htmlspecialchars($txt['edit_download'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
        $download_items = $cover['download_items'] ?? [];
        if (is_array($download_items) && !empty($download_items)):
            foreach ($download_items as $item): 
                if (!is_array($item)) continue;
                $is_pdf = (strtolower((string)($item['type'] ?? '')) === 'pdf');
                $icon = $is_pdf ? 'assets/img/Grouppdf.webp' : 'assets/img/Groupword.webp';
                $fileUrl = (string)($item['file'] ?? '#');
                $safeHref = ($fileUrl !== '' && $fileUrl !== '#')
                    ? get_image_url($fileUrl)
                    : '#';
        ?>
          <div class="col-lg-12">
            <div class="download-card mb-3">
              <div class="download-row">
                <!-- الأيقونة -->
                <img src="<?php echo htmlspecialchars(get_image_url($icon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="dl-icon" />
                
                <!-- العنوان والنص الفرعي -->
                <div class="dl-info">
                  <div class="dl-title"><?php echo htmlspecialchars((string)($item['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
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

  </div>
</section>
<!-- custom-services-info end -->

<?php 
$cover_modals_file = __DIR__ . '/includes/admin_cover_modals.php';
if ($is_admin_active && file_exists($cover_modals_file)) { 
    include_once $cover_modals_file; 
}
?>
