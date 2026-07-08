<section class="hero py-5" aria-label="قسم البداية">
    <div class="custom-container">
      <div class="hero-container">
        <div class="hero-content">
          
          <h1 class="editable" data-section="hero" data-key="title">
              <?php echo $content['hero_title'] ?? 'ابدأ رحلتك الأكاديمية في ألمانيا مع BCS'; ?>
          </h1>
          
          <p class="editable" data-section="hero" data-key="subtitle">
              <?php echo $content['hero_subtitle'] ?? 'هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة...'; ?>
          </p>
          
          <a href="#contact" class="btn btn-lg hero-btn">احجز استشارتك الآن</a>
          
          <?php if (isset($_SESSION['is_admin'])): ?>
            <div class="mt-3">
               <a href="/logout" class="btn btn-sm btn-outline-danger">تسجيل الخروج</a>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
</section>
