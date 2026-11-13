<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Contact Page View (Gold Standard MVC, Dynamic Items Loop & Security Hardened)
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
        'hero_alt'      => 'Kontaktieren Sie uns',
        'info_title'    => 'Kontaktinformationen',
        'no_items'      => 'Derzeit sind keine Kontaktinformationen hinterlegt.',
        'whatsapp_btn'  => 'Über WhatsApp kontaktieren',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_info'     => 'Kontaktinformationen bearbeiten',
        'edit_whatsapp' => 'WhatsApp-Bereich bearbeiten',
    ],
    'en' => [
        'hero_alt'      => 'Contact Us',
        'info_title'    => 'Contact Information',
        'no_items'      => 'No contact information items are currently available.',
        'whatsapp_btn'  => 'Contact via WhatsApp',
        'edit_hero'     => 'Edit Hero Image',
        'edit_info'     => 'Edit Contact Information',
        'edit_whatsapp' => 'Edit WhatsApp Section',
    ],
    'ar' => [
        'hero_alt'      => 'تواصل معنا',
        'info_title'    => 'معلومات التواصل',
        'no_items'      => 'لا توجد بيانات تواصل مضافة حالياً.',
        'whatsapp_btn'  => 'تواصل عبر الواتساب',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_info'     => 'تعديل معلومات التواصل',
        'edit_whatsapp' => 'تعديل قسم الواتساب',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$currentSettings = is_array($currentSettings ?? null) ? $currentSettings : (is_array($settings ?? null) ? $settings : []);

$getLocalizedData = static function (string $settingKey, mixed $default = []) use ($current_lang, $data, $currentSettings): mixed {
    $raw = $data[$settingKey] ?? ($currentSettings[$settingKey] ?? get_setting($settingKey, $default));

    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $parsed = is_array($decoded) ? $decoded : $default;
    } else {
        $parsed = is_array($raw) ? $raw : $default;
    }

    if (!is_array($parsed)) {
        return $default;
    }

    if (isset($parsed[$current_lang]) && is_array($parsed[$current_lang])) {
        return $parsed[$current_lang];
    }
    if (isset($parsed['de']) && is_array($parsed['de'])) {
        return $parsed['de'];
    }
    if (isset($parsed['en']) && is_array($parsed['en'])) {
        return $parsed['en'];
    }
    if (isset($parsed['ar']) && is_array($parsed['ar'])) {
        return $parsed['ar'];
    }

    return $parsed;
};

$sanitizeUrl = static function (?string $rawUrl): string {
    $url = trim((string)($rawUrl ?? ''));
    if ($url === '' || $url === '#') {
        return '';
    }
    if (preg_match('/^(javascript|data|vbscript|file):/i', $url)) {
        return '';
    }
    return $url;
};

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= 1. CONTACT HERO IMAGE START ================= -->
<section class="contact-hero py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactHeroModal" title="<?php echo htmlspecialchars($txt['edit_hero'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="contact-hero-img text-center">
      <?php 
      $contact_hero = $getLocalizedData('contact_hero', []);
      $contact_hero_bg = get_image_url($contact_hero['img'] ?? null, '/assets/img/contacthero.png');
      ?>
      <img src="<?php echo htmlspecialchars($contact_hero_bg, ENT_QUOTES, 'UTF-8'); ?>" 
           alt="<?php echo htmlspecialchars($txt['hero_alt'], ENT_QUOTES, 'UTF-8'); ?>" 
           class="img-fluid rounded-4" 
           style="max-height: 450px; width: 100%; object-fit: cover;">
    </div>
  </div>
</section>
<!-- ================= CONTACT HERO IMAGE END ================= -->

