<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Health Insurance Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Krankenversicherung',
        'advice_ttl'    => 'Warum ist eine Krankenversicherung wichtig?',
        'note_ttl'      => 'Ergänzende Unterlagen',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_tips'     => 'Wichtigkeit der Krankenversicherung bearbeiten',
        'edit_notes'    => 'Ergänzende Unterlagen bearbeiten',
        'edit_links'    => 'Buchungsbeschreibung & Links bearbeiten',
        'no_items'      => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Health Insurance',
        'advice_ttl'    => 'Why Is Health Insurance Important?',
        'note_ttl'      => 'Supporting Documents',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_tips'     => 'Edit Health Insurance Importance',
        'edit_notes'    => 'Edit Supporting Documents',
        'edit_links'    => 'Edit Booking Description & Links',
        'no_items'      => 'No items are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'التأمين الصحي',
        'advice_ttl'    => 'لماذا التأمين الصحي مهم؟',
        'note_ttl'      => 'الوثائق المكملة',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_tips'     => 'تعديل أهمية التأمين الصحي',
        'edit_notes'    => 'تعديل الوثائق المكملة',
        'edit_links'    => 'تعديل وصف وروابط الحجز',
        'no_items'      => 'لا توجد عناصر مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$health_data = is_array($health_data ?? null) ? $health_data : (is_array($health_page ?? null) ? $health_page : ($data['health_data'] ?? []));

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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $health_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $health_data['page_breadcrumb'] ?? $txt['default_bc'];
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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($health_data['hero_img'] ?? null, 'assets/img/education/servicesimg6.png');
    $hero_pos = htmlspecialchars((string)($health_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="custom-container">
      <div class="health-hero custom-hero" 
           style="background-image: url('<?php echo htmlspecialchars($hero_img, ENT_QUOTES, 'UTF-8'); ?>'); background-position: <?php echo $hero_pos; ?>;">
      </div>
    </div>
  </div>
</section>
<!-- custom-services end -->

<!-- ================= CUSTOM SERVICES INFO SECTION START ================= -->
<section class="custom-services-info py-5" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="custom-container">
    
    <!-- 1. قسم العنوان الرئيسي والوصف -->
    <div class="head-info pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthMainTitleModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars((string)($health_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($health_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 2. قسم النصائح والإرشادات (لماذا التأمين الصحي مهم؟) -->
    <div class="advice-check py-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthTipsModal" title="<?php echo htmlspecialchars($txt['edit_tips'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($health_data['advice_title'] ?? $txt['advice_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="row">
          <?php 
          $tips = $health_data['tips'] ?? [];
          if (is_array($tips) && !empty($tips)):
              foreach ($tips as $tip): 
                  if (empty($tip)) continue;
          ?>
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
              <p class="mb-0">✅ <?php echo htmlspecialchars((string)$tip, ENT_QUOTES, 'UTF-8'); ?></p>
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

    <!-- 3. قسم الوثائق المكملة -->
    <div class="advice-stars my-5 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthNotesModal" title="<?php echo htmlspecialchars($txt['edit_notes'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($health_data['note_title'] ?? $txt['note_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <ul class="star-list list-unstyled">
          <div class="row">
            <?php 
            $notes = $health_data['notes'] ?? [];
            if (is_array($notes) && !empty($notes)):
                foreach ($notes as $note): 
                    if (empty($note)) continue;
            ?>
              <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                <li>
                  <p class="mb-0 d-flex align-items-start">
                    <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 flex-shrink-0" width="25"/>
                    <span><?php echo htmlspecialchars((string)$note, ENT_QUOTES, 'UTF-8'); ?></span>
                  </p>
                </li>
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
        </ul>
      </div>
    </div>

    <!-- 4 & 5. قسم الوصف التمهيدي وروابط حجز الشركات -->
    <div class="pt-2 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#healthLinksModal" title="<?php echo htmlspecialchars($txt['edit_links'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <!-- الوصف التمهيدي لروابط الحجز -->
        <div class="mb-4">
          <p class="advice-text mt-4"><?php echo nl2br(htmlspecialchars((string)($health_data['intro_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
        </div>

        <!-- روابط حجز الشركات -->
        <?php 
        $links = $health_data['links'] ?? [];
        if (is_array($links) && !empty($links)):
            foreach ($links as $link_item): 
                if (!is_array($link_item)) continue;
                $is_active_link = !empty($link_item['active']);
                $link_class = $is_active_link ? 'link active mt-4' : 'link mt-4';
                $link_url   = trim((string)($link_item['url'] ?? ''));
                $link_text  = (string)($link_item['text'] ?? '');
        ?>
          <div class="<?php echo $link_class; ?>">
            <p class="text-center mb-0">
              <?php if ($link_url !== '' && $link_url !== '#'): ?>
                <a href="<?php echo htmlspecialchars($link_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="text-decoration-none text-inherit" style="color: inherit;">
                  <?php echo htmlspecialchars($link_text, ENT_QUOTES, 'UTF-8'); ?>
                </a>
              <?php else: ?>
                <?php echo htmlspecialchars($link_text, ENT_QUOTES, 'UTF-8'); ?>
              <?php endif; ?>
            </p>
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
$health_modals_file = __DIR__ . '/includes/admin_health_modals.php';
if ($is_admin_active && file_exists($health_modals_file)) { 
    include_once $health_modals_file; 
}
?>
