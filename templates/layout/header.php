<header>
    <nav class="nav-top navbar py-2" aria-label="روابط التواصل الاجتماعي">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <a class="navbar-brand d-none d-lg-flex editable-element" href="index.php" 
           data-page="global" data-section="header" data-field="logo_path">
          <?php echo \App\Core\CMS::get('global', 'header', 'logo_path', true, false); ?>
          <?php if(isset($_SESSION['is_admin'])): ?><i class="edit-icon">✏️</i><?php endif; ?>
        </a>

        <div class="flex-grow-1 d-none d-lg-flex more editable-element" data-page="global" data-section="header" data-field="header_ads">
            <div class="editable-content"><?php echo \App\Core\CMS::get('global', 'header', 'header_ads', false, true); ?></div>
            <?php if(isset($_SESSION['is_admin'])): ?><i class="edit-icon">✏️</i><?php endif; ?>
        </div>

        <div class="social-icons d-none d-lg-flex gap-3 editable-element" data-page="global" data-section="social" data-field="social_links">
          <?php 
            $links = \App\Core\CMS::get('global', 'social', 'social_links', false, false); 
          ?>
          <a href="<?php echo $links['facebook'] ?? '#'; ?>"><img src="assets/img/socialicons/Facebook.png" alt="فيسبوك"></a>
          <a href="<?php echo $links['instagram'] ?? '#'; ?>"><img src="assets/img/socialicons/Instagram.png" alt="إنستغرام"></a>
          <a href="<?php echo $links['whatsapp'] ?? '#'; ?>"><img src="assets/img/socialicons/whatsapp.png" alt="واتساب"></a>
          <a href="<?php echo $links['twitter'] ?? '#'; ?>"><img src="assets/img/socialicons/Twitter.png" alt="تويتر"></a>
          <a href="<?php echo $links['youtube'] ?? '#'; ?>"><img src="assets/img/socialicons/youtube.png" alt="يوتيوب"></a>
          <?php if(isset($_SESSION['is_admin'])): ?><i class="edit-icon">✏️</i><?php endif; ?>
        </div>
      </div>
    </nav>

    <nav id="main-header" class="navbar navbar-expand-lg py-3" aria-label="القائمة الرئيسية">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-lg-none" href="index.php">
          <?php echo \App\Core\CMS::get('global', 'header', 'logo_path', true, false); ?>
        </a>

        <div class="collapse navbar-collapse">
          <ul class="navbar-nav gap-3 editable-element" data-page="global" data-section="menu" data-field="nav_menu">
            <li class="nav-item"><a class="nav-link" href="index.php"><?php echo \App\Core\CMS::get('global', 'menu', 'home'); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="aboutus.php"><?php echo \App\Core\CMS::get('global', 'menu', 'about'); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="education.php"><?php echo \App\Core\CMS::get('global', 'menu', 'education'); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="job.php"><?php echo \App\Core\CMS::get('global', 'menu', 'job'); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="guide.php"><?php echo \App\Core\CMS::get('global', 'menu', 'guide'); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="contact.php"><?php echo \App\Core\CMS::get('global', 'menu', 'contact'); ?></a></li>
            <?php if(isset($_SESSION['is_admin'])): ?><i class="edit-icon">✏️</i><?php endif; ?>
          </ul>
        </div>

        <div class="d-flex align-items-center gap-3">
          <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"><span class="navbar-toggler-icon"></span></button>
          
          <div class="dropdown">
            <button class="btn lang-switch d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <img src="assets/img/home/global.svg" alt="lang">
                <span><?php echo \App\Core\CMS::get('global', 'header', 'lang_name', false, true); ?></span>
            </button>
          </div>
        </div>
      </div>
    </nav>
</header>
