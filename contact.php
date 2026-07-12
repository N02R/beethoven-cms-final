<?php 
$path_prefix = ''; 
// تعريف ملفات الـ CSS الخاصة بصفحة اتصل بنا فقط
$page_css = [
    'assets/css/contact.css'
];

// استدعاء الهيدر المشترك
include 'includes/header.php'; 
?>

  <!-- ===== HERO IMAGE ===== -->
  <section class="contact-hero">
    <div class="custom-container">
      <div class="contact-hero-img">
        <img src="assets/img/contact us/contacthero.png" alt="تواصل معنا" class="img-fluid w-100">
      </div>
    </div>
  </section>
  <!-- ===== HERO IMAGE END ===== -->

  <!-- ===== CONTACT INFO BAR ===== -->
  <section class="contact-info-section py-5">
    <div class="custom-container">
      <h2 class="sec-title mb-4">معلومات التواصل</h2>
      <div class="contact-info-bar">
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <img src="assets/img/Location.svg" alt="" />
          </div>
          <img src="assets/img/contact us/Line 16.png" alt="" />
          <a>Rheinweg 140 ,53129 Bonn,Germany</a>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <img src="assets/img/Mail.svg" alt="" />
          </div>
          <img src="assets/img/contact us/Line 16.png" alt="" />
          <a>info@Beethoven-City-Services.com</a>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon">
            <img src="assets/img/Call.svg" alt="" />
          </div>
          <img src="assets/img/contact us/Line 16.png" alt="" />
          <a>666-230-71 176 (0) 49+</a>
        </div>
      </div>
    </div>
  </section>
  <!-- ===== CONTACT INFO BAR END ===== -->

  <!-- ===== WHATSAPP SECTION ===== -->
  <section class="whatsapp-section py-5">
    <div class="custom-container">
      <div class="whatsapp-box text-center">
        <h2 class="whatsapp-title">
          نحن في Beethoven City نؤمن أن التواصل المباشر هو الأفضل.. لذلك نوفر لك قنوات تواصل واضحة وآمنة بدون أي نماذج أو جمع بيانات
        </h2>
        <a href="https://wa.me/4917671230666" target="_blank" rel="noopener" class="btn whatsapp-btn">
          تواصل معنا عبر واتساب
        </a>
      </div>
    </div>
  </section>
  <!-- ===== WHATSAPP SECTION END ===== -->

<?php 
// استدعاء الفوتر المشترك (يحتوي تلقائياً على بنر الاستشارة والـ scripts)
include 'includes/footer.php'; 
?>
