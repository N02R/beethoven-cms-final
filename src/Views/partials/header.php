<?php
declare(strict_types=1);

// استدعاء ملف الدوال لجلب البيانات من قاعدة البيانات
// استدعاء ملف الدوال بالرجوع ثلاثة مجلدات للوصول لجذر المشروع
require_once __DIR__ . '/../../../functions.php';
 // عدل المسار حسب مكان وجود الهيدر بالنسبة للجذر

// التحقق من صلاحيات المشرف باستخدام جلسة النظام المركزي
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin');

// جلب الإعدادات مباشرة من قاعدة البيانات عبر دالة get_setting مع قيم افتراضية آمنة
$site_logo_path  = get_setting('site_logo_path', 'assets/img/logo.png');
$menu_links      = get_setting('menu_links', []);
$social_links    = get_setting('social_links', []);
$languages       = get_setting('languages', []);
$ad              = get_setting('announcement', []);

// حساب حالة ظهور الإعلان
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
  <meta name="csrf-token" content="<?= htmlspecialchars($csrf_token ?? '') ?>">

  <title><?php echo htmlspecialchars($page_title ?? 'BCS || Beethoven City Services'); ?></title>
  
  <!-- مكتبات الأيقونات والـ Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- ملفات التنسيق المحلية -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/header.css">

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

          <a class="navbar-brand m-0" href="/">
            <img src="/<?php echo htmlspecialchars($site_logo_path) . '?' . time(); ?>" width="178" height="72" loading="lazy" alt="Logo">
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
                  <div class="rounded overflow-hidden shadow-sm" style="max-height: 65px;"><img src="/<?php echo htmlspecialchars($ad['image_path'] ?? 'assets/img/default-ad.png') . '?' . time(); ?>" class="img-fluid" style="object-fit: cover; max-height: 65px;" alt="Advertisement"></div>
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
                <a href="<?php echo htmlspecialchars($s['url']); ?>"><img src="/<?php echo htmlspecialchars($s['img']) . '?' . time(); ?>" width="28" alt="social"></a>
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
          <a class="navbar-brand" href="/">
            <img src="/<?php echo htmlspecialchars($site_logo_path) . '?' . time(); ?>" alt="Logo" height="50">
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
                  <a class="nav-link <?php echo (($link['active'] ?? 0) == 1 || ($link['is_active'] ?? 0) == 1) ? 'active' : ''; ?>" href="/<?php echo ltrim(htmlspecialchars($link['url']), '/'); ?>">
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
                  <img src="/assets/img/home/global.svg.webp" alt="lang" width="20">
                  <span><?php echo $current_lang_name ?? 'العربية'; ?></span>
                  <img src="/assets/img/home/arowwdown.svg.webp" alt="arrow" width="20">
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                  <?php foreach ($languages as $lang): ?>
                      <li><a class="dropdown-item" href="/<?php echo ltrim(htmlspecialchars($lang['url'] ?? ''), '/'); ?>"><?php echo htmlspecialchars($lang['name']); ?></a></li>
                  <?php endforeach; ?>
              </ul>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
      <div class="offcanvas-header"><h5 class="offcanvas-title"><img src="/<?php echo htmlspecialchars($site_logo_path) . '?' . time(); ?>" height="50" alt="Logo"></h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button></div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
            <?php foreach ($menu_links as $link): ?>
                <li class="nav-item"><a class="nav-link" href="/<?php echo ltrim(htmlspecialchars($link['url']), '/'); ?>"><?php echo htmlspecialchars($link['title']); ?></a></li>
            <?php endforeach; ?>
        </ul>
      </div>
    </div>
</header>
