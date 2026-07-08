<section class="reviews py-5">
  <div class="reviews-bg">
    <div class="custom-container">
      <h2 class="py-5 text-center sec-title">
        <?php echo \App\Core\CMS::get('home', 'reviews', 'main_title'); ?>
      </h2>
    </div>
  </div>

  <div class="custom-container">
    <div class="reviews-carousel-wrapper">
      <div id="carousel-reviews" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" dir="rtl">
        <div class="carousel-inner">
          <?php 
          // جلب الروابط من القاعدة (سنفترض أنها مخزنة كقائمة مفصولة بفواصل أو نستدعيها تباعاً)
          $videos = [
              \App\Core\CMS::get('home', 'reviews', 'video1'),
              \App\Core\CMS::get('home', 'reviews', 'video2'),
              \App\Core\CMS::get('home', 'reviews', 'video3')
          ];
          foreach ($videos as $index => $videoUrl): ?>
            <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
              <div class="video-wrapper">
                <div class="video-container">
                  <iframe src="<?php echo $videoUrl; ?>" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="dots mt-4">
          <?php for ($i = 0; $i < 3; $i++): ?>
            <span class="dot <?php echo ($i == 0) ? 'active' : ''; ?>" data-bs-target="#carousel-reviews" data-bs-slide-to="<?php echo $i; ?>"></span>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>
</section>
