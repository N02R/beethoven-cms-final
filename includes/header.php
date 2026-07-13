<?php
// تفعيل الجلسة إذا لم تكن نشطة للتحقق من الصلاحيات لاحقاً
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// تعريف المسار الأساسي للملفات
if (!isset($path_prefix)) { $path_prefix = ''; }

// إعداد مسار اللوجو الافتراضي للموقع لحماية التصميم
$site_logo_path = 'assets/img/logo.png'; 
// استدعاء ملف التحقق من الصلاحيات
require_once __DIR__ . '/check_auth.php'; 

// استبدال القيمة الثابتة بالتحقق الحقيقي
$is_admin = isAdmin(); 


// =======================================================
// منطق الإعلان التفاعلي وقراءة الشعار (يقرأ من ملف الإعدادات)
// =======================================================
$config_file_path = __DIR__ . '/../announcement_config.json';
$announcement = null;
$show_announcement = false;

if (file_exists($config_file_path)) {
    $announcement = json_decode(file_get_contents($config_file_path), true);
    
    if (is_array($announcement)) {
        // 1. استخراج مسار اللوجو المخصص إن وجد في ملف الـ JSON
        if (isset($announcement['site_logo_path']) && !empty($announcement['site_logo_path'])) {
            $site_logo_path = $announcement['site_logo_path'];
        }

        // 2. فحص الحالة والجدولة الزمنية للإعلان التفاعلي
        if (isset($announcement['status']) && $announcement['status'] === 'Published') {
            $show_announcement = true;
            $current_datetime = date('Y-m-d\TH:i');
            
            if (!empty($announcement['start_date']) && $current_datetime < $announcement['start_date']) { $show_announcement = false; }
            if (!empty($announcement['end_date']) && $current_datetime > $announcement['end_date']) { $show_announcement = false; }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BCS || الصفحة الرئيسية</title>
  <?php 
  $extra_css_string = (isset($page_css) && is_array($page_css)) ? "?files=" . implode(',', array_map('basename', $page_css)) : "";
  ?>
  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/minify.php<?php echo $extra_css_string; ?>">
  
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
</head>

<body>

  <!-- شريط الإعلان الديناميكي (أعلى الهيدر) -->
  <?php if ($show_announcement): 
      $ad_link = $announcement['link'] ?? '';
      $has_link = !empty($ad_link);
      $link_target = (isset($announcement['open_new_tab']) && $announcement['open_new_tab'] == 1) ? 'target="_blank"' : 'target="_self"';
  ?>
    <div class="header-announcement-bar text-center p-2 position-relative" 
         style="<?php echo ($announcement['type'] === 'text') ? "background-color:".htmlspecialchars($announcement['bg_color'])."; color:".htmlspecialchars($announcement['text_color'])."; font-size:".(int)$announcement['font_size']."px;" : "background-color:#ffffff;"; ?> z-index: 1040; width: 100%; transition: all 0.3s ease-in-out;">
         
        <?php if ($has_link): ?><a href="<?php echo htmlspecialchars($ad_link); ?>" <?php echo $link_target; ?> class="text-decoration-none text-reset d-block"><?php endif; ?>
            <?php if ($announcement['type'] === 'text'): ?>
                <span class="fw-bold"><?php echo htmlspecialchars($announcement['announcement_text']); ?></span>
            <?php elseif ($announcement['type'] === 'image' && !empty($announcement['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($announcement['image_path']); ?>" alt="<?php echo htmlspecialchars($announcement['alt_text'] ?? 'إعلان'); ?>" class="img-fluid" style="max-height: 60px; object-fit: contain;">
            <?php endif; ?>
        <?php if ($has_link): ?></a><?php endif; ?>
    </div>
  <?php endif; ?>

  <header>
    <nav class="nav-top navbar py-2" aria-label="روابط التواصل الاجتماعي">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <div class="logo-container d-none d-lg-flex">
          <?php if ($is_admin): ?><button class="edit-logo-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal" title="تعديل الشعار">📝</button><?php endif; ?>
          <a class="navbar-brand m-0" href="<?php echo $path_prefix; ?>index.php">
            <img src="<?php echo $path_prefix . $site_logo_path; ?>" alt="شعار بيتهوفن سيتي" width="178" height="72" loading="lazy">
          </a>
        </div>
        <div class="flex-grow-1 d-none d-lg-flex more"></div>
        <div class="social-icons d-none d-lg-flex gap-3">
          <a href="https://www.facebook.com/BeethovenCityService" target="_blank"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/Facebook.png" alt="فيسبوك"></a>
          <a href="https://www.instagram.com/beethoven_city_service" target="_blank"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/Instagram.png" alt="إنستغرام"></a>
          <a href="https://wa.me/4917671230666" target="_blank"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/whatsapp.png" alt="واتساب"></a>
          <a href="#" target="_blank"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/Twitter.png" alt="تويتر"></a>
          <a href="https://youtube.com/@learning_german_language" target="_blank"><img src="<?php echo $path_prefix; ?>assets/img/socialicons/youtube.png" alt="يوتيوب"></a>
        </div>
      </div>
    </nav>

    <nav id="main-header" class="navbar navbar-expand-lg py-3" aria-label="القائمة الرئيسية">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <div class="logo-container d-lg-none">
          <?php if ($is_admin): ?><button class="edit-logo-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal" title="تعديل الشعار">📝</button><?php endif; ?>
          <a class="navbar-brand m-0" href="<?php echo $path_prefix; ?>index.php"><img src="<?php echo $path_prefix . $site_logo_path; ?>" alt="شعار بيتهوفن سيتي" loading="lazy" height="50"></a>
        </div>
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
      </div>
    </nav>
  </header>

  <!-- مودل تعديل اللوجو -->
  <?php if ($is_admin): ?>
  <div class="modal fade" id="logoEditModal" tabindex="-1" aria-labelledby="logoEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoEditModalLabel">تعديل شعار الموقع</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <form id="logoUploadForm" enctype="multipart/form-data">
          <div class="modal-body text-center">
            <img id="currentLogoPreview" src="<?php echo $path_prefix . $site_logo_path; ?>" style="max-height: 100px;" class="mb-3">
            <input class="form-control" type="file" id="logoFileInput" name="logo" accept="image/*" required>
            <div id="uploadStatusSpinner" class="spinner-border text-primary d-none my-2" role="status"></div>
          </div>
          <div class="modal-footer"><button type="submit" class="btn btn-primary" id="saveLogoBtn">حفظ التعديلات</button></div>
        </form>
      </div>
    </div>
  </div>
  <script>
  document.getElementById('logoUploadForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const saveBtn = document.getElementById('saveLogoBtn');
      const spinner = document.getElementById('uploadStatusSpinner');
      
      saveBtn.disabled = true;
      if(spinner) spinner.classList.remove('d-none');

      fetch('<?php echo $path_prefix; ?>admin/upload_logo.php', { 
          method: 'POST', 
          body: new FormData(this) 
      })
      .then(r => r.json())
      .then(data => {
          if(data.success) { 
              location.reload(); 
          } else { 
              alert(data.error); 
              saveBtn.disabled = false;
              if(spinner) spinner.classList.add('d-none');
          }
      })
      .catch(err => {
          console.error(err);
          alert('حدث خطأ أثناء الرفع.');
          saveBtn.disabled = false;
          if(spinner) spinner.classList.add('d-none');
      });
  });
  </script>
  <?php endif; ?>
</body>
</html>
