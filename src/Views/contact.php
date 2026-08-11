<?php
/**
 * صفحة تواصل معنا - Contact Page View
 */
?>

<!-- ===== HERO IMAGE ===== -->
<section class="contact-hero py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="contact-hero-img">
      <?php 
      $contact_hero_bg = get_image_url($contact_hero_img ?? null, 'assets/img/contacthero.png');
      ?>
      <img src="<?php echo htmlspecialchars($contact_hero_bg); ?>" alt="تواصل معنا" class="img-fluid w-100" style="border-radius: 20px;">
    </div>
  </div>
</section>
<!-- ===== HERO IMAGE END ===== -->

<!-- ===== CONTACT INFO BAR ===== -->
<section class="contact-info-section py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactInfoModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل معلومات التواصل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="mb-5">
      <h2 class="sec-title"><?php echo htmlspecialchars($contact_info_title ?? 'معلومات التواصل'); ?></h2>
      <?php if (!empty($contact_info_desc)): ?>
        <p class="main-p" style="max-width: 700px;"><?php echo htmlspecialchars($contact_info_desc); ?></p>
      <?php endif; ?>
    </div>

    <div class="contact-info-bar">
      <!-- 1. العنوان -->
      <div class="contact-info-item">
        <div class="contact-info-icon">
          <img src="<?php echo get_image_url($contact_address_icon ?? null, 'assets/img/Location.svg'); ?>" alt="Location" />
        </div>
        <img src="<?php echo get_image_url('assets/img/contact us/Line 16.png'); ?>" alt="" />
        <span class="text-decoration-none" style="text-decoration: none !important;"><?php echo htmlspecialchars($contact_address ?? ''); ?></span>
      </div>
      
      <!-- 2. البريد الإلكتروني -->
      <div class="contact-info-item">
        <div class="contact-info-icon">
          <img src="<?php echo get_image_url($contact_email_icon ?? null, 'assets/img/Mail.svg'); ?>" alt="Mail" />
        </div>
        <img src="<?php echo get_image_url('assets/img/contact us/Line 16.png'); ?>" alt="" />
        <a href="mailto:<?php echo htmlspecialchars($contact_email ?? ''); ?>" class="text-decoration-none" style="text-decoration: none !important;"><?php echo htmlspecialchars($contact_email ?? ''); ?></a>
      </div>
      
      <!-- 3. الهاتف -->
      <div class="contact-info-item">
        <div class="contact-info-icon">
          <img src="<?php echo get_image_url($contact_phone_icon ?? null, 'assets/img/Call.svg'); ?>" alt="Call" />
        </div>
        <img src="<?php echo get_image_url('assets/img/contact us/Line 16.png'); ?>" alt="" />
        <a href="tel:<?php echo htmlspecialchars($contact_phone ?? ''); ?>" class="text-decoration-none" dir="ltr" style="text-decoration: none !important;"><?php echo htmlspecialchars($contact_phone ?? ''); ?></a>
      </div>
    </div>
  </div>
</section>
<!-- ===== CONTACT INFO BAR END ===== -->

<!-- ===== WHATSAPP SECTION ===== -->
<section class="whatsapp-section py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#whatsappSectionModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل قسم الواتساب">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="whatsapp-box text-center">
      <h2 class="whatsapp-title">
        <?php echo nl2br(htmlspecialchars($whatsapp_text ?? '')); ?>
      </h2>
      <a href="<?php echo htmlspecialchars($whatsapp_url ?? '#'); ?>" target="_blank" rel="noopener" class="btn whatsapp-btn text-decoration-none" style="text-decoration: none !important;">
        <?php echo htmlspecialchars($whatsapp_btn_txt ?? 'تواصل عبر الواتساب'); ?>
      </a>
    </div>
  </div>
</section>
<!-- ===== WHATSAPP SECTION END ===== -->

<?php 
$contact_modals_file = __DIR__ . '/admin/admin_contact_modals.php';
if (!empty($is_admin) && file_exists($contact_modals_file)) { 
    include_once $contact_modals_file; 
}
?>
