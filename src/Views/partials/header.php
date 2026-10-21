<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Header Partial (Production-Ready & Secure)
 * يدعم تعدد اللغات بالكامل (لوجو، إعلانات نصية وصور، قوائم، لغات ديناميكية، وتواصل اجتماعي)
 */

// 1. تحديد اللغة والاتجاه الحاليين من النظام
$current_lang = get_current_lang();
$current_dir = ($current_lang === 'ar') ? 'rtl' : 'ltr';
$current_lang_code = $current_lang;

// 2. تنظيف المسار الحالي من أي بادئة لغة سابقة لمنع تكرارها
$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$path_segments = explode('/', trim($request_path, '/'));
if (!empty($path_segments[0]) && in_array($path_segments[0], ['ar', 'en', 'de'], true)) {
    array_shift($path_segments);
}
$clean_uri = '/' . implode('/', $path_segments);
$clean_uri = ($clean_uri === '/') ? '' : $clean_uri;

// 3. التحقق الآمن والصارم لصلاحيات المشرف وفق معايير الجودة والأمان
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true 
            && isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'super_admin'], true);

// 4. جلب ومعالجة اللوجو متعدد اللغات من قاعدة البيانات
$site_logo_raw = get_setting('site_logo_path', 'assets/img/logo.png');
$site_logo_path = 'assets/img/logo.png';

if (is_string($site_logo_raw)) {
    $trimmed_logo = trim($site_logo_raw);
    if (str_starts_with($trimmed_logo, '{')) {
        $logo_array = json_decode($trimmed_logo, true);
        if (is_array($logo_array)) {
            $site_logo_path = $logo_array[$current_lang] ?? ($logo_array['de'] ?? ($logo_array['ar'] ?? ($logo_array['en'] ?? 'assets/img/logo.png')));
        }
    } else {
        $site_logo_path = $site_logo_raw;
    }
}

// 5. جلب اللغات المفعلة من قاعدة البيانات
$languages_raw = get_setting('languages', []);
if (is_string($languages_raw)) {
    $languages_raw = json_decode($languages_raw, true);
}
$available_languages = is_array($languages_raw) ? $languages_raw : [
    ['code' => 'de', 'name' => 'Deutsch', 'active' => true],
    ['code' => 'en', 'name' => 'English', 'active' => true],
    ['code' => 'ar', 'name' => 'العربية', 'active' => true]
];
$active_languages = array_filter($available_languages, fn($lang) => ($lang['active'] ?? true) === true);

$current_lang_name = 'Deutsch';
foreach ($active_languages as $lang) {
    if ($lang['code'] === $current_lang) {
        $current_lang_name = $lang['name'];
        break;
    }
}

// 6. جلب القوائم ومنصات التواصل الاجتماعي
$menu_links = get_setting('menu_links', []);
if (is_string($menu_links)) {
    $menu_links = json_decode($menu_links, true);
}
$menu_links = array_values(is_array($menu_links) ? $menu_links : []);

$social_links = get_setting('social_links', []);
if (is_string($social_links)) {
    $social_links = json_decode($social_links, true);
}
$social_links = is_array($social_links) ? $social_links : [];

// 7. استخراج وتأمين بيانات الإعلان (Announcement)
$rawAnnouncement = get_setting('announcement', []);
if (is_string($rawAnnouncement)) {
    $allAds = json_decode($rawAnnouncement, true);
    $allAds = is_array($allAds) ? $allAds : [];
} else {
    $allAds = is_array($rawAnnouncement) ? $rawAnnouncement : [];
}

$ad = $allAds[$current_lang] ?? ($allAds['ar'] ?? ($allAds['de'] ?? (is_array($rawAnnouncement) ? $rawAnnouncement : [])));

$hasText = !empty($ad['announcement_text']);
$hasImage = !empty($ad['image_path']) && $ad['image_path'] !== 'assets/img/default-ad.png';
$status = $ad['status'] ?? 'Draft';
$current_time = date('Y-m-d\TH:i');

$is_in_time = true;
if (!empty($ad['start_date']) && $current_time < $ad['start_date']) { $is_in_time = false; }
if (!empty($ad['end_date']) && $current_time > $ad['end_date']) { $is_in_time = false; }

