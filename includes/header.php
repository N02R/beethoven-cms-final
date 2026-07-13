<?php
// تأكيد تهيئة المتغير إذا لم يكن معرفاً في الصفحات الرئيسية
if (!isset($path_prefix)) {
    $path_prefix = '';
}
?>
  <!-- قسم الاشتراك في النشرة البريدية للمتجر -->
  <section class="consult-banner-section">
    <div class="container-fluid custom-container">
      <div class="consult-banner">
        <div class="consult-banner-inner">
          <div class="consult-banner-text">
            <h4>اشترك معنا للحصول على آخر العروض</h4>
            <p>سجل بريدك الإلكتروني لتصلك خصومات المتجر والمنتجات الجديدة أولاً بأول.</p>
          </div>
          <form class="consult-banner-form" action="<?php echo $path_prefix; ?>subscribe.php" method="POST">
            <input type="email" name="email" placeholder="أدخل بريدك الإلكتروني..." aria-label="Email" required />
            <button type="submit">
              <img src="<?php echo $path_prefix; ?>assets/img/home/send-2.svg" alt="إرسال">
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
  
  <!-- الفوتر الرئيسي للمتجر -->
  <footer class="footer-section pt-5">
    <div class="container-fluid custom-container">
      <div class="row gy-5">
        <!-- عن المتجر -->
        <div class="col-12 col-lg-5">
          <img src="<?php echo $path_prefix; ?>assets/img/logo.png" alt="BCS Logo" class="mb-4" style="max-height: 60px;">
          <p class="footer-desc">
            مرحباً بك في متجرنا الإلكتروني المتكامل. نوفر لك تجربة تسوق فريدة وسريعة وآمنة مع إدارة كاملة للطلبات والمنتجات عبر نظام BCS-CMS الذكي.
          </p>
        </div>
        
        <!-- روابط المتجر السريعة -->
        <div class="col-12 col-md-6 col-lg-3">
          <h5>روابط سريعة</h5>
          <div class="quick-link">
            <a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a>
            <a href="<?php echo $path_prefix; ?>products.php">تصفح المنتجات</a>
            <a href="<?php echo $path_prefix; ?>cart.php">سلة المشتريات 🛒</a>
            <a href="<?php echo $path_prefix; ?>about_store.php">حول المتجر</a>
            <a href="<?php echo $path_prefix; ?>contact.php">الدعم الفني</a>
          </div>
        </div>
        
        <!-- معلومات التواصل -->
        <div class="col-12 col-md-6 col-lg-4">
          <h5>تواصل معنا</h5>
          <div class="contact-link">
            <a href="tel:+4917671230666">
              <img src="<?php echo $path_prefix; ?>assets/img/contact us/call-calling.svg" alt=""> 666-230-71 176 (0) 49+
            </a>
            <a href="mailto:support@bcs-cms.com">
              <img src="<?php echo $path_prefix; ?>assets/img/contact us/sms.svg" alt=""> support@bcs-cms.com
            </a>
            <div class="contact-link-item d-flex align-items-center gap-2">
              <img src="<?php echo $path_prefix; ?>assets/img/contact us/Location.svg" alt="">
              <span style="font-size: 12px; color: #4F4F4F;">Rheinweg 140, 53129 Bonn, Germany</span>
            </div>
          </div>
        </div>
      </div>
      
      <hr class="my-4">
      
      <!-- الحقوق والسياسات -->
      <div class="foot-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 pb-4">
        <p class="mb-0 text-center text-md-start">
          <span>&copy;</span> جميع الحقوق محفوظة | **BCS-CMS Store**
        </p>
        <div class="d-flex gap-3">
          <a href="<?php echo $path_prefix; ?>privacy.php">سياسة الخصوصية</a>
          <a href="<?php echo $path_prefix; ?>terms.php">شروط الاستخدام</a>
        </div>
      </div>
    </div>
  </footer>
  <!-- footer end -->
  
  <?php 
  // 1. تجميع ملفات الـ JS الإضافية المحقونة من الصفحات ديناميكياً
  $extra_js_string = "";
  if (isset($page_js) && is_array($page_js)) {
      // جلب اسم الملف المباشر فقط (بدون المسار الزائد) ليتعرف عليه سكربت الضغط
      $cleaned_js_files = array_map('basename', $page_js);
      $extra_js_string = "?files=" . implode(',', $cleaned_js_files);
  }
  ?>

  <!-- سطر واحد مدمج ومضغوط يجمع الملفات الأساسية (Bootstrap, main) والإضافية تلقائياً -->
  <script src="<?php echo $path_prefix; ?>assets/js/minify-js.php<?php echo $extra_js_string; ?>"></script>

  <!-- أي سكربت مخصص مكتوب كـ Text داخل الصفحة يتم طباعته هنا بأمان بعد دمج الملفات -->
  <?php if (isset($custom_script)) { echo $custom_script; } ?>
</body>
</html>
