<?php
// 1. بدء الجلسة بأمان
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. تعريف المسار الأساسي للملفات
if (!isset($path_prefix)) { 
    $path_prefix = ''; 
}

// 3. دالة التحقق من صلاحيات المسؤول
if (!function_exists('isUserAdmin')) {
    function isUserAdmin() {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}

$is_admin = isUserAdmin(); 
$site_logo_path = 'assets/img/logo.png'; 

// =======================================================
// منطق الإعلان التفاعلي وقراءة البيانات
// =======================================================
$config_file_path = __DIR__ . '/../announcement_config.json';
$announcement = [];
$menu_links = [];

if (file_exists($config_file_path)) {
    $announcement = json_decode(file_get_contents($config_file_path), true) ?? [];
    
    // استخراج اللوجو
    if (!empty($announcement['site_logo_path'])) {
        $site_logo_path = $announcement['site_logo_path'];
    }

    // استخراج القائمة
    if (isset($announcement['menu_links']) && is_array($announcement['menu_links'])) {
        $menu_links = $announcement['menu_links'];
        usort($menu_links, function($a, $b) { return ($a['order'] ?? 0) <=> ($b['order'] ?? 0); });
    } else {
        $menu_links = [
            ["title" => "الرئيسية", "url" => "index.php", "active" => true],
            ["title" => "عن الشركة", "url" => "aboutus.php", "active" => false],
            ["title" => "التعليم العالي", "url" => "education.php", "active" => false],
            ["title" => "التدريب المهني", "url" => "job.php", "active" => false],
            ["title" => "دليل بيتهوفن", "url" => "guide.php", "active" => false],
            ["title" => "تواصل معنا", "url" => "contact.php", "active" => false]
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'BCS || الصفحة الرئيسية'; ?></title>
  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/minify.php">
  <style>
    .editable-admin-border { border: 1px dashed #0d6efd !important; position: relative; }
    .edit-logo-btn, .edit-announcement-btn, .edit-social-btn {
        position: absolute; top: -10px; left: -10px; z-index: 999; background: #0d6efd;
        border: none; border-radius: 50%; width: 24px; height: 24px; color: white;
        display: flex; align-items: center; justify-content: center; cursor: pointer;
    }
  </style>
</head>
<body>

  <header>
    <!-- nav top -->
    <nav class="nav-top navbar py-2">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- Logo -->
        <div class="logo-container d-none d-lg-flex <?php echo $is_admin ? 'editable-admin-border p-1' : ''; ?>">
          <?php if ($is_admin): ?><button class="edit-logo-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal">📝</button><?php endif; ?>
          <a class="navbar-brand m-0" href="index.php"><img src="<?php echo $path_prefix . $site_logo_path; ?>" width="178" height="72"></a>
        </div>

        <!-- مركز الإعلانات الديناميكي -->
        <div class="flex-grow-1 d-none d-lg-flex justify-content-center px-4">
          <?php 
          $current_time = date('Y-m-d H:i:s');
          $status = $announcement['status'] ?? 'Draft';
          $start = $announcement['start_date'] ?? '';
          $end = $announcement['end_date'] ?? '';
          
          // منطق العرض: منشور + داخل التوقيت، أو أنكِ أدمن
          $is_published = ($status === 'Published');
          $is_in_time = (empty($start) || $current_time >= $start) && (empty($end) || $current_time <= $end);
          $should_show = ($is_published && $is_in_time) || $is_admin;

          if ($should_show): 
          ?>
            <div class="w-100 text-center <?php echo $is_admin ? 'editable-admin-border position-relative p-2' : ''; ?>" style="max-width: 500px;">
              <?php if ($is_admin): ?>
                <button class="edit-announcement-btn" data-bs-toggle="modal" data-bs-target="#announcementEditModal">📝</button>
                <?php if (!$is_published || !$is_in_time): ?><span class="badge bg-warning position-absolute" style="top: -10px; right: 10px; font-size: 10px;">مخفي عن المستخدمين</span><?php endif; ?>
              <?php endif; ?>

              <?php 
              $link = $announcement['link'] ?? '';
              $target = ($announcement['open_new_tab'] ?? 0) == 1 ? 'target="_blank"' : '';
              if ($link) echo '<a href="'.$link.'" '.$target.' class="text-decoration-none">';
              
              if (($announcement['type'] ?? 'text') === 'text'): ?>
                <div class="p-2 rounded" style="background-color: <?php echo $announcement['bg_color'] ?? '#f1f5f9'; ?>; color: <?php echo $announcement['text_color'] ?? '#1e293b'; ?>;">
                  <marquee><?php echo htmlspecialchars($announcement['announcement_text'] ?? 'مرحباً بكم!'); ?></marquee>
                </div>
              <?php else: ?>
                <img src="<?php echo $path_prefix . ($announcement['announcement_image_path'] ?? ''); ?>" style="max-height: 60px;">
              <?php endif; 
              if ($link) echo '</a>'; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Social Icons -->
        <div class="social-icons d-none d-lg-flex gap-3 <?php echo $is_admin ? 'editable-admin-border p-1' : ''; ?>">
          <?php if ($is_admin): ?><button class="edit-social-btn" data-bs-toggle="modal" data-bs-target="#socialLinksEditModal">📝</button><?php endif; ?>
          <?php 
          $socials = $announcement['social_links'] ?? [];
          foreach ($socials as $s): ?>
            <a href="<?php echo $s['url']; ?>"><img src="<?php echo $path_prefix . $s['img']; ?>" width="28"></a>
          <?php endforeach; ?>
        </div>
      </div>
    </nav>
    
    <!-- باقي القائمة الرئيسية والـ Offcanvas كما هي في ملفك -->
    <nav id="main-header" class="navbar navbar-expand-lg py-3">...</nav>
  </header>

<?php if ($is_admin) include __DIR__ . '/admin_header_modals.php'; ?>
