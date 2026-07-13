<?php
// 1. بدء الجلسة بأمان إذا لم تكن قد بدأت بعد
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. تعريف المسار الأساسي للملفات
if (!isset($path_prefix)) { 
    $path_prefix = ''; 
}

// 3. دالة التحقق من صلاحيات المسؤول (Enterprise Strict Check)
if (!function_exists('isUserAdmin')) {
    function isUserAdmin() {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}

// التحقق الفعلي
$is_admin = isUserAdmin(); 

// 4. إعداد مسار اللوجو الافتراضي للموقع 
$site_logo_path = 'assets/img/logo.png'; 

// =======================================================
// منطق الإعلان التفاعلي وقراءة البيانات من ملف الـ JSON
// =======================================================
$config_file_path = __DIR__ . '/../announcement_config.json';
$announcement = null;
$show_announcement = false;
$menu_links = [];

if (file_exists($config_file_path)) {
    $announcement = json_decode(file_get_contents($config_file_path), true);
    
    if (is_array($announcement)) {
        // استخراج مسار اللوجو المخصص إن وجد في ملف الـ JSON
        if (isset($announcement['site_logo_path']) && !empty($announcement['site_logo_path'])) {
            $site_logo_path = $announcement['site_logo_path'];
        }

        // استخراج الروابط الديناميكية (في حال عدم وجودها سنضع الروابط الحالية كاحتياط لضمان عدم توقف الموقع)
        if (isset($announcement['menu_links']) && is_array($announcement['menu_links'])) {
            $menu_links = $announcement['menu_links'];
            // ترتيب الروابط بناءً على حقل الترتيب الحركي
            usort($menu_links, function($a, $b) { return ($a['order'] ?? 0) <=> ($b['order'] ?? 0); });
        } else {
            // الروابط الافتراضية المأخوذة من كودك الأصلي ليعمل الموقع مباشرة
            $menu_links = [
                ["title" => "الرئيسية", "url" => "index.php", "order" => 1],
                ["title" => "عن الشركة", "url" => "about.php", "order" => 2],
                ["title" => "التعليم العالي", "url" => "education.php", "order" => 3],
                ["title" => "التدريب المهني", "url" => "job.php", "order" => 4],
                ["title" => "دليل بيتهوفن", "url" => "guide.php", "order" => 5],
                ["title" => "تواصل معنا", "url" => "contact.php", "order" => 6]
            ];
        }

        // فحص الحالة والجدولة الزمنية للإعلان التفاعلي
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
  <title><?php echo $page_title ?? 'BCS || الصفحة الرئيسية'; ?></title>
  <?php 
  $extra_css_string = (isset($page_css) && is_array($page_css)) ? "?files=" . implode(',', array_map('basename', $page_css)) : "";
  ?>
  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/minify.php<?php echo $extra_css_string; ?>">
  
  <style>
    .logo-container { position: relative; display: inline-block; }
    <?php if ($is_admin): ?>
    /* تحميل تنسيقات لوحة التحكم وحواجز التعديل للمسؤول فقط */
    .editable-admin-border { border: 1px dashed #0d6efd; position: relative; }
    .edit-logo-btn {
        position: absolute; top: 5px; left: 5px; background: rgba(255,255,255,0.9);
        border: 1px solid #ccc; border-radius: 50%; width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: all 0.2s; z-index: 10;
    }
    .edit-logo-btn:hover { background: #fff; transform: scale(1.1); }
    <?php endif; ?>
  </style>
</head>
<body>

  <!-- شريط الإعلان الديناميكي (أعلى الهيدر) -->
  <?php if ($show_announcement && is_array($announcement)): 
      $ad_link = $announcement['link'] ?? '';
      $has_link = !empty($ad_link);
      $link_target = (isset($announcement['open_new_tab']) && $announcement['open_new_tab'] == 1) ? 'target="_blank"' : 'target="_self"';
  ?>
    <div class="header-announcement-bar text-center p-2 position-relative <?php echo $is_admin ? 'editable-admin-border' : ''; ?>" 
         style="<?php echo (isset($announcement['type']) && $announcement['type'] === 'text') ? "background-color:".htmlspecialchars($announcement['bg_color'] ?? '#000')."; color:".htmlspecialchars($announcement['text_color'] ?? '#fff')."; font-size:".(int)($announcement['font_size'] ?? 16)."px;" : "background-color:#ffffff;"; ?> z-index: 1040; width: 100%; transition: all 0.3s ease-in-out;">
         
        <?php if ($is_admin): ?>
            <!-- زر عائم لتعديل الإعلان يظهر للمدير فقط -->
            <button class="btn btn-sm btn-dark position-absolute top-50 translate-middle-y start-0 ms-2" style="font-size: 11px; z-index:1050;" data-bs-toggle="modal" data-bs-target="#announcementEditModal">📝 تعديل الإعلان</button>
        <?php endif; ?>

        <?php if ($has_link): ?><a href="<?php echo htmlspecialchars($ad_link); ?>" <?php echo $link_target; ?> class="text-decoration-none text-reset d-block"><?php endif; ?>
            <?php if (isset($announcement['type']) && $announcement['type'] === 'text'): ?>
                <span class="fw-bold"><?php echo htmlspecialchars($announcement['announcement_text'] ?? ''); ?></span>
            <?php elseif (isset($announcement['type']) && $announcement['type'] === 'image' && !empty($announcement['image_path'])): ?>
                <img src="<?php echo $path_prefix . htmlspecialchars($announcement['image_path']); ?>" alt="<?php echo htmlspecialchars($announcement['alt_text'] ?? 'إعلان'); ?>" class="img-fluid" style="max-height: 60px; object-fit: contain;">
            <?php endif; ?>
        <?php if ($has_link): ?></a><?php endif; ?>
    </div>
  <?php endif; ?>

  <header>
    <nav class="nav-top navbar py-2" aria-label="روابط التواصل الاجتماعي">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <div class="logo-container d-none d-lg-flex <?php echo $is_admin ? 'editable-admin-border p-1' : ''; ?>">
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
        <div class="logo-container d-lg-none <?php echo $is_admin ? 'editable-admin-border p-1' : ''; ?>">
          <?php if ($is_admin): ?><button class="edit-logo-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal" title="تعديل الشعار">📝</button><?php endif; ?>
          <a class="navbar-brand m-0" href="<?php echo $path_prefix; ?>index.php"><img src="<?php echo $path_prefix . $site_logo_path; ?>" alt="شعار بيتهوفن سيتي" loading="lazy" height="50"></a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav gap-3" id="nav-dynamic-links">
            <?php foreach ($menu_links as $link): ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix . htmlspecialchars($link['url']); ?>"><?php echo htmlspecialchars($link['title']); ?></a></li>
            <?php endforeach; ?>
          </ul>
          <?php if ($is_admin): ?>
              <!-- زر تحرير القائمة بالكامل للمسؤول فقط -->
              <button class="btn btn-sm btn-outline-primary ms-auto" data-bs-toggle="modal" data-bs-target="#menuEditModal">⚙️ إدارة القائمة</button>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </header>

<?php 
// =======================================================
// جدار الحماية: استدعاء ملف أدوات الإدارة للمسؤول فقط
// =======================================================
if ($is_admin) {
    include __DIR__ . '/admin_header_modals.php';
}
?>
