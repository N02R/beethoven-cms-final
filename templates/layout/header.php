<header>
    <nav class="nav-top navbar py-2">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-none d-lg-flex" href="/">
          <?php echo \App\Core\CMS::get('global', 'header', 'logo_path'); ?>
        </a>
        <div class="flex-grow-1 d-none d-lg-flex more"></div>
        <div class="social-icons d-none d-lg-flex gap-3">
            <a href="<?php echo \App\Core\CMS::get('global', 'header', 'facebook_link'); ?>"><img src="/assets/img/socialicons/Facebook.png"></a>
            </div>
      </div>
    </nav>

    <nav id="main-header" class="navbar navbar-expand-lg py-3">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-lg-none" href="/"><?php echo \App\Core\CMS::get('global', 'header', 'logo_path'); ?></a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav gap-3">
            <li class="nav-item"><a class="nav-link" href="/">الرئيسية</a></li>
            <li class="nav-item"><a class="nav-link" href="/about">عن الشركة</a></li>
          </ul>
        </div>
      </div>
    </nav>
</header>
