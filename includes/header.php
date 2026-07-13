<?php
// إذا لم يكن المتغير معرفاً (أي في الصفحات الرئيسية)، نجعل قيمته فارغة
if (!isset($path_prefix)) {
    $path_prefix = '';
}

// محاكاة الاتصال وقراءة اللوجو من قاعدة البيانات (سنربطه بملف اتصال لاحقاً)
// مؤقتاً سنفترض القيمة الافتراضية إذا لم يتم جلبها من القاعدة
$site_logo_path = 'assets/img/logo.png'; 

// اختبار الصلاحيات: اجعليها true لرؤية التعديل، وستكون مربوطة بالـ Session لاحقاً
$is_admin = true; 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <?php 
  // تجهيز المتغيرات لملفات الـ CSS الإضافية
  $extra_css_string = "";
  if (isset($page_css) && is_array($page_css)) {
      $cleaned_files = array_map('basename', $page_css);
      $extra_css_string = "?files=" . implode(',', $cleaned_files);
  }
  ?>

  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/minify.php<?php echo $extra_css_string; ?>">
  
  <!-- تنسيق بسيط لأيقونة القلم الخاصة باللوجو لتظهر بشكل متناسق فوقه -->
  <?php if ($is_admin): ?>
  <style>
    .logo-container { position: relative; display: inline-block; }
    .edit-logo-btn {
        position: absolute; top: 5px; left: 5px; background: rgba(255,255,255,0.9);
        border: 1px solid #ccc; border-radius: 50%; width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: all 0.2s; z-index: 10;
    }
    .edit-logo-btn:hover { background: #fff; transform: scale(1.1); }
  </style>
  <?php endif; ?>
</head>

<body>
  <header>
    <!-- nav top -->
    <nav class="nav-top navbar py-2" aria-label="روابط التواصل الاجتماعي">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- Logo (Desktop) -->
        <div class="logo-container d-none d-lg-flex">
          <?php if ($is_admin): ?>
            <button class="edit-logo-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal" title="تعديل الشعار">📝</button>
          <?php endif; ?>
          <a class="navbar-brand m-0" href="<?php echo $path_prefix; ?>index.php">
            <img src="<?php echo $path_prefix . $site_logo_path; ?>" alt="شعار بيتهوفن سيتي" width="178" height="72" loading="lazy">
          </a>
        </div>

        <div class="flex-grow-1 d-none d-lg-flex more"></div>
        <!-- Social Icons -->
        <div class="social-icons d-none d-lg-flex gap-3">
          <a href="https://www.facebook.com/BeethovenCityService" target="_blank" rel="noopener"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/Facebook.png" alt="فيسبوك"></a>
          <a href="https://www.instagram.com/beethoven_city_service" target="_blank" rel="noopener"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/Instagram.png" alt="إنستغرام"></a>
          <a href="https://wa.me/4917671230666" target="_blank" rel="noopener"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/whatsapp.png" alt="واتساب"></a>
          <a href="#" target="_blank" rel="noopener"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/Twitter.png" alt="تويتر"></a>
          <a href="https://youtube.com/@learning_german_language?si=Ulc8NPGJgLdMDyvY" target="_blank" rel="noopener"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/youtube.png" alt="يوتيوب"></a>
        </div>
      </div>
    </nav>

    <!-- main nav -->
    <nav id="main-header" class="navbar navbar-expand-lg py-3" aria-label="القائمة الرئيسية">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- Logo (Mobile) -->
        <div class="logo-container d-lg-none">
          <?php if ($is_admin): ?>
            <button class="edit-logo-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal" title="تعديل الشعار">📝</button>
          <?php endif; ?>
          <a class="navbar-brand m-0" href="<?php echo $path_prefix; ?>index.php">
            <img src="<?php echo $path_prefix . $site_logo_path; ?>" alt="شعار بيتهوفن سيتي" loading="lazy" height="50">
          </a>
        </div>

        <!-- Logo (Scroll) -->
        <div class="logo-container logo-scroll d-none">
          <?php if ($is_admin): ?>
            <button class="edit-logo-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal" title="تعديل الشعار">📝</button>
          <?php endif; ?>
          <a class="navbar-brand m-0" href="<?php echo $path_prefix; ?>index.php">
            <img src="<?php echo $path_prefix . $site_logo_path; ?>" alt="logo" height="50">
          </a>
        </div>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav gap-3">
            <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>about.php">عن الشركة</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>job.php">التدريب المهني</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>guide.php">دليل بيتهوفن</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>contact.php">تواصل معنا</a></li>
          </ul>
        </div>

        <!-- Right Side Controls -->
        <div class="d-flex align-items-center gap-3">
          <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-label="فتح القائمة">
            <span class="navbar-toggler-icon"></span>
          </button>
          <!-- Language Switcher -->
          <div class="dropdown">
            <button class="btn lang-switch d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo $path_prefix; ?>assets/img/home/global.svg" alt="Language">
                <span>العربية</span>
              <img src="<?php echo $path_prefix; ?>assets/img/home/arowwdown.svg" alt="فتح القائمة">
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item d-flex align-items-center gap-2 active" href="<?php echo $path_prefix; ?>about.php"><img src="<?php echo $path_prefix; ?>assets/img/ar.svg" width="20" height="20" alt="">العربية</a></li>
              <li><a class="dropdown-item d-flex align-items-center gap-2" href="<?php echo $path_prefix; ?>about-en.php"><img src="<?php echo $path_prefix; ?>assets/img/en.svg" width="20" height="20" alt="">English</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header d-flex align-items-center justify-content-between">
        <h5 class="offcanvas-title mb-0" id="offcanvasNavbarLabel">
          <a href="<?php echo $path_prefix; ?>index.php">
            <img src="<?php echo $path_prefix . $site_logo_path; ?>" alt="شعار بيتهوفن سيتي" height="50">
          </a>
        </h5>
        <button type="button" class="btn-close me-auto" data-bs-dismiss="offcanvas" aria-label="إغلاق القائمة"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>about.php">عن الشركة</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>job.php">التدريب المهني</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>guide.php">دليل بيتهوفن</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix; ?>contact.php">تواصل معنا</a></li>
        </ul>
      </div>
    </div>
  </header>

  <!-- ==================== المودل الخاص بتعديل اللوجو للمدير فقط ==================== -->
  <?php if ($is_admin): ?>
  <div class="modal fade" id="logoEditModal" tabindex="-1" aria-labelledby="logoEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoEditModalLabel">تعديل شعار الموقع الرسمي</h5>
          <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <form id="logoUploadForm" enctype="multipart/form-data">
          <div class="modal-body text-center" dir="rtl">
            
            <!-- عرض الصورة الحالية -->
            <div class="mb-4">
              <label class="form-label d-block text-startfw-bold mb-2">الشعار الحالي للموقع:</label>
              <div class="p-3 border rounded bg-light d-inline-block">
                <img id="currentLogoPreview" src="<?php echo $path_prefix . $site_logo_path; ?>" alt="الشعار الحالي" style="max-height: 100px; object-fit: contain;">
              </div>
            </div>

            <!-- حقل اختيار الصورة الجديدة -->
            <div class="mb-3 text-start">
              <label for="logoFileInput" class="form-label fw-bold">اختر الشعار الجديد (PNG, JPG, JPEG):</label>
              <input class="form-control" type="file" id="logoFileInput" name="new_logo" accept="image/png, image/jpeg, image/jpg" required>
              <div class="form-text text-muted">الحجم الأقصى المسموح به: 2 ميجابايت.</div>
            </div>

            <!-- مؤشر تحميل خفيف -->
            <div id="uploadStatusSpinner" class="spinner-border text-primary d-none my-2" role="status">
              <span class="visually-hidden">جاري الرفع...</span>
            </div>

          </div>
          <div class="modal-footer d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary" id="saveLogoBtn">حفظ التعديلات</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  document.getElementById('logoUploadForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const fileInput = document.getElementById('logoFileInput');
      if (fileInput.files.length === 0) return;

      const saveBtn = document.getElementById('saveLogoBtn');
      const spinner = document.getElementById('uploadStatusSpinner');
      
      // إظهار مؤشر التحميل وتعطيل الزر
      saveBtn.disabled = true;
      spinner.classList.remove('d-none');

      const formData = new FormData(this);

      // إرسال طلب AJAX إلى السكربت الخلفي الآمن
      fetch('<?php echo $path_prefix; ?>admin/upload_logo.php', {
          method: 'POST',
          body: formData
      })
      .then(response => {
          if (!response.ok) {
              throw new Error('استجاب السيرفر برمز خطأ غير متوقع: ' + response.status);
          }
          return response.json();
      })
      .then(data => {
          saveBtn.disabled = false;
          spinner.classList.add('d-none');

          if(data.success) {
              alert('تم تحديث الشعار بنجاح وأرشفة العملية في سجلات السيرفر!');
              location.reload(); // تحديث الصفحة لرؤية اللوجو الجديد
          } else {
              // إبراز الخطأ الصريح القادم من قواعد التحقق بالـ PHP للمدير
              alert('تنبيه: ' + data.error);
          }
      })
      .catch(error => {
          saveBtn.disabled = false;
          spinner.classList.add('d-none');
          // تنبيه منسق لأي انقطاع في الشبكة أو خطأ في صيغة البيانات
          alert('حدث خطأ أثناء الرفع: ' + error.message);
      });
  });
  </script>
  <?php endif; ?>
  <!-- ==================== نهاية المودل ==================== -->