<!-- ================= 2. CONTACT INFO BAR (DYNAMIC LOOP) START ================= -->
<section class="contact-info-section py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactInfoModal" title="<?php echo htmlspecialchars($txt['edit_info'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $contact_info_raw = $getLocalizedData('contact_info', []);
    $info_title    = (string)($contact_info_raw['title'] ?? $txt['info_title']);
    $info_desc     = (string)($contact_info_raw['desc'] ?? '');
    $contact_items = class_exists(\App\Models\ContactModel::class)
        ? \App\Models\ContactModel::normalizeContactItems($contact_info_raw)
        : (is_array($contact_info_raw['items'] ?? null) ? $contact_info_raw['items'] : []);
    $divider_img = get_image_url('assets/img/Line.png');
    ?>

    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars($info_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if ($info_desc !== ''): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($info_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>

    <?php if (!empty($contact_items)): ?>
      <div class="contact-info-bar" style="color: inherit;">
        <?php foreach ($contact_items as $item): 
            if (!is_array($item)) continue;
            $item_text = (string)($item['text'] ?? '');
            $item_url  = $sanitizeUrl($item['url'] ?? '');
            $item_icon = get_image_url($item['icon'] ?? null, '/assets/img/Location.svg');
            $is_ltr    = !empty($item['is_ltr']);
            $is_ext    = ($item_url !== '' && (str_starts_with($item_url, 'http://') || str_starts_with($item_url, 'https://')));
        ?>
          <div class="contact-info-item">
            <div class="contact-info-icon">
              <img src="<?php echo htmlspecialchars($item_icon, ENT_QUOTES, 'UTF-8'); ?>" alt="Contact Icon" />
            </div>
            <img src="<?php echo htmlspecialchars($divider_img, ENT_QUOTES, 'UTF-8'); ?>" alt="" />
            <?php if ($item_url !== ''): ?>
              <a href="<?php echo htmlspecialchars($item_url, ENT_QUOTES, 'UTF-8'); ?>"
                 <?php echo $is_ltr ? 'dir="ltr"' : ''; ?>
                 <?php echo $is_ext ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                 style="color: inherit; text-decoration: none !important;">
                <?php echo htmlspecialchars($item_text, ENT_QUOTES, 'UTF-8'); ?>
              </a>
            <?php else: ?>
              <span <?php echo $is_ltr ? 'dir="ltr"' : ''; ?> style="color: inherit; text-decoration: none !important;">
                <?php echo htmlspecialchars($item_text, ENT_QUOTES, 'UTF-8'); ?>
              </span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center text-muted py-4">
        <p><?php echo htmlspecialchars($txt['no_items'], ENT_QUOTES, 'UTF-8'); ?></p>
      </div>
    <?php endif; ?>
  </div>
</section>
<!-- ================= CONTACT INFO BAR END ================= -->

<!-- ================= 3. WHATSAPP SECTION START ================= -->
<section class="whatsapp-section py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#whatsappSectionModal" title="<?php echo htmlspecialchars($txt['edit_whatsapp'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $whatsapp_data    = $getLocalizedData('whatsapp_section', []);
    $whatsapp_text    = (string)($whatsapp_data['text'] ?? '');
    $whatsapp_url     = $sanitizeUrl($whatsapp_data['url'] ?? '#') ?: '#';
    $whatsapp_btn_txt = (string)($whatsapp_data['btn_text'] ?? $txt['whatsapp_btn']);
    ?>
    <div class="whatsapp-box text-center">
      <h2 class="whatsapp-title mb-4">
        <?php echo nl2br(htmlspecialchars($whatsapp_text, ENT_QUOTES, 'UTF-8')); ?>
      </h2>
      <a href="<?php echo htmlspecialchars($whatsapp_url, ENT_QUOTES, 'UTF-8'); ?>" 
         target="_blank" 
         rel="noopener noreferrer" 
         class="btn whatsapp-btn text-decoration-none" 
         style="text-decoration: none !important;">
        <?php echo htmlspecialchars($whatsapp_btn_txt, ENT_QUOTES, 'UTF-8'); ?>
      </a>
    </div>
  </div>
</section>
<!-- ================= WHATSAPP SECTION END ================= -->

<?php 
$contact_modals_file = __DIR__ . '/admin/admin_contact_modals.php';
if ($is_admin_active && is_file($contact_modals_file)) { 
    include_once $contact_modals_file; 
}
?>
