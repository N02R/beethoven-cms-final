<?php
declare(strict_types=1);

// تحديد اللغة والاتجاه الحاليين من النظام
$current_lang = get_current_lang();
$current_dir = ($current_lang === 'ar') ? 'rtl' : 'ltr';
$current_lang_code = $current_lang;

// تنظيف المسار الحالي من أي بادئة لغة سابقة لمنع تكرارها (مثل /en/de/home)
$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$path_segments = explode('/', trim($request_path, '/'));
if (!empty($path_segments[0]) && in_array($path_segments[0], ['ar', 'en', 'de'], true)) {
    array_shift($path_segments);
}
$clean_uri = '/' . implode('/', $path_segments);
$clean_uri = ($clean_uri === '/') ? '' : $clean_uri;

// التحقق من صلاحيات المشرف باستخدام جلسة النظام المركزي
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin');

// جلب الإعدادات مع معالجة آمنة لضمان تحويل بيانات الـ JSON إلى مصفوفات بشكل صحيح
$site_logo_raw  = get_setting('site_logo_path', 'assets/img/logo.png');

// معالجة اللوجو ليكون متعدد اللغات بناءً على الـ JSON أو مسار نصي عادي
if (is_string($site_logo_raw) && str_starts_with(trim($site_logo_raw), '{')) {
    $site_logo_array = json_decode($site_logo_raw, true) ?? [];
    $site_logo_path = $site_logo_array[$current_lang] ?? $site_logo_array['de'] ?? $site_logo_array['ar'] ?? 'assets/img/logo.png';
} else {
    $site_logo_path = $site_logo_raw;
}

$menu_links      = get_setting('menu_links', []);
if (is_string($menu_links)) {
    $menu_links = json_decode($menu_links, true) ?? [];
}

$social_links    = get_setting('social_links', []);
if (is_string($social_links)) {
    $social_links = json_decode($social_links, true) ?? [];
}

$languages       = get_setting('languages', []);
if (is_string($languages)) {
    $languages = json_decode($languages, true) ?? [];
}

$ad_raw          = get_setting('announcement', []);
$ad              = is_string($ad_raw) ? (json_decode($ad_raw, true) ?? []) : $ad_raw;

// استخراج نص الإعلان بناءً على اللغة الحالية مع دعم القيم الاحتياطية
$announcement_text = '';
if (isset($ad[$current_lang]['announcement_text'])) {
    $announcement_text = $ad[$current_lang]['announcement_text'];
} elseif (isset($ad['de']['announcement_text'])) {
    $announcement_text = $ad['de']['announcement_text'];
} elseif (isset($ad['announcement_text'])) {
    $announcement_text = $ad['announcement_text'];
}

// حساب حالة ظهور الإعلان
$is_published = ($ad['status'] ?? 'Draft') === 'Published';
$current_time = date('Y-m-d\TH:i');
$is_in_time = true;
if (!empty($ad['start_date']) && $current_time < $ad['start_date']) { $is_in_time = false; }
if (!empty($ad['end_date']) && $current_time > $ad['end_date']) { $is_in_time = false; }
$is_visible = ($is_published && $is_in_time);
?>

