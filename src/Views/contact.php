<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Contact Page View (Inline Editing Enabled)
 * النسخة المحدثة والنظيفة المطابقة للمعايير الذهبية والدستور الهندسي الموحد.
 */

$current_lang = $current_lang ?? get_current_lang();
$current_dir = $current_dir ?? (($current_lang === 'ar') ? 'rtl' : 'ltr');

// دالة مساعدة داخلية موحدة لاستخراج بيانات القسم متعدد اللغات بسلامة تامة وحصانة ضد الـ null (DRY Principle)
$getLocalizedData = function(string $settingKey, mixed $default = []): mixed {
    global $current_lang;
    $raw = get_setting($settingKey, $default);
    
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $data = is_array($decoded) ? $decoded : $default;
    } else {
        $data = is_array($raw) ? $raw : $default;
    }

    if (!is_array($data)) {
        return $default;
    }

    $langKey = $current_lang ?? 'de';
    if (isset($data[$langKey]) && is_array($data[$langKey])) {
        return $data[$langKey];
    }
    if (isset($data['de']) && is_array($data['de'])) {
        return $data['de'];
    }
    if (isset($data['ar']) && is_array($data['ar'])) {
        return $data['ar'];
    }
    if (isset($data['en']) && is_array($data['en'])) {
        return $data['en'];
    }

    return $data;
};
?>

<!-- ===== HERO IMAGE ===== -->
<section class="contact-hero py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactHeroModal" title="تعديل صورة الهيرو">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="contact-hero-img text-center">
      <?php 
      // 🌟 جلب بيانات هيرو صفحة التواصل متعددة اللغات بالمعيار الذهبي
      $contact_hero = $getLocalizedData('contact_hero', []);
      $contact_hero_bg = get_image_url($contact_hero['img'] ?? null, 'assets/img/contacthero.png');
      ?>
      <img src="<?php echo htmlspecialchars($contact_hero_bg, ENT_QUOTES, 'UTF-8'); ?>" alt="تواصل معنا" class="img-fluid rounded-4" style="max-height: 450px; width: 100%; object-fit: cover;">
    </div>
  </div>
</section>
<!-- ===== HERO IMAGE END ===== -->

<!-- ===== CONTACT INFO BAR ===== -->
<section class="contact-info-section py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactInfoModal" title="تعديل معلومات التواصل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 جلب بيانات معلومات التواصل متعددة اللغات بالمعيار الذهبي
    $contact_info = $getLocalizedData('contact_info', []);
    
    $info_title   = $contact_info['title'] ?? 'معلومات التواصل';
    $info_desc    = $contact_info['desc'] ?? '';
    $address      = $contact_info['address'] ?? '';
    $email        = $contact_info['email'] ?? '';
    $phone        = $contact_info['phone'] ?? '';
    
    $address_icon = $contact_info['address_icon'] ?? 'assets/img/Location.svg';
    $email_icon   = $contact_info['email_icon'] ?? 'assets/img/Mail.svg';
    $phone_icon   = $contact_info['phone_icon'] ?? 'assets/img/Call.svg';
    ?>

    <div class="mb-5 <?php echo ($current_dir === 'rtl') ? 'text-end' : 'text-start'; ?>">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars($info_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($info_desc)): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($info_desc, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>

    <!-- شريط معلومات التواصل متكيف مع الاتجاهين -->
    <div class="contact-info-bar" style="color: inherit;">
      <!-- 1. العنوان -->
      <div class="contact-info-item">
        <div class="contact-info-icon">
          <img src="<?php echo get_image_url($address_icon, 'assets/img/Location.svg'); ?>" alt="Location" />
        </div>
        <img src="<?php echo get_image_url('assets/img/contact us/Line 16.png'); ?>" alt="" />
        <span style="color: inherit; text-decoration: none !important;"><?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></span>
      </div>
      
      <!-- 2. البريد الإلكتروني -->
      <div class="contact-info-item">
        <div class="contact-info-icon">
          <img src="<?php echo get_image_url($email_icon, 'assets/img/Mail.svg'); ?>" alt="Mail" />
        </div>
        <img src="<?php echo get_image_url('assets/img/contact us/Line 16.png'); ?>" alt="" />
        <a href="mailto:<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" style="color: inherit; text-decoration: none !important;"><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></a>
      </div>
      
      <!-- 3. الهاتف -->
      <div class="contact-info-item">
        <div class="contact-info-icon">
          <img src="<?php echo get_image_url($phone_icon, 'assets/img/Call.svg'); ?>" alt="Call" />
        </div>
        <img src="<?php echo get_image_url('assets/img/contact us/Line 16.png'); ?>" alt="" />
        <a href="tel:<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" dir="ltr" style="color: inherit; text-decoration: none !important;"><?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?></a>
      </div>
    </div>
  </div>
</section>
<!-- ===== CONTACT INFO BAR END ===== -->

<!-- ===== WHATSAPP SECTION ===== -->
<section class="whatsapp-section py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#whatsappSectionModal" title="تعديل قسم الواتساب">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    // 🌟 جلب بيانات قسم الواتساب متعددة اللغات بالمعيار الذهبي
    $whatsapp_data = $getLocalizedData('whatsapp_section', []);
    
    $whatsapp_text    = $whatsapp_data['text'] ?? '';
    $whatsapp_url     = $whatsapp_data['url'] ?? '#';
    $whatsapp_btn_txt = $whatsapp_data['btn_text'] ?? 'تواصل عبر الواتساب';
    ?>
    <div class="whatsapp-box text-center">
      <h2 class="whatsapp-title mb-4">
        <?php echo nl2br(htmlspecialchars($whatsapp_text, ENT_QUOTES, 'UTF-8')); ?>
      </h2>
      <a href="<?php echo htmlspecialchars($whatsapp_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="btn whatsapp-btn text-decoration-none" style="text-decoration: none !important;">
        <?php echo htmlspecialchars($whatsapp_btn_txt, ENT_QUOTES, 'UTF-8'); ?>
      </a>
    </div>
  </div>
</section>
<!-- ===== WHATSAPP SECTION END ===== -->

<?php 
// تضمين موديلات التعديل الخاصة بصفحة التواصل للمشرف فقط
$contact_modals_file = __DIR__ . '/admin/admin_contact_modals.php';
if (!empty($is_admin) && $is_admin === true && file_exists($contact_modals_file)) { 
    include_once $contact_modals_file; 
}
?>
