<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Contact Page View (Gold Standard MVC, Multilingual & Security Hardened)
 * متطابق بالكامل مع المعيار الهندسي المعتمد في صفحات النظام.
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

// نصوص احتياطية مترجمة للغات الثلاث في حال خلو الحقول أو الأقسام
$i18nDefaults = [
    'de' => [
        'hero_alt'      => 'Kontaktieren Sie uns',
        'info_title'    => 'Kontaktinformationen',
        'whatsapp_btn'  => 'Über WhatsApp kontaktieren',
        'edit_hero'     => 'Hero-Bild bearbeiten',
        'edit_info'     => 'Kontaktinformationen bearbeiten',
        'edit_whatsapp' => 'WhatsApp-Bereich bearbeiten',
    ],
    'en' => [
        'hero_alt'      => 'Contact Us',
        'info_title'    => 'Contact Information',
        'whatsapp_btn'  => 'Contact via WhatsApp',
        'edit_hero'     => 'Edit Hero Image',
        'edit_info'     => 'Edit Contact Information',
        'edit_whatsapp' => 'Edit WhatsApp Section',
    ],
    'ar' => [
        'hero_alt'      => 'تواصل معنا',
        'info_title'    => 'معلومات التواصل',
        'whatsapp_btn'  => 'تواصل عبر الواتساب',
        'edit_hero'     => 'تعديل صورة الهيرو',
        'edit_info'     => 'تعديل معلومات التواصل',
        'edit_whatsapp' => 'تعديل قسم الواتساب',
    ],
];
$txt = $i18nDefaults[$current_lang];

// تهيئة متغيرات البيانات والإعدادات بأمان تام
$data = is_array($data ?? null) ? $data : [];
$currentSettings = is_array($currentSettings ?? null) ? $currentSettings : (is_array($settings ?? null) ? $settings : []);

// 1. دالة موحدة لاستخراج بيانات أي قسم متعدد اللغات بأمان تام
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

// 2. دالة تنقية الروابط الخارجية/الداخلية لمنع حقن javascript:
$sanitizeUrl = static function (?string $rawUrl): string {
    $url = trim((string)($rawUrl ?? '#'));
    if ($url === '' || $url === '#') {
        return '#';
    }
    if (preg_match('/^(javascript|data|vbscript|file):/i', $url)) {
        return '#';
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

<!-- ================= 2. CONTACT INFO BAR START ================= -->
<section class="contact-info-section py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactInfoModal" title="<?php echo htmlspecialchars($txt['edit_info'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $contact_info = $getLocalizedData('contact_info', []);
    
    $info_title   = (string)($contact_info['title'] ?? $txt['info_title']);
    $info_desc    = (string)($contact_info['desc'] ?? '');
    $address      = (string)($contact_info['address'] ?? '');
    $email        = (string)($contact_info['email'] ?? '');
    $phone        = (string)($contact_info['phone'] ?? '');
    $clean_phone  = preg_replace('/[^0-9+]/', '', $phone) ?? '';
    
    $address_icon = (string)($contact_info['address_icon'] ?? 'assets/img/Location.svg');
    $email_icon   = (string)($contact_info['email_icon'] ?? 'assets/img/Mail.svg');
    $phone_icon   = (string)($contact_info['phone_icon'] ?? 'assets/img/Call.svg');
    ?>

    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars($info_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if ($info_desc !== ''): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($info_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>

    <!-- شريط معلومات التواصل متكيف مع الاتجاهين -->
    <div class="contact-info-bar" style="color: inherit;">
      <!-- 1. العنوان -->
      <div class="contact-info-item">
        <div class="contact-info-icon">
          <img src="<?php echo htmlspecialchars(get_image_url($address_icon, '/assets/img/Location.svg'), ENT_QUOTES, 'UTF-8'); ?>" alt="Location" />
        </div>
        <img src="<?php echo htmlspecialchars(get_image_url('assets/img/Line.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="" />
        <span style="color: inherit; text-decoration: none !important;"><?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></span>
      </div>
      
      <!-- 2. البريد الإلكتروني -->
      <div class="contact-info-item">
        <div class="contact-info-icon">
          <img src="<?php echo htmlspecialchars(get_image_url($email_icon, '/assets/img/Mail.svg'), ENT_QUOTES, 'UTF-8'); ?>" alt="Mail" />
        </div>
        <img src="<?php echo htmlspecialchars(get_image_url('assets/img/contact us/Line 16.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="" />
        <a href="mailto:<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" style="color: inherit; text-decoration: none !important;">
          <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>
        </a>
      </div>
      
      <!-- 3. الهاتف -->
      <div class="contact-info-item">
        <div class="contact-info-icon">
          <img src="<?php echo htmlspecialchars(get_image_url($phone_icon, '/assets/img/Call.svg'), ENT_QUOTES, 'UTF-8'); ?>" alt="Call" />
        </div>
        <img src="<?php echo htmlspecialchars(get_image_url('assets/img/Line.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="" />
        <a href="tel:<?php echo htmlspecialchars($clean_phone, ENT_QUOTES, 'UTF-8'); ?>" dir="ltr" style="color: inherit; text-decoration: none !important;">
          <?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>
        </a>
      </div>
    </div>
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
    $whatsapp_data = $getLocalizedData('whatsapp_section', []);
    
    $whatsapp_text    = (string)($whatsapp_data['text'] ?? '');
    $whatsapp_url     = $sanitizeUrl($whatsapp_data['url'] ?? '#');
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
// تضمين موديلات التعديل الخاصة بصفحة التواصل للمشرف فقط
$contact_modals_file = __DIR__ . '/admin/admin_contact_modals.php';
if ($is_admin_active && is_file($contact_modals_file)) { 
    include_once $contact_modals_file; 
}
?>
