<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css"> 
  
  <!-- حقن ملفات الـ CSS الإضافية الخاصة بكل صفحة ديناميكياً -->
  <?php if (isset($page_css) && is_array($page_css)): ?>
    <?php foreach ($page_css as $css_file): ?>
      <link rel="stylesheet" href="<?php echo $css_file; ?>">
    <?php endforeach; ?>
  <?php endif; ?>

  <link rel="stylesheet" href="assets/css/all.min.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <link rel="stylesheet" href="assets/css/responsive-index.css">
</head>

<body>
  <!-- header start -->
  <header>
    <!-- nav top -->
    <nav class="nav-top navbar py-2" aria-label="روابط التواصل الاجتماعي">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <!-- Logo (Desktop) -->
        <a class="navbar-brand d-none d-lg-flex" href="index.php">
          <img src="assets/img/logo.png" alt="شعار بيتهوفن سيتي" width="178" height="72" loading="lazy">
        </a>
        <!-- Spacer -->
        <div class="flex-grow-1 d-none d-lg-flex more"></div>
        <!-- Social Icons -->
        <div class="social-icons d-none d-lg-flex gap-3">
          <a href="https://www.facebook.com/BeethovenCityService" target="_blank" rel="noopener">
            <img src="assets/img/socialicons/Facebook.png" alt="فيسبوك">
          </a>
          <a href="https://www.instagram.com/beethoven_city_service" target="_blank" rel="noopener">
            <img src="assets/img/socialicons/Instagram.png" alt="إنستغرام">
          </a>
          <a href="https://wa.me/4917671230666" target="_blank" rel="noopener">
            <img src="assets/img/socialicons/whatsapp.png" alt="واتساب">
          </a>
          <a href="#" target="_blank" rel="noopener">
            <img src="assets/img/socialicons/Twitter.png" alt="تويتر">
          </a>
          <a href="https://youtube.com/@learning_german_language?si=Ulc8NPGJgLdMDyvY" target="_blank" rel="noopener">
            <img src="assets/img/socialicons/youtube.png" alt="يوتيوب">
          </a>
        </div>
      </div>
    </nav>
    <!-- main nav -->
    <nav id="main-header" class="navbar navbar-expand-lg py-3" aria-label="القائمة الرئيسية">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <!-- Logo (Mobile) -->
        <a class="navbar-brand d-lg-none" href="index.php">
          <img src="assets/img/logo.png" alt="شعار بيتهوفن سيتي" loading="lazy">
        </a>
        <a class="navbar-brand logo-scroll d-none" href="index.php">
          <img src="assets/img/logo.png" alt="logo">
        </a>
        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav gap-3">
            <li class="nav-item">
              <a class="nav-link" href="index.php">الرئيسية</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="about.php">عن الشركة</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="education.php">التعليم العالي</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="job.php">التدريب المهني</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="guide.php">دليل بيتهوفن</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">تواصل معنا</a>
            </li>
          </ul>
        </div>
        <!-- Right Side Controls -->
        <div class="d-flex align-items-center gap-3">
          <!-- Mobile Menu Button -->
          <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar" aria-label="فتح القائمة">
            <span class="navbar-toggler-icon"></span>
          </button>
          <!-- Language Switcher -->
          <div class="dropdown">
            <button class="btn lang-switch d-flex align-items-center justify-content-between" type="button"
              data-bs-toggle="dropdown" aria-expanded="false">
                <img src="assets/img/home/global.svg" alt="Language">
                <span>العربية</span>
              <img src="assets/img/home/arowwdown.svg" alt="فتح القائمة">
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2 active" href="about.php">
                  <img src="assets/img/ar.svg" width="20" height="20" alt="">
                  العربية
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="about-en.php">
                  <img src="assets/img/en.svg" width="20" height="20" alt="">
                  English
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <!-- offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header d-flex align-items-center justify-content-between">
        <h5 class="offcanvas-title mb-0" id="offcanvasNavbarLabel">
          <a href="index.php">
            <img src="assets/img/logo.png" alt="شعار بيتهوفن سيتي" height="50">
          </a>
        </h5>
        <button type="button" class="btn-close me-auto" data-bs-dismiss="offcanvas" aria-label="إغلاق القائمة"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">الرئيسية</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="about.php">عن الشركة</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="education.php">التعليم العالي</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="job.php">التدريب المهني</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="guide.php">دليل بيتهوفن</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">تواصل معنا</a>
          </li>
        </ul>
      </div>
    </div>
  </header>
  <!-- header end -->
