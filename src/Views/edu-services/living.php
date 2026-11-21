<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Cost of Living in Germany Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'default_bc'    => 'Lebenshaltungskosten in Deutschland',
        'tips_ttl'      => 'Tipps zur Reduzierung der Ausgaben',
        'notes_ttl'     => 'Wichtige Hinweise !!',
        'edit_bc'       => 'Breadcrumb bearbeiten',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_main'     => 'Haupttitel & Beschreibung bearbeiten',
        'edit_tips'     => 'Spartipps bearbeiten',
        'edit_notes'    => 'Wichtige Hinweise bearbeiten',
        'no_items'      => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'          => 'Home',
        'higher_edu'    => 'Higher Education',
        'voc_training'  => 'Vocational Training',
        'default_bc'    => 'Cost of Living in Germany',
        'tips_ttl'      => 'Tips to Reduce Expenses',
        'notes_ttl'     => 'Important Notes !!',
        'edit_bc'       => 'Edit Breadcrumb',
        'edit_hero'     => 'Edit Hero Image',
        'edit_main'     => 'Edit Main Title & Description',
        'edit_tips'     => 'Edit Expense-Saving Tips',
        'edit_notes'    => 'Edit Important Notes',
        'no_items'      => 'No items are currently available.',
    ],
    'ar' => [
        'home'          => 'الرئيسية',
        'higher_edu'    => 'التعليم العالي',
        'voc_training'  => 'التدريب المهني',
        'default_bc'    => 'تكلفة المعيشة في ألمانيا',
        'tips_ttl'      => 'نصائح لتقليل النفقات',
        'notes_ttl'     => 'ملاحظات هامة !!',
        'edit_bc'       => 'تعديل مسار التنقل',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_main'     => 'تعديل العنوان والوصف الرئيسي',
        'edit_tips'     => 'تعديل نصائح تقليل النفقات',
        'edit_notes'    => 'تعديل الملاحظات الهامة',
        'no_items'      => 'لا توجد عناصر مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$living_data = is_array($living_data ?? null) ? $living_data : (is_array($living_page ?? null) ? $living_page : ($data['living_data'] ?? []));

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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $living_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $living_data['page_breadcrumb'] ?? $txt['default_bc'];
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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($living_data['hero_img'] ?? null, 'assets/img/education/servicesimg8.png');
    $hero_pos = htmlspecialchars((string)($living_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="custom-container">
      <div class="living-hero custom-hero" 
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
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingMainModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars((string)($living_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($living_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 1. نصائح لتقليل النفقات -->
    <div class="advice-check py-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingTipsModal" title="<?php echo htmlspecialchars($txt['edit_tips'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
        $tips_sec   = is_array($living_data['tips_section'] ?? null) ? $living_data['tips_section'] : [];
        $tips_title = (string)($tips_sec['title'] ?? $txt['tips_ttl']);
        $tips_items = is_array($tips_sec['items'] ?? null) ? $tips_sec['items'] : [];
        ?>
        <h5 class="advice-text mb-4"><?php echo htmlspecialchars($tips_title, ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="row">
          <?php if (!empty($tips_items)): ?>
            <?php foreach ($tips_items as $tip): 
                if (empty($tip)) continue;
            ?>
              <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                <p class="mb-0">✅ <?php echo htmlspecialchars((string)$tip, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12 text-muted">
              <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 2. ملاحظات هامة -->
    <div class="advice-stars my-5 py-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#livingNotesModal" title="<?php echo htmlspecialchars($txt['edit_notes'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <?php 
        $notes_sec   = is_array($living_data['notes_section'] ?? null) ? $living_data['notes_section'] : [];
        $notes_title = (string)($notes_sec['title'] ?? $txt['notes_ttl']);
        $notes_items = is_array($notes_sec['items'] ?? null) ? $notes_sec['items'] : [];
        ?>
        <h5 class="mb-4 note-text"><?php echo htmlspecialchars($notes_title, ENT_QUOTES, 'UTF-8'); ?></h5>
        <ul class="star-list list-unstyled p-0">
          <?php if (!empty($notes_items)): ?>
            <?php foreach ($notes_items as $note): 
                if (empty($note)) continue;
            ?>
              <li class="d-flex align-items-start mb-3">
                <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 mt-1 flex-shrink-0" width="25"/>
                <p class="mb-0"><?php echo htmlspecialchars((string)$note, ENT_QUOTES, 'UTF-8'); ?></p>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="text-muted">
              <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<?php
$living_modals_file = __DIR__ . '/includes/admin_living_modals.php';
if ($is_admin_active && file_exists($living_modals_file)) { 
    include_once $living_modals_file; 
}
?>
