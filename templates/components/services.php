<section class="services py-5">
    <div class="custom-container">
      <h2 class="mb-5 sec-title"><?php echo \App\Core\CMS::get('home', 'services', 'main_title'); ?></h2>
      <div class="row g-4">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <a href="<?php echo \App\Core\CMS::get('home', 'services', 'edu_link'); ?>" class="card-link text-decoration-none">
            <div class="card" style="background: url('assets/img/home/education.jpg') no-repeat center/cover;">
              <div class="card-info">
                <h3><?php echo \App\Core\CMS::get('home', 'services', 'edu_title'); ?></h3>
                <img src="assets/img/home/Arrow.svg" alt="Arrow">
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <a href="<?php echo \App\Core\CMS::get('home', 'services', 'job_link'); ?>" class="card-link text-decoration-none">
            <div class="card" style="background: url('assets/img/home/traning.jpg') no-repeat center/cover;">
              <div class="card-info">
                <h3><?php echo \App\Core\CMS::get('home', 'services', 'job_title'); ?></h3>
                <img src="assets/img/home/Arrow.svg" alt="Arrow">
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
</section>
