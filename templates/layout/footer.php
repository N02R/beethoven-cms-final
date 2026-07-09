<section class="consult-banner-section">
    <div class="container-fluid custom-container">
      <div class="consult-banner">
        <div class="consult-banner-inner">
          <div class="consult-banner-text">
            <h4><?php echo \App\Core\CMS::get('global', 'banner', 'title'); ?></h4>
            <p><?php echo \App\Core\CMS::get('global', 'banner', 'description'); ?></p>
          </div>
          <form class="consult-banner-form" action="/api/consult" method="POST">
            <input type="email" name="email" placeholder="<?php echo \App\Core\CMS::get('global', 'banner', 'placeholder'); ?>" required />
            <button type="submit"><img src="/assets/img/home/send-2.svg" alt="إرسال"></button>
          </form>
        </div>
      </div>
    </div>
</section>

<footer class="footer-section pt-5">
    <div class="container-fluid custom-container">
      <div class="row gy-5">
        <div class="col-12 col-lg-5">
          <img src="/assets/img/logo.png" alt="BCS Logo" class="mb-4">
          <p class="footer-desc"><?php echo \App\Core\CMS::get('global', 'footer', 'description'); ?></p>
        </div>
        
        <div class="col-12 col-md-6 col-lg-3">
          <h5>روابط سريعة</h5>
          <div class="quick-link">
            <a href="/about">عن الشركة</a>
            <a href="/education">التعليم العالي</a>
            <a href="/job">التدريب المهني</a>
            <a href="/guide">دليل الشركة</a>
            <a href="/contact">تواصل معنا</a>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <h5>تواصل معنا</h5>
          <div class="contact-link">
            <a href="tel:<?php echo \App\Core\CMS::get('global', 'footer', 'phone_raw'); ?>">
                <img src="/assets/img/contact%20us/call-calling.svg" alt="Phone"> <?php echo \App\Core\CMS::get('global', 'footer', 'phone_display'); ?>
            </a>
            <a href="mailto:<?php echo \App\Core\CMS::get('global', 'footer', 'email'); ?>">
                <img src="/assets/img/contact%20us/sms.svg" alt="Email"> <?php echo \App\Core\CMS::get('global', 'footer', 'email'); ?>
            </a>
            <div class="contact-link-item d-flex align-items-center gap-2">
              <img src="/assets/img/contact%20us/Location.svg" alt="Location">
              <span style="font-size: 12px; color: #4F4F4F;"><?php echo \App\Core\CMS::get('global', 'footer', 'address'); ?></span>
            </div>
          </div>
        </div>
      </div>
      
      <hr class="my-4">
      <div class="foot-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 pb-4">
        <p class="mb-0"><?php echo \App\Core\CMS::get('global', 'footer', 'copyrights'); ?></p>
        <a href="/privacy">سياسة الخصوصية وشروط الإستخدام</a>
      </div>
    </div>
</footer>

<!-- Universal Modal: المودال الموحد لكل شيء -->
<div id="universalModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h4 id="modalTitle">تعديل المحتوى</h4>
            <button type="button" class="close-btn" onclick="closeModal()">×</button>
        </div>
        <form id="universalForm">
            <!-- هنا سيتم حقن الحقول ديناميكياً بواسطة JavaScript -->
            <div id="dynamicFields"></div>
            
            <div class="modal-footer" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('universalModal').style.display = 'none';
    }
</script>