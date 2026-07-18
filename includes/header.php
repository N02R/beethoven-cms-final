<?php
date_default_timezone_set('Europe/Berlin'); 
// 1. بدء الجلسة بأمان
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 2. تعريف المسار الأساسي
if (!isset($path_prefix)) { $path_prefix = ''; }

// 3. دالة التحقق من صلاحيات المسؤول
if (!function_exists('isUserAdmin')) {
    function isUserAdmin() {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
$is_admin = isUserAdmin(); 

// 4. تحميل البيانات (تم تعريف $data هنا لتكون متاحة لكل الصفحات)
$site_logo_path = 'assets/img/logo.png'; 
$config_file_path = __DIR__ . '/../announcement_config.json';
// تهيئة افتراضية للمصفوفة لتجنب أي خطأ في الصفحات الأخرى
$data = [
    'announcement' => [], 
    'menu_links' => [], 
    'social_links' => [], 
    'languages' => [], 
    'site_logo_path' => $site_logo_path,
    'hero' => []
];

if (file_exists($config_file_path)) {
    $decoded_data = json_decode(file_get_contents($config_file_path), true);
    if (is_array($decoded_data)) {
        $data = array_merge($data, $decoded_data);
    }
}

$site_logo_path = $data['site_logo_path'] ?? $site_logo_path;
$menu_links = $data['menu_links'] ?? [
    ["title" => "الرئيسية", "url" => "index.php", "active" => true],
    ["title" => "عن الشركة", "url" => "about.php", "active" => false],
    ["title" => "التعليم العالي", "url" => "education.php", "active" => false],
    ["title" => "التدريب المهني", "url" => "job.php", "active" => false],
    ["title" => "دليل بيتهوفن", "url" => "guide.php", "active" => false],
    ["title" => "تواصل معنا", "url" => "contact.php", "active" => false]
];
usort($menu_links, function($a, $b) { return ($a['order'] ?? 0) <=> ($b['order'] ?? 0); });

// 5. تجهيز منطق الإعلان الموحد
$ad = $data['announcement'] ?? [];
$is_published = ($ad['status'] ?? 'Draft') === 'Published';
$current_time = date('Y-m-d\TH:i');
$is_in_time = true;
if (!empty($ad['start_date']) && $current_time < $ad['start_date']) { $is_in_time = false; }
if (!empty($ad['end_date']) && $current_time > $ad['end_date']) { $is_in_time = false; }
$is_visible = ($is_published && $is_in_time);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'BCS || الصفحة الرئيسية'; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/minify.php<?php echo (isset($page_css) ? "?files=" . implode(',', array_map('basename', $page_css)) : ""); ?>">


</head>
<body>

<header>
    <!-- NavTop -->
    <nav class="nav-top navbar py-2">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- اللوجو الرئيسي -->
        <div class="editable-wrapper">
          <?php if ($is_admin): ?>
            <button class="admin-edit-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal"><i class="bi bi-pencil"></i></button>
          <?php endif; ?>
          <a class="navbar-brand m-0" href="<?php echo $path_prefix; ?>index.php">
            <img src="<?php echo $path_prefix . $site_logo_path . '?' . time(); ?>" width="178" height="72" loading="lazy">
          </a>
        </div>

        <!-- منطقة الإعلان -->
        <div class="flex-grow-1 d-none d-lg-flex justify-content-center align-items-center px-4">
          <?php if ($is_visible || $is_admin): ?>
            <div class="editable-wrapper" style="max-width: 500px; width: 100%;">
              <?php if ($is_admin): ?>
                <button class="admin-edit-btn" data-bs-toggle="modal" data-bs-target="#announcementEditModal"><i class="bi bi-pencil"></i></button>
              <?php endif; ?>
              <?php if (!empty($ad['link'])): ?><a href="<?php echo htmlspecialchars($ad['link']); ?>" <?php echo (($ad['open_new_tab'] ?? 0) == 1 ? 'target="_blank"' : ''); ?>><?php endif; ?>
                <?php if (($ad['type'] ?? 'text') === 'text'): ?>
                  <div class="p-2 rounded shadow-sm" style="background-color: <?php echo $ad['bg_color'] ?? '#f1f5f9'; ?>; color: <?php echo $ad['text_color'] ?? '#1e293b'; ?>; font-size: <?php echo $ad['font_size'] ?? '16'; ?>px;">
                    <marquee behavior="scroll" direction="right"><?php echo htmlspecialchars($ad['announcement_text'] ?? 'مرحباً بكم!'); ?></marquee>
                  </div>
                <?php else: ?>
                  <div class="rounded overflow-hidden shadow-sm" style="max-height: 65px;"><img src="<?php echo $path_prefix . ($ad['image_path'] ?? 'assets/img/default-ad.png') . '?' . time(); ?>" class="img-fluid" style="object-fit: cover; max-height: 65px;"></div>
                <?php endif; ?>
              <?php if (!empty($ad['link'])): ?></a><?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- السوشيال ميديا -->
        <div class="editable-wrapper d-none d-lg-flex">
          <?php if ($is_admin): ?><button class="admin-edit-btn" data-bs-toggle="modal" data-bs-target="#socialLinksEditModal"><i class="bi bi-pencil"></i></button><?php endif; ?>
          <div class="social-icons d-flex gap-3">
            <?php foreach (($data['social_links'] ?? []) as $s): ?><a href="<?php echo $s['url']; ?>"><img src="<?php echo $path_prefix . $s['img'] . '?' . time(); ?>" width="28"></a><?php endforeach; ?>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- Main Header -->
    <nav id="main-header" class="navbar navbar-expand-lg py-3" aria-label="القائمة الرئيسية">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- اللوجو (Mobile Only) -->
        <div class="d-lg-none">
          <a class="navbar-brand" href="<?php echo $path_prefix; ?>index.php">
            <img src="<?php echo $path_prefix . $site_logo_path . '?' . time(); ?>" alt="Logo" height="50">
          </a>
        </div>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse editable-wrapper">
          <?php if ($is_admin): ?><button class="admin-edit-btn" data-bs-toggle="modal" data-bs-target="#menuEditModal"><i class="bi bi-pencil"></i></button><?php endif; ?>
          <ul class="navbar-nav gap-3">
            <?php foreach ($menu_links as $link): ?>
                <li class="nav-item">
                  <a class="nav-link <?php echo ($link['active'] ?? false) ? 'active' : ''; ?>" href="<?php echo $path_prefix . htmlspecialchars($link['url']); ?>">
                    <?php echo htmlspecialchars($link['title']); ?>
                  </a>
                </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Controls -->
        <div class="d-flex align-items-center gap-3">
          <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"><span class="navbar-toggler-icon"></span></button>
          
          <div class="dropdown editable-wrapper">
              <?php if ($is_admin): ?>
                  <button class="admin-edit-btn" data-bs-toggle="modal" data-bs-target="#langEditModal" style="top:-30px; right:0;">
                      <i class="bi bi-pencil"></i>
                  </button>
              <?php endif; ?>
              
              <button class="btn lang-switch d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown">
                  <img src="<?php echo $path_prefix; ?>assets/img/home/global.svg">
                  <span><?php echo $current_lang_name ?? 'العربية'; ?></span>
                  <img src="<?php echo $path_prefix; ?>assets/img/home/arowwdown.svg">
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                  <?php foreach (($data['languages'] ?? [['name' => 'العربية', 'url' => 'index.php']]) as $lang): ?>
                      <li><a class="dropdown-item" href="<?php echo $path_prefix . $lang['url']; ?>"><?php echo $lang['name']; ?></a></li>
                  <?php endforeach; ?>
              </ul>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
      <div class="offcanvas-header"><h5 class="offcanvas-title"><img src="<?php echo $path_prefix . $site_logo_path . '?' . time(); ?>" height="50"></h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button></div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
            <?php foreach ($menu_links as $link): ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo $path_prefix . htmlspecialchars($link['url']); ?>"><?php echo htmlspecialchars($link['title']); ?></a></li>
            <?php endforeach; ?>
        </ul>
      </div>
    </div>
</header>

<?php if ($is_admin) { include __DIR__ . '/admin_header_modals.php'; } ?>
