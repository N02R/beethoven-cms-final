  <!-- ===== HERO IMAGE ===== -->
  <section class="contact-hero py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="contact-hero-img">
        <img src="<?php echo htmlspecialchars($path_prefix . ltrim($contact_hero_img, '/') . '?v=' . time()); ?>" alt="تواصل معنا" class="img-fluid w-100">
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
      <h2 class="sec-title mb-4">معلومات التواصل</h2>
      <div class="contact-info-bar">
        <!-- 1. العنوان -->
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <img src="<?php echo htmlspecialchars($path_prefix . ltrim($contact_address_icon ?? 'assets/img/Location.svg', '/') . '?v=' . time()); ?>" alt="Location" />
          </div>
          <img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/contact us/Line 16.png'); ?>" alt="" />
          <a><?php echo htmlspecialchars($contact_address); ?></a>
        </div>
        
        <!-- 2. البريد الإلكتروني -->
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <img src="<?php echo htmlspecialchars($path_prefix . ltrim($contact_email_icon ?? 'assets/img/Mail.svg', '/') . '?v=' . time()); ?>" alt="Mail" />
          </div>
          <img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/contact us/Line 16.png'); ?>" alt="" />
          <a><?php echo htmlspecialchars($contact_email); ?></a>
        </div>
        
        <!-- 3. الهاتف -->
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <img src="<?php echo htmlspecialchars($path_prefix . ltrim($contact_phone_icon ?? 'assets/img/Call.svg', '/') . '?v=' . time()); ?>" alt="Call" />
          </div>
          <img src="<?php echo htmlspecialchars($path_prefix . 'assets/img/contact us/Line 16.png'); ?>" alt="" />
          <a><?php echo htmlspecialchars($contact_phone); ?></a>
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
          <?php echo nl2br(htmlspecialchars($whatsapp_text)); ?>
        </h2>
        <a href="<?php echo htmlspecialchars($whatsapp_url); ?>" target="_blank" rel="noopener" class="btn whatsapp-btn">
          <?php echo htmlspecialchars($whatsapp_btn_txt); ?>
        </a>
      </div>
    </div>
  </section>
  <!-- ===== WHATSAPP SECTION END ===== -->

<?php 
if (!empty($is_admin) && file_exists(__DIR__ . '/admin/admin_contact_modals.php')) { 
    include_once __DIR__ . '/admin/admin_contact_modals.php'; 
}
?>