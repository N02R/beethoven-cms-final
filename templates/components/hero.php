<section class="hero py-5" aria-label="قسم البداية">
    <div class="custom-container">
      <div class="hero-container">
        <div class="hero-content">
          <h1><?php echo \App\Core\CMS::get('home', 'hero', 'title'); ?></h1>
          <p><?php echo \App\Core\CMS::get('home', 'hero', 'description'); ?></p>
          <a href="<?php echo \App\Core\CMS::get('home', 'hero', 'button_link'); ?>" class="btn btn-lg hero-btn">
            <?php echo \App\Core\CMS::get('home', 'hero', 'button_text'); ?>
          </a>
        </div>
      </div>
    </div>
</section>
