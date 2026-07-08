<header>
    <nav class="nav-top navbar py-2" aria-label="روابط التواصل الاجتماعي">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-none d-lg-flex" href="/">
          <?php echo \App\Core\CMS::get('global', 'header', 'logo_path'); ?>
        </a>
        
        <div class="flex-grow-1 d-none d-lg-flex more"></div>
        
        <div class="social-icons d-none d-lg-flex gap-3">
          <a href="<?php echo \App\Core\CMS::get('global', 'header', 'facebook_link'); ?>" target="_blank" rel="noopener"><img src="/assets/img/socialicons/Facebook.png" alt="فيسبوك"></a>
          <a href="<?php echo \App\Core\CMS::get('global', 'header', 'instagram_link'); ?>" target="_blank" rel="noopener"><img src="/assets/img/socialicons/Instagram.png" alt="إنستغرام"></a>
          <a href="<?php echo \App\Core\CMS::get('global', 'header', 'whatsapp_link'); ?>" target="_blank" rel="noopener"><img src="/assets/img/socialicons/whatsapp.png" alt="واتساب"></a>
          <a href="<?php echo \App\Core\CMS::get('global', 'header', 'twitter_link'); ?>" target="_blank" rel="noopener"><img src="/assets/img/socialicons/Twitter.png" alt="تويتر"></a>
          <a href="<?php echo \App\Core\CMS::get('global', 'header', 'youtube_link'); ?>" target="_blank" rel="noopener"><img src="/assets/img/socialicons/youtube.png" alt="يوتيوب"></a>
        </div>
      </div>
    </nav>

    <nav id="main-header" class="navbar navbar-expand-lg py-3" aria-label="القائمة الرئيسية">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-lg-none" href="/"><?php echo \App\Core\CMS::get('global', 'header', 'logo_path'); ?></a>
        
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav gap-3">
            <li class="nav-item"><a class="nav-link active" href="/">الرئيسية</a></li>
            <li class="nav-item"><a class="nav-link" href="/about">عن الشركة</a></li>
            </ul>
        </div>
        
        <div class="d-flex align-items-center gap-3">
          <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"><span class="navbar-toggler-icon"></span></button>
          <div class="dropdown">
            <button class="btn lang-switch d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown">
                <img src="/assets/img/home/global.svg" alt="Language">
                <span><?php echo \App\Core\CMS::get('global', 'header', 'lang_name'); ?></span>
                <img src="/assets/img/home/arowwdown.svg" alt="فتح القائمة">
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item active" href="#"><img src="/assets/img/ar.svg" width="20" alt=""> العربية</a></li>
              <li><a class="dropdown-item" href="#"><img src="/assets/img/en.svg" width="20" alt=""> English</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
</header>
