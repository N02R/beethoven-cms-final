<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Financial Guarantees & Blocked Bank Account Page View (Gold Standard MVC, Multilingual & Security Hardened)
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
        'home'            => 'Startseite',
        'higher_edu'      => 'Höhere Bildung',
        'voc_training'    => 'Berufsausbildung',
        'default_bc'      => 'Finanzierungsnachweis & Sperrkonto',
        'importance_ttl'  => 'Bedeutung des Finanzierungsnachweises',
        'options_ttl'     => 'Möglichkeiten des Finanzierungsnachweises für das Studium in Deutschland',
        'account_ttl'     => 'Das Sperrkonto',
        'edit_bc'         => 'Breadcrumb bearbeiten',
        'edit_hero'       => 'Hero-Bild bearbeiten',
        'edit_main'       => 'Haupttitel, Beschreibung & Bedeutung bearbeiten',
        'edit_options'    => 'Finanzierungsoptionen bearbeiten',
        'edit_account'    => 'Sperrkonto-Punkte bearbeiten',
        'edit_links'      => 'Externe Links & Anbieter bearbeiten',
        'no_items'        => 'Derzeit sind keine Einträge hinterlegt.',
    ],
    'en' => [
        'home'            => 'Home',
        'higher_edu'      => 'Higher Education',
        'voc_training'    => 'Vocational Training',
        'default_bc'      => 'Financial Guarantees & Blocked Bank Account',
        'importance_ttl'  => 'Importance of Proof of Financial Means',
        'options_ttl'     => 'Financial Guarantee Options for Studying in Germany',
        'account_ttl'     => 'Blocked Bank Account',
        'edit_bc'         => 'Edit Breadcrumb',
        'edit_hero'       => 'Edit Hero Image',
        'edit_main'       => 'Edit Main Title, Description & Importance',
        'edit_options'    => 'Edit Financial Guarantee Options',
        'edit_account'    => 'Edit Blocked Account Points',
        'edit_links'      => 'Edit External Links & Providers',
        'no_items'        => 'No items are currently available.',
    ],
    'ar' => [
        'home'            => 'الرئيسية',
        'higher_edu'      => 'التعليم العالي',
        'voc_training'    => 'التدريب المهني',
        'default_bc'      => 'الضمانات المالية والحساب البنكي المغلق',
        'importance_ttl'  => 'أهمية إثبات الضمان المالي',
        'options_ttl'     => 'خيارات الضمان المالي للدراسة في ألمانيا',
        'account_ttl'     => 'الحساب البنكي المغلق',
        'edit_bc'         => 'تعديل مسار التنقل',
        'edit_hero'       => 'تعديل صورة الهيرو',
        'edit_main'       => 'تعديل العنوان والوصف الرئيسي والأهمية',
        'edit_options'    => 'تعديل خيارات الضمان المالي',
        'edit_account'    => 'تعديل نقاط الحساب المغلق',
        'edit_links'      => 'تعديل الروابط والشركات',
        'no_items'        => 'لا توجد عناصر مضافة حالياً.',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$financial_data = is_array($financial_data ?? null) ? $financial_data : (is_array($financial_page ?? null) ? $financial_page : ($data['financial_data'] ?? []));

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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedBreadcrumbModal" title="<?php echo htmlspecialchars($txt['edit_bc'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $bc_url  = $financial_data['page_breadcrumb_url'] ?? '#';
    $bc_text = $financial_data['page_breadcrumb'] ?? $txt['default_bc'];
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
      <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <?php
    $hero_img = get_image_url($financial_data['hero_img'] ?? null, 'assets/img/education/servicesimg7.png');
    $hero_pos = htmlspecialchars((string)($financial_data['hero_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="custom-container">
      <div class="financial-hero custom-hero"
        style="background-image: url('<?php echo htmlspecialchars($hero_img, ENT_QUOTES, 'UTF-8'); ?>'); background-position: <?php echo $hero_pos; ?>;">
      </div>
    </div>
  </div>
</section>
<!-- custom-services end -->

<!-- ================= CUSTOM SERVICES INFO SECTION START ================= -->
<section class="custom-services-info py-5" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="custom-container">
    
    <!-- 1. العنوان الرئيسي والأهمية -->
    <div class="head-info pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedMainModal" title="<?php echo htmlspecialchars($txt['edit_main'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars((string)($financial_data['main_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars((string)($financial_data['main_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
        
        <h5 class="advice-text mt-5"><?php echo htmlspecialchars((string)($financial_data['importance_title'] ?? $txt['importance_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <p><?php echo nl2br(htmlspecialchars((string)($financial_data['importance_desc'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></p>
      </div>
    </div>

    <!-- 2. خيارات الضمان المالي -->
    <div class="advice-check my-5 pb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedOptionsModal" title="<?php echo htmlspecialchars($txt['edit_options'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($financial_data['options_title'] ?? $txt['options_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <div class="d-flex flex-column gap-3">
          <?php 
          $options_items = $financial_data['options_items'] ?? [];
          if (is_array($options_items) && !empty($options_items)):
              foreach ($options_items as $opt): 
                  if (empty($opt)) continue;
          ?>
            <p class="mb-0">✅️ <?php echo htmlspecialchars((string)$opt, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php 
              endforeach;
          else:
          ?>
            <p class="mb-0 text-muted"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- 3. الحساب البنكي المغلق -->
    <div class="advice-stars pb-4 mb-4 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedAccountModal" title="<?php echo htmlspecialchars($txt['edit_account'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text mb-4"><?php echo htmlspecialchars((string)($financial_data['account_title'] ?? $txt['account_ttl']), ENT_QUOTES, 'UTF-8'); ?></h5>
        <ul class="star-list list-unstyled">
          <div class="d-flex flex-column gap-3">
            <?php 
            $account_points = $financial_data['account_points'] ?? [];
            if (is_array($account_points) && !empty($account_points)):
                foreach ($account_points as $point): 
                    if (empty($point)) continue;
            ?>
              <li>
                <p class="mb-0 d-flex align-items-start">
                  <img src="<?php echo htmlspecialchars(get_image_url('assets/img/starList.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="" class="ms-2 me-2 flex-shrink-0" width="25"/>
                  <span><?php echo htmlspecialchars((string)$point, ENT_QUOTES, 'UTF-8'); ?></span>
                </p>
              </li>
            <?php 
                endforeach;
            else:
            ?>
              <li class="text-muted">
                <p class="mb-0"><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
              </li>
            <?php endif; ?>
          </div>
        </ul>
      </div>
    </div>

    <!-- 4. الروابط الخارجية (Dr.WALTER, FINTIBA وغيرها) -->
    <div class="py-5 editable-wrapper">
      <div class="edit-board-box">
        <?php if ($is_admin_active): ?>
          <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#blockedLinksModal" title="<?php echo htmlspecialchars($txt['edit_links'], ENT_QUOTES, 'UTF-8'); ?>">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <div class="custom-container">
          <?php 
          $service_links = $financial_data['service_links'] ?? [];
          if (is_array($service_links) && !empty($service_links)):
              foreach ($service_links as $index => $link): 
                  if (!is_array($link)) continue;
                  $is_active_link = !empty($link['active']);
                  $base_class = $is_active_link ? 'link active' : 'link';
                  $link_class = ($index === 0) ? $base_class : $base_class . ' mt-4';
                  $link_url   = trim((string)($link['url'] ?? ''));
                  $link_text  = (string)($link['text'] ?? '');
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

  </div>
</section>
<!-- custom-services-info end -->

<?php
$financial_modals_file = __DIR__ . '/includes/admin_financial_modals.php';
if ($is_admin_active && file_exists($financial_modals_file)) { 
    include_once $financial_modals_file; 
}
?>
