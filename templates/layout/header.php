<header>
    <nav class="nav-top navbar py-2">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-none d-lg-flex" href="/"><?php echo \App\Core\CMS::get('global', 'header', 'logo_path', false, true); ?></a>
<div class="flex-grow-1 d-none d-lg-flex more">
    <?php echo \App\Core\CMS::get('global', 'header', 'header_ads', false, true); ?>
</div>


<div class="social-icons d-none d-lg-flex gap-3">
    <!-- غلفنا المجموعة كاملة بالقلم -->
    <span class="editable-element" data-page="global" data-section="social" data-field="all_links">
        <a href="<?php echo \App\Core\CMS::get('global', 'social', 'facebook_link'); ?>"><img src="assets/img/socialicons/Facebook.png" alt="فيسبوك"></a>
        <a href="<?php echo \App\Core\CMS::get('global', 'social', 'instagram_link'); ?>"><img src="assets/img/socialicons/Instagram.png" alt="إنستغرام"></a>
        <a href="<?php echo \App\Core\CMS::get('global', 'social', 'whatsapp_link'); ?>"><img src="assets/img/socialicons/whatsapp.png" alt="واتساب"></a>
        <a href="<?php echo \App\Core\CMS::get('global', 'social', 'twitter_link'); ?>"><img src="assets/img/socialicons/Twitter.png" alt="تويتر"></a>
        <a href="<?php echo \App\Core\CMS::get('global', 'social', 'youtube_link'); ?>"><img src="assets/img/socialicons/youtube.png" alt="يوتيوب"></a>
        
        <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
            <i class="edit-icon">✏️</i>
        <?php endif; ?>
    </span>
</div>


    </nav>

    <nav id="main-header" class="navbar navbar-expand-lg py-3">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-lg-none" href="/"><?php echo \App\Core\CMS::get('global', 'header', 'logo_path', false, true); ?></a>
        
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav gap-3">
            <li class="nav-item"><a class="nav-link" href="/"><?php echo \App\Core\CMS::get('global', 'menu', 'home'); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="/about"><?php echo \App\Core\CMS::get('global', 'menu', 'about'); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="/education"><?php echo \App\Core\CMS::get('global', 'menu', 'education'); ?></a></li>
            <li class="nav-item"><a class="nav-link" href="/job"><?php echo \App\Core\CMS::get('global', 'menu', 'job'); ?></a></li>
          </ul>
        </div>
        
        <div class="d-flex align-items-center gap-3">
          <button class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"><span class="navbar-toggler-icon"></span></button>
          <div class="dropdown">
            <button class="btn lang-switch" data-bs-toggle="dropdown">
                <span><?php echo \App\Core\CMS::get('global', 'header', 'lang_name'); ?></span>
            </button>
          </div>
        </div>
      </div>
    </nav>
    
    <div id="logoModal" class="modal" style="display:none; position:fixed; z-index:9999; top:20%; left:30%; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.3);">
    <h4>تعديل الشعار</h4>
    <p>الصورة الحالية:</p>
    <img id="currentLogo" src="" width="100">
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="logo" accept="image/*">
        <button type="submit">حفظ</button>
        <button type="button" onclick="document.getElementById('logoModal').style.display='none'">إغلاق</button>
    </form>
</div>

</header>
