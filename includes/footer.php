  <!-- footer start -->
  <section class="consult-banner-section">
    <div class="container-fluid custom-container">
      <div class="consult-banner">
        <div class="consult-banner-inner">
          <div class="consult-banner-text">
            <h4>احصل على استشارة مجانية</h4>
            <p>هذا النص هو مثال لنص يمكن أن يُستبدل في نفس المساحة</p>
          </div>
          <form class="consult-banner-form" action="send_consult.php" method="POST">
            <input type="email" name="email" placeholder="ادخل إيميلك..." aria-label="Email" required />
            <button type="submit">
              <img src="assets/img/home/send-2.svg" alt="إرسال">
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
          <img src="assets/img/logo.png" alt="BCS Logo" class="mb-4">
          <p class="footer-desc">
            هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربي，
            حيث يمكنك..
          </p>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
          <h5>روابط سريعة</h5>
          <div class="quick-link">
            <a href="about.php">عن الشركة</a>
            <a href="education.php">التعليم العالي</a>
            <a href="job.php">التدريب المهني</a>
            <a href="guide.php">دليل الشركة</a>
            <a href="contact.php">تواصل معنا</a>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
          <h5>تواصل معنا</h5>
          <div class="contact-link">
            <a href="tel:+4917671230666"><img src="img/contact%20us/call-calling.svg" alt="">666-230-71 176 (0) 49+</a>
            <a href="mailto:info@Beethoven-City-Services.com"><img src="img/contact%20us/sms.svg" alt="">info@Beethoven-City-Services.com</a>
            <div class="contact-link-item d-flex align-items-center gap-2">
              <img src="img/contact%20us/Location.svg" alt="">
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
  
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!-- حقن ملفات الـ JS الإضافية ديناميكياً قبل باقي الملفات -->
  <?php if (isset($page_js) && is_array($page_js)): ?>
    <?php foreach ($page_js as $js_file): ?>
      <script src="<?php echo $js_file; ?>"></script>
    <?php endforeach; ?>
  <?php endif; ?>

  <script src="assets/js/all.min.js"></script>
  <script src="assets/js/main.js"></script>

  <!-- حقن أي سكربت مخصص (Inline Script) تم تعريفه في الصفحة -->
  <?php if (isset($custom_script)) { echo $custom_script; } ?>
</body>

</html>
