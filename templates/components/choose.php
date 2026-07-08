<section class="choose py-5">
    <div class="container-fluid custom-container choose-container">
      <h2 class="mb-5 sec-title"><?php echo \App\Core\CMS::get('home', 'choose', 'main_title'); ?></h2>
      <div class="row g-3">
        <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card choose-card <?php echo ($i == 2) ? 'active' : ''; ?>">
            <div class="card-body">
              <a href="#"><img src="assets/img/home/Grouphome<?php echo $i; ?>.svg" alt="icon"></a>
              <h5 class="card-title"><?php echo \App\Core\CMS::get('home', 'choose', 'card' . $i . '_title'); ?></h5>
              <p class="card-text"><?php echo \App\Core\CMS::get('home', 'choose', 'card' . $i . '_text'); ?></p>
            </div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
    </div>
</section>
