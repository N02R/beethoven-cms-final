<?php
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

// 4. تحميل البيانات من المصدر الموحد
$site_logo_path = 'assets/img/logo.png'; 
$config_file_path = __DIR__ . '/../announcement_config.json';
$data = ['announcement' => [], 'menu_links' => [], 'social_links' => [], 'site_logo_path' => $site_logo_path];

if (file_exists($config_file_path)) {
    $data = json_decode(file_get_contents($config_file_path), true) ?? $data;
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

$ad = $data['announcement'] ?? [];
$is_visible = ($ad['status'] ?? 'Draft') === 'Published';
$current_time = date('Y-m-d\TH:i');
if ($is_visible) {
    if (!empty($ad['start_date']) && $current_time < $ad['start_date']) { $is_visible = false; }
    if (!empty($ad['end_date']) && $current_time > $ad['end_date']) { $is_visible = false; }
}
?>

<header>
    <!-- nav top -->
    <nav class="nav-top navbar py-2">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- Logo Desktop -->
        <div class="logo-container d-none d-lg-flex <?php echo $is_admin ? 'editable-admin-border p-1' : ''; ?>">
          <?php if ($is_admin): ?><button class="edit-logo-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal">📝</button><?php endif; ?>
          <a class="navbar-brand m-0" href="<?php echo $path_prefix; ?>index.php">
            <img src="<?php echo $path_prefix . $site_logo_path; ?>" width="178" height="72" loading="lazy">
          </a>
        </div>

        <!-- Dynamic Announcement -->
        <div class="flex-grow-1 d-none d-lg-flex justify-content-center align-items-center px-4">
          <?php if ($is_visible || $is_admin): ?>
            <div class="w-100 text-center <?php echo $is_admin ? 'editable-admin-border position-relative p-1' : ''; ?>" style="max-width: 500px;">
              <?php if ($is_admin): ?>
                <button class="edit-announcement-btn" data-bs-toggle="modal" data-bs-target="#announcementEditModal" style="position: absolute; top: -10px; left: -10px; z-index: 10; background: #0d6efd; border: none; border-radius: 50%; width: 24px; height: 24px; color: white; display: flex; align-items: center; justify-content: center;">📝</button>
              <?php endif; ?>

              <?php if (!empty($ad['link'])): ?><a href="<?php echo htmlspecialchars($ad['link']); ?>" <?php echo (($ad['open_new_tab'] ?? 0) == 1 ? 'target="_blank"' : ''); ?>><?php endif; ?>
                <?php if (($ad['type'] ?? 'text') === 'text'): ?>
                  <div class="p-2 rounded shadow-sm" style="background-color: <?php echo $ad['bg_color'] ?? '#f1f5f9'; ?>; color: <?php echo $ad['text_color'] ?? '#1e293b'; ?>; font-size: <?php echo $ad['font_size'] ?? '16'; ?>px;">
                    <marquee behavior="scroll" direction="right"><?php echo htmlspecialchars($ad['announcement_text'] ?? 'مرحباً بكم!'); ?></marquee>
                  </div>
                <?php else: ?>
                  <div class="rounded overflow-hidden shadow-sm" style="max-height: 65px;">
                    <img src="<?php echo $path_prefix . ($ad['image_path'] ?? 'assets/img/default-ad.png'); ?>" class="img-fluid" style="object-fit: cover; max-height: 65px;">
                  </div>
                <?php endif; ?>
              <?php if (!empty($ad['link'])): ?></a><?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Social Icons -->
        <div class="social-icons d-none d-lg-flex gap-3 position-relative <?php echo $is_admin ? 'editable-admin-border p-1' : ''; ?>">
          <?php if ($is_admin): ?><button class="edit-social-btn" data-bs-toggle="modal" data-bs-target="#socialLinksEditModal">📝</button><?php endif; ?>
          <?php foreach (($data['social_links'] ?? []) as $s): ?>
            <a href="<?php echo $s['url']; ?>"><img src="<?php echo $path_prefix . $s['img']; ?>" width="28"></a>
          <?php endforeach; ?>
        </div>
      </div>
    </nav>
    
    <!-- main nav -->
    <nav id="main-header" class="navbar navbar-expand-lg py-3">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        <div class="logo-container d-lg-none <?php echo $is_admin ? 'editable-admin-border p-1' : ''; ?>">
          <?php if ($is_admin): ?><button class="edit-logo-btn" data-bs-toggle="modal" data-bs-target="#logoEditModal">📝</button><?php endif; ?>
          <a class="navbar-brand m-0" href="<?php echo $path_prefix; ?>index.php">
            <img src="<?php echo $path_prefix . $site_logo_path; ?>" height="50">
          </a>
        </div>
        
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav gap-3">
            <?php foreach ($menu_links as $link): ?>
                <li class="nav-item"><a class="nav-link <?php echo ($link['active'] ?? false) ? 'active' : ''; ?>" href="<?php echo $path_prefix . htmlspecialchars($link['url']); ?>"><?php echo htmlspecialchars($link['title']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
        
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"><span class="navbar-toggler-icon"></span></button>
      </div>
    </nav>
    
    <!-- offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"><img src="<?php echo $path_prefix . $site_logo_path; ?>" height="50"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
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
