<?php
declare(strict_types=1);

/**
 * Beethoven CMS - English Language Study Programs Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Englischsprachige Studiengänge',
        'who_title'     => 'Wer kann von diesen Programmen profitieren',
        'who_subtitle'  => 'Alle, die folgende Voraussetzungen erfüllen:',
        'lang_title'    => 'Allgemeine Sprachanforderungen',
        'note_hl'       => 'Hinweis:',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_who'      => 'Voraussetzungen bearbeiten',
        'edit_lang'     => 'Sprachanforderungen bearbeiten',
        'edit_note'     => 'Hinweis bearbeiten',
        'no_items'      => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'English-Taught Study Programs',
        'who_title'     => 'Who Can Benefit from These Programs',
        'who_subtitle'  => 'Anyone who meets the following requirements:',
        'lang_title'    => 'General Language Requirements',
        'note_hl'       => 'Note:',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_who'      => 'Edit Eligibility Requirements',
        'edit_lang'     => 'Edit Language Requirements',
        'edit_note'     => 'Edit Note',
        'no_items'      => 'No items are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'برامج دراسية باللغة الإنجليزية',
        'who_title'     => 'من يمكنه الاستفادة من هذه البرامج',
        'who_subtitle'  => 'كل من يستوفي الشروط التالية:',
        'lang_title'    => 'متطلبات اللغة بشكل عام',
        'note_hl'       => 'ملاحظة:',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_who'      => 'تعديل شروط الاستفادة',
        'edit_lang'     => 'تعديل متطلبات اللغة',
        'edit_note'     => 'تعديل الملاحظة',
        'no_items'      => 'لا توجد عناصر مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$english_data = is_array($english_data ?? null) ? $english_data : (is_array($englishlang_page ?? null) ? $englishlang_page : ($data['english_data'] ?? []));

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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $english_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $english_data['page_breadcrumb'] ?? $txt['default_bc'];
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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($english_data['hero_img'] ?? null, 'assets/img/education/servicesimg5.png');
    $hero_pos = htmlspecialchars((string)($english_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
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
    
    <!-- 1. العنوان والوصف الرئيسي -->
    <div class="head-info pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishMainModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars((string)($english_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($english_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 2. من يمكنه الاستفادة والشروط -->
    <div class="advice-check my-5 pb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishWhoModal" title="<?php echo htmlspecialchars($txt['edit_who'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($english_data['who_title'] ?? $txt['who_title']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <h6 class="mb-3"><?php echo htmlspecialchars((string)($english_data['who_subtitle'] ?? $txt['who_subtitle']), ENT_QUOTES, 'UTF-8'); ?></h6>
        <div class="row">
          <?php 
          $who_items = $english_data['who_items'] ?? [];
          if (is_array($who_items) && !empty($who_items)):
              foreach ($who_items as $index => $item): 
                  if (empty($item)) continue;
                  $col_class = ($index === 2) ? 'col-lg-4 col-md-4 col-sm-12' : (($index === 3) ? 'col-lg-8 col-md-8 col-sm-12' : 'col-lg-6 col-md-6 col-sm-12');
          ?>
            <div class="<?php echo htmlspecialchars($col_class, ENT_QUOTES, 'UTF-8'); ?> mb-2">
              <p>✅️ <?php echo htmlspecialchars((string)$item, ENT_QUOTES, 'UTF-8'); ?></p>
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

    <!-- 3. متطلبات اللغة -->
    <div class="advice-stars pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishLangModal" title="<?php echo htmlspecialchars($txt['edit_lang'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($english_data['lang_title'] ?? $txt['lang_title']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <ul class="star-list list-unstyled">
          <div class="row">
            <?php 
            $lang_points = $english_data['lang_points'] ?? [];
            if (is_array($lang_points) && !empty($lang_points)):
                foreach ($lang_points as $point): 
                    if (empty($point)) continue;
            ?>
              <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <li>
                  <p class="d-flex align-items-start">
                    <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 flex-shrink-0" width="25"/>
                    <span><?php echo htmlspecialchars((string)$point, ENT_QUOTES, 'UTF-8'); ?></span>
                  </p>
                </li>
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
        </ul>
      </div>
    </div>
    
    <!-- 4. الملاحظة -->
    <div class="note mt-5 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#englishNoteModal" title="<?php echo htmlspecialchars($txt['edit_note'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <p><span style="color: #66aeee;"><?php echo htmlspecialchars((string)($english_data['note_highlight'] ?? $txt['note_hl']), ENT_QUOTES, 'UTF-8'); ?></span> <?php echo htmlspecialchars((string)($english_data['note_text'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
      </div>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
$english_modals_file = __DIR__ . '/includes/admin_english_modals.php';
if ($is_admin_active && file_exists($english_modals_file)) { 
    include_once $english_modals_file; 
}
?>
