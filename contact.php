<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

$path_prefix = ''; 

// 1. تعريف ملفات الـ CSS الخاصة بصفحة اتصل بنا
$page_css = [
    'assets/css/contact.css'
];

$page_js = [];

// 2. استدعاء الهيدر الأساسي
include_once 'includes/header.php'; 

// 3. جلب بيانات التواصل من ملف الـ JSON مع قيم افتراضية متكاملة
$contact_hero_img = $data['contact_hero_img'] ?? 'assets/img/contact us/contacthero.png';

$contact_address  = $data['contact_address'] ?? 'Rheinweg 140 ,53129 Bonn,Germany';
$contact_email    = $data['contact_email'] ?? 'info@Beethoven-City-Services.com';
$contact_phone    = $data['contact_phone'] ?? '666-230-71 176 (0) 49+';

$whatsapp_text    = $data['whatsapp_text'] ?? 'نحن في Beethoven City نؤمن أن التواصل المباشر هو الأفضل.. لذلك نوفر لك قنوات تواصل واضحة وآمنة بدون أي نماذج أو جمع بيانات';
$whatsapp_url     = $data['whatsapp_url'] ?? 'https://wa.me/4917671230666';
$whatsapp_btn_txt = $data['whatsapp_btn_txt'] ?? 'تواصل معنا عبر واتساب';
?>

  <!-- ===== HERO IMAGE ===== -->
  <section class="contact-hero py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <div class="contact-hero-img">
        <img src="<?php echo htmlspecialchars($path_prefix . $contact_hero_img . '?v=' . time()); ?>" alt="تواصل معنا" class="img-fluid w-100">
      </div>
    </div>
  </section>
  <!-- ===== HERO IMAGE END ===== -->

  <!-- ===== CONTACT INFO BAR ===== -->
  <section class="contact-info-section py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#contactInfoModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل معلومات التواصل">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container">
      <h2 class="sec-title mb-4">معلومات التواصل</h2>
      <div class="contact-info-bar">
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <img src="<?php echo $path_prefix; ?>assets/img/Location.svg" alt="" />
          </div>
          <img src="<?php echo $path_prefix; ?>assets/img/contact us/Line 16.png" alt="" />
          <a><?php echo htmlspecialchars($contact_address); ?></a>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <img src="<?php echo $path_prefix; ?>assets/img/Mail.svg" alt="" />
          </div>
          <img src="<?php echo $path_prefix; ?>assets/img/contact us/Line 16.png" alt="" />
          <a><?php echo htmlspecialchars($contact_email); ?></a>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <img src="<?php echo $path_prefix; ?>assets/img/Call.svg" alt="" />
          </div>
          <img src="<?php echo $path_prefix; ?>assets/img/contact us/Line 16.png" alt="" />
          <a><?php echo htmlspecialchars($contact_phone); ?></a>
        </div>
      </div>
    </div>
  </section>
  <!-- ===== CONTACT INFO BAR END ===== -->

  <!-- ===== WHATSAPP SECTION ===== -->
  <section class="whatsapp-section py-5" style="position: relative;">
    <?php if (isset($is_admin) && $is_admin): ?>
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
if (isset($is_admin) && $is_admin && file_exists(__DIR__ . '/includes/admin_contact_modals.php')) { 
    include_once __DIR__ . '/includes/admin_contact_modals.php'; 
}

// 4. استدعاء الفوتر الأساسي
include_once 'includes/footer.php'; 
?>
