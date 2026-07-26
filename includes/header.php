<?php
date_default_timezone_set('Europe/Berlin'); 

// 0. تضمين ملف الاتصال بقاعدة البيانات
require_once __DIR__ . '/db.php'; // تأكد أن ملف الاتصال موجود في نفس المجلد أو قم بتعديل المسار إن لزم

// دالة مساعدة لجلب قيمة إعداد معين من قاعدة البيانات بسرعة
if (!function_exists('get_setting')) {
    function get_setting(PDO $pdo, string $key, string $default = ''): string {
        static $settings = null;
        if ($settings === null) {
            try {
                $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
                $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            } catch (\Exception $e) {
                $settings = [];
            }
        }
        return $settings[$key] ?? $default;
    }
}

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

// 4. تحميل البيانات مباشرة من قاعدة البيانات (MySQL) بدلاً من ملف الـ JSON
// جلب شعار الموقع باستخدام الدالة المساعدة من جدول site_settings مع الاعتماد على القيمة الافتراضية القديمة
$site_logo_path = get_setting($pdo, 'site_logo_path', 'assets/img/logo.png'); 

// تهيئة افتراضية للمصفوفة لضمان عمل باقي الصفحات دون أخطاء
$data = [
    'announcement' => [], 
    'menu_links' => [], 
    'social_links' => [], 
    'languages' => [], 
    'site_logo_path' => $site_logo_path,
    'hero' => []
];

try {
    // أ. جلب إعدادات الموقع العامة الأخرى إن وجدت في الجدول
    $stmtSettings = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
    while ($row = $stmtSettings->fetch()) {
        if ($row['setting_key'] === 'site_logo_path') {
            $site_logo_path = $row['setting_value'];
        }
    }

    // ب. جلب القائمة الرئيسية من جدول menu_links
    $stmtMenu = $pdo->query("SELECT * FROM menu_links ORDER BY `order` ASC");
    $db_menu = $stmtMenu->fetchAll();
    if (!empty($db_menu)) {
        $data['menu_links'] = $db_menu;
    }

    // ج. جلب روابط التواصل الاجتماعي من جدول social_links
    $stmtSocial = $pdo->query("SELECT * FROM social_links");
    $db_social = $stmtSocial->fetchAll();
    if (!empty($db_social)) {
        $data['social_links'] = $db_social;
    }

    // د. جلب اللغات من جدول languages
    $stmtLang = $pdo->query("SELECT * FROM languages");
    $db_lang = $stmtLang->fetchAll();
    if (!empty($db_lang)) {
        $data['languages'] = $db_lang;
    }

    // هـ. جلب تفاصيل الإعلان من جدول announcements (أحدث إعلان نشط أو أول سجل)
    $stmtAd = $pdo->query("SELECT * FROM announcements ORDER BY id DESC LIMIT 1");
    $db_ad = $stmtAd->fetch();
    if ($db_ad) {
        $data['announcement'] = [
            'status' => $db_ad['status'] ?? 'Draft',
            'type' => $db_ad['type'] ?? 'text',
            'announcement_text' => $db_ad['announcement_text'] ?? '',
            'image_path' => $db_ad['image_path'] ?? '',
            'link' => $db_ad['link'] ?? '',
            'open_new_tab' => $db_ad['open_new_tab'] ?? 0,
            'start_date' => $db_ad['start_date'] ?? '',
            'end_date' => $db_ad['end_date'] ?? '',
            'bg_color' => $db_ad['bg_color'] ?? '#f1f5f9',
            'text_color' => $db_ad['text_color'] ?? '#1e293b',
            'font_size' => $db_ad['font_size'] ?? 16
        ];
    }

} catch (\PDOException $e) {
    // في حال حدوث خطأ في الاتصال، يتم الاعتماد على القيم الافتراضية لمنع توقف الموقع
}

// تحديث المتغيرات لتطابق المنطق القديم تماماً
$data['site_logo_path'] = $site_logo_path;
$menu_links = $data['menu_links'] ?? [
    ["title" => "الرئيسية", "url" => "index.php", "active" => true],
    ["title" => "عن الشركة", "url" => "about.php", "active" => false],
    ["title" => "التعليم العالي", "url" => "education.php", "active" => false],
    ["title" => "التدريب المهني", "url" => "job.php", "active" => false],
    ["title" => "دليل بيتهوفن", "url" => "guide.php", "active" => false],
    ["title" => "تواصل معنا", "url" => "contact.php", "active" => false]
];

// التأكد من ترتيب القائمة بناءً على حقل order إن وجد
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
  <title><?php echo $page_title ?? 'BCS || Beethoven City Services'; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- الملفات الأساسية لكل الصفحات -->
  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/all.min.css">
  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/main.css">
  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/header.css">
  <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/footer.css">

  <!-- حقن ملفات الـ CSS الديناميكية الخاصة بكل صفحة (مثل about.css) -->
  <?php 
  if (isset($page_css) && is_array($page_css)) {
      foreach ($page_css as $css_file) {
          $clean_css = ltrim($css_file, '/');
          echo '<link rel="stylesheet" href="' . $path_prefix . $clean_css . '?v=' . time() . '">' . PHP_EOL;
      }
  }
  ?>
</head>

<body>

<header>
    <!-- NavTop -->
    <nav class="nav-top navbar py-2">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- اللوجو الرئيسي -->
        <div class="editable-wrapper">
          <?php if ($is_admin): ?>
              <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#logoEditModal" title="تعديل الشعار">
                  <i class="bi bi-pencil-fill"></i>
              </button>
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
                  <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#announcementEditModal" title="تعديل الإعلان">
                      <i class="bi bi-pencil-fill"></i>
                  </button>
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
          <?php if ($is_admin): ?>
              <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#socialLinksEditModal" title="تعديل منصات التواصل">
                  <i class="bi bi-pencil-fill"></i>
              </button>
          <?php endif; ?>

          <div class="social-icons d-flex gap-3">
            <?php foreach (($data['social_links'] ?? []) as $s): ?>
                <a href="<?php echo htmlspecialchars($s['url']); ?>"><img src="<?php echo $path_prefix . $s['img'] . '?' . time(); ?>" width="28"></a>
            <?php endforeach; ?>
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
          <?php if ($is_admin): ?>
              <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#menuEditModal" title="تعديل القائمة">
                  <i class="bi bi-pencil-fill"></i>
              </button>
          <?php endif; ?>

          <ul class="navbar-nav gap-3">
            <?php foreach ($menu_links as $link): ?>
                <li class="nav-item">
                  <a class="nav-link <?php echo (($link['active'] ?? 0) == 1 || ($link['is_active'] ?? 0) == 1) ? 'active' : ''; ?>" href="<?php echo $path_prefix . htmlspecialchars($link['url']); ?>">
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
                  <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langEditModal" title="تعديل اللغات">
                      <i class="bi bi-pencil-fill"></i>
                  </button>
              <?php endif; ?>
              
              <button class="btn lang-switch d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown">
                  <img src="<?php echo $path_prefix; ?>assets/img/home/global.svg">
                  <span><?php echo $current_lang_name ?? 'العربية'; ?></span>
                  <img src="<?php echo $path_prefix; ?>assets/img/home/arowwdown.svg">
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                  <?php foreach (($data['languages'] ?? [['name' => 'العربية', 'url' => 'index.php']]) as $lang): ?>
                      <li><a class="dropdown-item" href="<?php echo $path_prefix . htmlspecialchars($lang['url']); ?>"><?php echo htmlspecialchars($lang['name']); ?></a></li>
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