<!DOCTYPE html>
<html lang="<?php echo $current_lang_code; ?>" dir="<?php echo $current_dir; ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo \App\Core\Security::generateCsrfToken(); ?>">

  <title><?php echo htmlspecialchars($page_title ?? 'BCS || Beethoven City Services'); ?></title>
  
  <!-- مكتبات الأيقونات والـ Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- ملفات التنسيق المحلية العامة -->
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/header.css">

  <style>
    .editable-wrapper { position: relative; }
    .edit-pen {
        position: absolute; top: -8px; right: -8px; z-index: 1050;
        background-color: #ffc107; color: #000; border: none; border-radius: 50%;
        width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2); cursor: pointer; transition: transform 0.2s ease;
    }
    .edit-pen:hover { transform: scale(1.15); background-color: #e0a800; }
  </style>

  <!-- ملفات الـ CSS الخاصة بالصفحات الفردية -->
  <?php 
  if (isset($page_css) && is_array($page_css)) {
      foreach ($page_css as $css_file) {
          echo '<link rel="stylesheet" href="/' . ltrim($css_file, '/') . '?v=' . time() . '">' . PHP_EOL;
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

          <a class="navbar-brand m-0" href="/<?php echo $current_lang_code; ?>">
            <img src="<?php echo get_image_url($site_logo_path, '/assets/img/logo.png'); ?>" width="178" height="72" loading="lazy" alt="Logo">
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

              <?php if (!empty($ad['link'])): ?>
                  <a href="<?php echo htmlspecialchars($ad['link']); ?>" <?php echo (($ad['open_new_tab'] ?? 0) == 1 ? 'target="_blank" rel="noopener noreferrer"' : ''); ?>>
              <?php endif; ?>
              
                <?php if (($ad['type'] ?? 'text') === 'text'): ?>
                  <div class="p-2 rounded shadow-sm" style="background-color: <?php echo htmlspecialchars($ad['bg_color'] ?? '#f1f5f9'); ?>; color: <?php echo htmlspecialchars($ad['text_color'] ?? '#1e293b'); ?>; font-size: <?php echo htmlspecialchars((string)($ad['font_size'] ?? '16')); ?>px;">
                    <marquee behavior="scroll" direction="<?php echo ($current_dir === 'rtl') ? 'right' : 'left'; ?>"><?php echo htmlspecialchars($announcement_text ?: 'Welcome!'); ?></marquee>
                  </div>
                <?php else: ?>
                  <div class="rounded overflow-hidden shadow-sm" style="max-height: 65px;">
                    <img src="<?php echo get_image_url($ad['image_path'] ?? null, '/assets/img/default-ad.png'); ?>" class="img-fluid" style="object-fit: cover; max-height: 65px;" alt="Advertisement">
                  </div>
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
            <?php foreach ($social_links as $s): ?>
                <a href="<?php echo htmlspecialchars($s['url'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer">
                  <img src="<?php echo get_image_url($s['img'] ?? null); ?>" width="28" alt="social">
                </a>
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
          <a class="navbar-brand" href="/<?php echo $current_lang_code; ?>">
            <img src="<?php echo get_image_url($site_logo_path, '/assets/img/logo.png'); ?>" alt="Logo" height="50">
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
            <?php foreach ($menu_links as $link): 
                $link_url = ltrim($link['url'] ?? '', '/');
                // استخراج العنوان حسب اللغة الحالية من هيكلية الـ JSON الجديدة مع بدائل آمنة
                $link_title = $link[$current_lang]['title'] ?? $link['de']['title'] ?? $link['ar']['title'] ?? ($link['title'] ?? '');
            ?>
                <li class="nav-item">
                  <a class="nav-link" href="/<?php echo $current_lang_code . '/' . $link_url; ?>">
                    <?php echo htmlspecialchars($link_title); ?>
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
                  <img src="<?php echo get_image_url('assets/img/home/global.svg.webp'); ?>" alt="lang" width="20">
                  <span>
                    <?php 
                        if ($current_lang === 'en') echo 'English';
                        elseif ($current_lang === 'ar') echo 'العربية';
                        else echo 'Deutsch';
                    ?>
                  </span>
                  <img src="<?php echo get_image_url('assets/img/home/arowwdown.svg.webp'); ?>" alt="arrow" width="15">
              </button>

              <!-- روابط تبديل اللغات النظيفة بدون تراكم -->
              <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/de<?php echo $clean_uri; ?>">Deutsch</a></li>
                  <li><a class="dropdown-item" href="/en<?php echo $clean_uri; ?>">English</a></li>
                  <li><a class="dropdown-item" href="/ar<?php echo $clean_uri; ?>">العربية</a></li>
              </ul>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">
          <img src="<?php echo get_image_url($site_logo_path, '/assets/img/logo.png'); ?>" height="50" alt="Logo">
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
            <?php foreach ($menu_links as $link): 
                $link_url = ltrim($link['url'] ?? '', '/');
                $link_title = $link[$current_lang]['title'] ?? $link['de']['title'] ?? $link['ar']['title'] ?? ($link['title'] ?? '');
            ?>
                <li class="nav-item">
                  <a class="nav-link" href="/<?php echo $current_lang_code . '/' . $link_url; ?>">
                    <?php echo htmlspecialchars($link_title); ?>
                  </a>
                </li>
            <?php endforeach; ?>
        </ul>
      </div>
    </div>
</header>
