<?php
// تأكيد تهيئة المتغير إذا لم يكن معرفاً في الصفحات الرئيسية
if (!isset($path_prefix)) {
    $path_prefix = '';
}
?>
<!-- footer start -->
  <section class="consult-banner-section">
    <div class="container-fluid custom-container">
      <div class="consult-banner">
        <div class="consult-banner-inner">
          <div class="consult-banner-text">
            <h4>احصل على استشارة مجانية</h4>
            <p>هذا النص هو مثال لنص يمكن أن يُستبدل في نفس المساحة</p>
          </div>
          <form class="consult-banner-form" action="<?php echo $path_prefix; ?>send_consult.php" method="POST">
            <input type="email" name="email" placeholder="ادخل إيميلك..." aria-label="Email" required />
            <button type="submit">
              <img src="<?php echo $path_prefix; ?>assets/img/home/send-2.svg" alt="إرسال">
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
  
  <footer class="footer-section pt-5">
    <div class="container-fluid custom-container">
      <div class="row gy-5">
        <div class="col-12 col-lg-5">
          <img src="<?php echo $path_prefix; ?>assets/img/logo.png" alt="BCS Logo" class="mb-4">
          <p class="footer-desc">
            هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربي...
          </p>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <h5>روابط سريعة</h5>
          <div class="quick-link">
            <a href="<?php echo $path_prefix; ?>about.php">عن الشركة</a>
            <a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a>
            <a href="<?php echo $path_prefix; ?>job.php">التدريب المهني</a>
            <a href="<?php echo $path_prefix; ?>guide.php">دليل الشركة</a>
            <a href="<?php echo $path_prefix; ?>contact.php">تواصل معنا</a>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
          <h5>تواصل معنا</h5>
          <div class="contact-link">
            <a href="tel:+4917671230666">
              <img src="<?php echo $path_prefix; ?>assets/img/contact us/call-calling.svg" alt="">666-230-71 176 (0) 49+
            </a>
            <a href="mailto:info@Beethoven-City-Services.com">
              <img src="<?php echo $path_prefix; ?>assets/img/contact us/sms.svg" alt="">info@Beethoven-City-Services.com
            </a>
            <div class="contact-link-item d-flex align-items-center gap-2">
              <img src="<?php echo $path_prefix; ?>assets/img/contact us/Location.svg" alt="">
              <span style="font-size: 12px; color: #4F4F4F;">Rheinweg 140 ,53129 Bonn,Germany</span>
            </div>
          </div>
        </div>
      </div>
      <hr class="my-4">
      <div class="foot-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 pb-4">
        <p class="mb-0 text-center text-md-start">
          <span>&copy;</span> جميع الحقوق محفوظة | Beethoven City Services
        </p>
        <a href="#">سياسة الخصوصية وشروط الإستخدام</a>
      </div>
    </div>
  </footer>
  <!-- footer end  -->
  
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
