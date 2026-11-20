<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Offers & Agreements Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Angebote und Vereinbarungen',
        'note_ttl'      => 'Wichtige Hinweise !!',
        'contact_phrase'=> 'Kontakt aufnehmen',
        'download_lbl'  => 'Herunterladen',
        'pdf_alt'       => 'PDF-Datei',
        'word_alt'      => 'Word-Datei',
        'star_alt'      => 'Stern',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_notes'    => 'Wichtige Hinweise bearbeiten',
        'edit_cards'    => 'Pakete & Vereinbarungen bearbeiten',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Offers and Agreements',
        'note_ttl'      => 'Important Notes !!',
        'contact_phrase'=> 'contact us',
        'download_lbl'  => 'Download',
        'pdf_alt'       => 'PDF File',
        'word_alt'      => 'Word File',
        'star_alt'      => 'Star',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_notes'    => 'Edit Important Notes',
        'edit_cards'    => 'Edit Packages & Agreements',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'العروض والاتفاقيات',
        'note_ttl'      => 'ملاحظات هامة !!',
        'contact_phrase'=> 'بالتواصل معنا',
        'download_lbl'  => 'تحميل',
        'pdf_alt'       => 'ملف PDF',
        'word_alt'      => 'ملف Word',
        'star_alt'      => 'نجمة',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_notes'    => 'تعديل الملاحظات الهامة',
        'edit_cards'    => 'تعديل الحزم والاتفاقيات',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$offers_data = is_array($offers_data ?? null)
    ? $offers_data
    : (is_array($offers_page ?? null) ? $offers_page : ($data['offers_data'] ?? []));

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
$contact_url     = $prefix . '/contact';

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
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $bc_url  = $offers_data['page_breadcrumb_url'] ?? '#';
  $bc_text = $offers_data['page_breadcrumb'] ?? $txt['default_bc'];
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
<!-- Breadcrumb end -->

<!-- ================= CUSTOM HERO SECTION START ================= -->
<section class="custom-services py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <?php
  $hero_img = get_image_url($offers_data['hero_img'] ?? null, 'assets/img/education/servicesimg10.png');
  $hero_pos = htmlspecialchars((string)($offers_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
  ?>
  <div class="custom-container">
    <div class="pakeges-hero custom-hero" 
         style="background-image: url('<?php echo htmlspecialchars($hero_img, ENT_QUOTES, 'UTF-8'); ?>'); background-position: <?php echo $hero_pos; ?>;">
    </div>
  </div>
</section>
<!-- custom-services end -->

<!-- ================= CUSTOM SERVICES INFO SECTION START ================= -->
<section class="custom-services-info py-5" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="custom-container">
    
    <!-- القسم الرئيسي (العنوان والوصف) -->
    <div class="head-info pb-4 mb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>
      <h2 class="main-text"><?php echo htmlspecialchars((string)($offers_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
      <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($offers_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
    </div>

    <!-- ملاحظات هامة -->
    <div class="advice-stars py-4 mb-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_notes'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <h5 class="note-text mb-3"><?php echo htmlspecialchars((string)($offers_data['note_title'] ?? $txt['note_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
      <ul class="star-list list-unstyled p-0">
        <?php 
        $notes_list = is_array($offers_data['notes_list'] ?? null) ? $offers_data['notes_list'] : [];
        if (empty($notes_list) && !empty($offers_data['note_text'])) {
            $notes_list = [(string)$offers_data['note_text']];
        }
        
        if (!empty($notes_list)):
            foreach ($notes_list as $note_item):
                if (trim((string)$note_item) === '') continue;
        ?>
            <li class="d-flex align-items-start mb-2">
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($txt['star_alt'], ENT_QUOTES, 'UTF-8'); ?>" class="ms-2 me-2 mt-1 flex-shrink-0" width="25"/>
              <p class="mb-0"><?php echo $formatNoteWithContact((string)$note_item); ?></p>
            </li>
        <?php 
            endforeach;
        endif; 
        ?>
      </ul>
    </div>

    <!-- كروت التحميل والاتفاقيات -->
    <div class="dl-card py-4 editable-wrapper" style="position: relative;">
      <?php if ($is_admin_active): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#offersCardsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="<?php echo htmlspecialchars($txt['edit_cards'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>

      <div class="row g-4 mt-2">
        <?php 
        $download_cards = is_array($offers_data['download_cards'] ?? null) ? $offers_data['download_cards'] : [];
        if (!empty($download_cards)):
            foreach ($download_cards as $card):
                if (!is_array($card)) continue;
                $is_active   = !empty($card['active']);
                $card_class  = $is_active ? 'card card-active h-100' : 'card h-100';
                $title_class = $is_active ? 'text-bg text-center text-bg-active' : 'text-bg text-center';
                $link_class  = $is_active ? 'text-active' : '';
                $p_class     = $is_active ? 'text-active mb-0' : 'mb-0';
                
                $file_type = strtolower((string)($card['type'] ?? 'pdf'));
                if ($file_type === 'word' || $file_type === 'docx') {
                    $icon_img = 'assets/img/Groupword.webp';
                    $alt_text = $txt['word_alt'];
                } else {
                    $icon_img = 'assets/img/Grouppdf.webp';
                    $alt_text = $txt['pdf_alt'];
                }

                $raw_file = trim((string)($card['file'] ?? '#'));
                $file_url = ($raw_file !== '' && $raw_file !== '#')
                    ? htmlspecialchars('/' . ltrim($raw_file, '/'), ENT_QUOTES, 'UTF-8')
                    : '#';
        ?>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="<?php echo htmlspecialchars($card_class, ENT_QUOTES, 'UTF-8'); ?>">
              <h5 class="<?php echo htmlspecialchars($title_class, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)($card['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h5>
              <div class="card-body d-flex align-items-center gap-3">
                <img src="<?php echo htmlspecialchars(get_image_url($icon_img), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8'); ?>" />
                <div class="card-body-info">
                  <a href="<?php echo $file_url; ?>" class="<?php echo htmlspecialchars($link_class, ENT_QUOTES, 'UTF-8'); ?>" download><?php echo htmlspecialchars($txt['download_lbl'], ENT_QUOTES, 'UTF-8'); ?></a>
                  <?php if (!empty($card['sub'])): ?>
                    <p class="<?php echo htmlspecialchars($p_class, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$card['sub'], ENT_QUOTES, 'UTF-8'); ?></p>
                  <?php endif; ?>
                </div>
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
$offers_modals_file = __DIR__ . '/includes/admin_offers_modals.php';
if ($is_admin_active && file_exists($offers_modals_file)) { 
    include_once $offers_modals_file; 
}
?>
