<!-- templates/components/guide.php -->
<section class="guide py-2">
  <div class="custom-container">
    <h2 class="mb-2 pt-5 sec-title"><?php echo \App\Core\CMS::get('home', 'guide', 'title'); ?></h2>
    <p class="main-p"><?php echo \App\Core\CMS::get('home', 'guide', 'description'); ?></p>

    <div id="carousel-guide" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
      <div class="carousel-inner" dir="rtl">
        <?php for ($slide = 1; $slide <= 3; $slide++): ?>
          <div class="carousel-item <?php echo ($slide == 1) ? 'active' : ''; ?>">
            <div class="row g-4">
              <?php for ($card = 1; $card <= 3; $card++): ?>
                <?php $idx = ($slide - 1) * 3 + $card; ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                      <img src="<?php echo \App\Core\CMS::get('home', 'guide_card' . $idx, 'img'); ?>" class="img-fluid guide-img">
                      <h5 class="card-title fw-bold mt-3"><?php echo \App\Core\CMS::get('home', 'guide_card' . $idx, 'title'); ?></h5>
                      <p class="card-text"><?php echo \App\Core\CMS::get('home', 'guide_card' . $idx, 'description'); ?></p>
                      <a href="<?php echo \App\Core\CMS::get('home', 'guide_card' . $idx, 'link'); ?>" class="btn fw-bold mt-auto">
                        قراءة المزيد <img src="/assets/img/home/Arrow..svg" class="me-2">
                      </a>
                    </div>
                  </div>
                </div>
              <?php endfor; ?>
            </div>
          </div>
        <?php endfor; ?>
      </div>
      <!-- Dots -->
      <div class="dots mt-4">
        <?php for ($i = 0; $i < 3; $i++): ?>
          <span class="dot <?php echo ($i == 0) ? 'active' : ''; ?>" data-bs-target="#carousel-guide" data-bs-slide-to="<?php echo $i; ?>"></span>
        <?php endfor; ?>
      </div>
    </div>
  </div>
</section>