$is_visible = (($status === 'Published' || $status === 'active' || $hasText || $hasImage) && $is_in_time);

// دالة مساعدة لاستخراج عنوان القائمة
$getMenuTitle = function(array $link) use ($current_lang): string {
    if (isset($link[$current_lang]['title']) && is_string($link[$current_lang]['title'])) {
        return $link[$current_lang]['title'];
    }
    if (isset($link['title'])) {
        if (is_array($link['title'])) {
            return $link['title'][$current_lang] ?? ($link['title']['ar'] ?? ($link['title']['de'] ?? ''));
        }
        return (string)$link['title'];
    }
    return $link['de']['title'] ?? ($link['ar']['title'] ?? '');
};
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($current_lang_code, ENT_QUOTES, 'UTF-8'); ?>" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo htmlspecialchars(\App\Core\Security::generateCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">

  <title><?php echo htmlspecialchars($page_title ?? 'BCS || Beethoven City Services', ENT_QUOTES, 'UTF-8'); ?></title>
  
  <!-- مكتبات الأيقونات والـ Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- ملفات التنسيق المحلية العامة -->
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/header.css">

  <!-- ملفات الـ CSS الخاصة بالصفحات الفردية -->
  <?php 
  if (isset($page_css) && is_array($page_css)) {
      foreach ($page_css as $css_file) {
          echo '<link rel="stylesheet" href="/' . htmlspecialchars(ltrim($css_file, '/'), ENT_QUOTES, 'UTF-8') . '?v=' . time() . '">' . PHP_EOL;
      }
  }
  ?>
</head>

<body>

