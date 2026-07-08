<!-- templates/components/services.php -->
<section class="services py-5">
  <div class="custom-container">
    <h2 class="mb-5 sec-title"><?php echo \App\Core\CMS::get('home', 'services', 'title'); ?></h2>
    <div class="row g-4">
      <!-- الخدمة الأولى -->
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card" style="background: url('<?php echo \App\Core\CMS::get('home', 'service1', 'img'); ?>') no-repeat center/cover;">
          <a href="<?php echo \App\Core\CMS::get('home', 'service1', 'link'); ?>">
            <div class="card-info">
              <h3><?php echo \App\Core\CMS::get('home', 'service1', 'title'); ?></h3>
              <img src="/assets/img/home/Arrow.svg" alt="Arrow">
            </div>
          </a>
        </div>
      </div>
      <!-- الخدمة الثانية -->
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card" style="background: url('<?php echo \App\Core\CMS::get('home', 'service2', 'img'); ?>') no-repeat center/cover;">
          <a href="<?php echo \App\Core\CMS::get('home', 'service2', 'link'); ?>">
            <div class="card-info">
              <h3><?php echo \App\Core\CMS::get('home', 'service2', 'title'); ?></h3>
              <img src="/assets/img/home/Arrow.svg" alt="Arrow">
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