<header>
    <!-- NavTop -->
    <nav class="nav-top navbar py-2">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- اللوجو الرئيسي المتعدد اللغات -->
        <div class="navbar-brand-wrapper">
          <div class="editable-wrapper d-inline-block">
            <?php if ($is_admin): ?>
                <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#logoEditModal" title="تعديل الشعار">
                    <i class="bi bi-pencil-fill"></i>
                </button>
            <?php endif; ?>

            <a class="navbar-brand m-0" href="/<?php echo htmlspecialchars($current_lang_code, ENT_QUOTES, 'UTF-8'); ?>">
              <img src="<?php echo htmlspecialchars(get_image_url($site_logo_path, '/assets/img/logo.png'), ENT_QUOTES, 'UTF-8'); ?>" width="178" height="72" loading="lazy" alt="Logo">
            </a>
          </div>
        </div>

        <!-- منطقة الإعلان العلوي -->
        <div class="flex-grow-1 d-none d-lg-flex justify-content-center align-items-center px-4">
          <?php if ($is_visible || $is_admin): ?>
            <div class="editable-wrapper d-block w-100" style="max-width: 500px;">
              <?php if ($is_admin): ?>
                  <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#announcementEditModal" title="تعديل الإعلان">
                      <i class="bi bi-pencil-fill"></i>
                  </button>
              <?php endif; ?>

              <?php if (!empty($ad['link'])): ?>
                  <a href="<?php echo htmlspecialchars($ad['link'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo (($ad['open_new_tab'] ?? 0) == 1 ? 'target="_blank" rel="noopener noreferrer"' : ''); ?>>
              <?php endif; ?>
              
                <?php if (($ad['type'] ?? 'text') === 'text'): ?>
                  <?php if (!empty($ad['announcement_text'])): ?>
                    <div class="p-2 rounded shadow-sm announcement-ticker-container" style="background-color: <?php echo htmlspecialchars($ad['bg_color'] ?? '#f1f5f9', ENT_QUOTES, 'UTF-8'); ?>; color: <?php echo htmlspecialchars($ad['text_color'] ?? '#1e293b', ENT_QUOTES, 'UTF-8'); ?>; font-size: <?php echo htmlspecialchars((string)($ad['font_size'] ?? '16'), ENT_QUOTES, 'UTF-8'); ?>px;">
                      <div class="announcement-ticker-track">
                          <span><?php echo htmlspecialchars($ad['announcement_text'], ENT_QUOTES, 'UTF-8'); ?></span>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php else: ?>
                  <div class="rounded overflow-hidden shadow-sm" style="max-height: 65px;">
                    <img src="<?php echo htmlspecialchars(get_image_url($ad['image_path'] ?? 'assets/img/default-ad.png'), ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="object-fit: cover; max-height: 65px;" alt="Advertisement">
                  </div>
                <?php endif; ?>
                
              <?php if (!empty($ad['link'])): ?></a><?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- السوشيال ميديا -->
        <div class="d-none d-lg-flex align-items-center">
          <div class="editable-wrapper d-inline-block">
            <?php if ($is_admin): ?>
                <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#socialLinksEditModal" title="تعديل منصات التواصل">
                    <i class="bi bi-pencil-fill"></i>
                </button>
            <?php endif; ?>

            <div class="social-icons d-flex gap-3">
              <?php foreach ($social_links as $s): ?>
                  <a href="<?php echo htmlspecialchars($s['url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo htmlspecialchars(get_image_url($s['img'] ?? null), ENT_QUOTES, 'UTF-8'); ?>" width="28" alt="social">
                  </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- Main Header -->
    <nav id="main-header" class="navbar navbar-expand-lg py-3" aria-label="القائمة الرئيسية">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- اللوجو (Mobile Only) -->
        <div class="d-lg-none">
          <a class="navbar-brand" href="/<?php echo htmlspecialchars($current_lang_code, ENT_QUOTES, 'UTF-8'); ?>">
            <img src="<?php echo htmlspecialchars(get_image_url($site_logo_path, '/assets/img/logo.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="Logo" height="50">
          </a>
        </div>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse">
          <div class="editable-wrapper d-inline-block w-100">
            <?php if ($is_admin): ?>
                <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#menuEditModal" title="تعديل القائمة">
                    <i class="bi bi-pencil-fill"></i>
                </button>
            <?php endif; ?>

            <ul class="navbar-nav gap-3">
              <?php foreach ($menu_links as $link): 
                  $link_url = ltrim($link['url'] ?? '', '/');
                  $link_title = $getMenuTitle($link);
              ?>
                  <li class="nav-item">
                    <a class="nav-link" href="/<?php echo htmlspecialchars($current_lang_code . '/' . $link_url, ENT_QUOTES, 'UTF-8'); ?>">
                      <?php echo htmlspecialchars($link_title, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                  </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>

        <!-- Controls & Languages Switcher -->
        <div class="d-flex align-items-center gap-3">
          <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"><span class="navbar-toggler-icon"></span></button>
          
          <div class="dropdown">
            <div class="editable-wrapper d-inline-block">
                <?php if ($is_admin): ?>
                    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#langEditModal" title="تعديل اللغات">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                <?php endif; ?>
                
                <button class="btn lang-switch d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown">
                    <img src="<?php echo htmlspecialchars(get_image_url('assets/img/home/global.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="lang" width="20">
                    <span><?php echo htmlspecialchars($current_lang_name, ENT_QUOTES, 'UTF-8'); ?></span>
                    <img src="<?php echo htmlspecialchars(get_image_url('assets/img/home/arowwdown.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="arrow" width="15">
                </button>

                <!-- روابط تبديل اللغات القادمة من قاعدة البيانات -->
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php foreach ($active_languages as $lang): 
                        $code = $lang['code'] ?? 'de';
                        $name = $lang['name'] ?? 'Deutsch';
                    ?>
                        <li><a class="dropdown-item" href="/<?php echo htmlspecialchars($code . $clean_uri, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- Offcanvas (Mobile Menu) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">
          <img src="<?php echo htmlspecialchars(get_image_url($site_logo_path, '/assets/img/logo.png'), ENT_QUOTES, 'UTF-8'); ?>" height="50" alt="Logo">
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
            <?php foreach ($menu_links as $link): 
                $link_url = ltrim($link['url'] ?? '', '/');
                $link_title = $getMenuTitle($link);
            ?>
                <li class="nav-item">
                  <a class="nav-link" href="/<?php echo htmlspecialchars($current_lang_code . '/' . $link_url, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($link_title, ENT_QUOTES, 'UTF-8'); ?>
                  </a>
                </li>
            <?php endforeach; ?>
        </ul>
      </div>
    </div>
</header>
