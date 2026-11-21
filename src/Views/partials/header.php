<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Header Partial (Gold Standard MVC, Secure & Multilingual)
 * يدعم تعدد اللغات بالكامل (لوجو، إعلانات نصية وصور، قوائم، لغات ديناميكية، وتواصل اجتماعي)
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

// 1. تحديد اللغة والاتجاه الحاليين من النظام
$supported_langs = ['de', 'en', 'ar'];
$current_lang = $current_lang ?? (function_exists('get_current_lang') ? get_current_lang() : ($_SESSION['site_lang'] ?? 'de'));
if (!in_array($current_lang, $supported_langs, true)) {
    $current_lang = 'de';
}
$current_dir = ($current_lang === 'ar') ? 'rtl' : 'ltr';
$current_lang_code = $current_lang;
$modal_dir = $current_dir;
$modal_align = ($current_dir === 'rtl') ? 'text-end' : 'text-start';

// 2. تنظيف المسار الحالي من أي بادئة لغة سابقة لمنع تكرارها مع الحفاظ على الاستعلام إن وجد
$request_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$path_segments = array_values(array_filter(explode('/', trim((string)$request_path, '/')), static fn($seg) => $seg !== ''));
if (!empty($path_segments[0]) && in_array(strtolower($path_segments[0]), $supported_langs, true)) {
    array_shift($path_segments);
}
$clean_uri = !empty($path_segments) ? '/' . implode('/', $path_segments) : '';
$query_str = isset($_SERVER['QUERY_STRING']) && trim((string)$_SERVER['QUERY_STRING']) !== ''
    ? '?' . ltrim((string)$_SERVER['QUERY_STRING'], '?')
    : '';

// 3. التحقق الآمن والصارم لصلاحيات المشرف بشكل مركزي في الهيدر
$is_admin = !empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true
            && isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'super_admin'], true);

// دالة مساعدة آمنة لفك ترميز JSON إن كان نصاً
$decodeSettingArray = static function (mixed $raw): array {
    if (is_array($raw)) {
        return $raw;
    }
    if (is_string($raw) && trim($raw) !== '') {
        $decoded = json_decode(trim($raw), true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }
    return [];
};

// 4. جلب ومعالجة اللوجو (صورة + نص متعدد اللغات)
$site_logo_raw = $data['site_logo_path'] ?? (function_exists('get_setting') ? get_setting('site_logo_path', 'assets/img/icon.png') : 'assets/img/icon.png');
$site_logo_icon = 'assets/img/icon.png';

$logoArr = $decodeSettingArray($site_logo_raw);
if (!empty($logoArr)) {
    $candidateLogo = (string)($logoArr[$current_lang] ?? ($logoArr['de'] ?? ($logoArr['en'] ?? ($logoArr['ar'] ?? 'assets/img/icon.png'))));
    if (trim($candidateLogo) !== '') {
        $site_logo_icon = trim($candidateLogo);
    }
} elseif (is_string($site_logo_raw) && trim($site_logo_raw) !== '') {
    $site_logo_icon = trim($site_logo_raw);
}

$site_logo_text_raw = $data['site_logo_text'] ?? (function_exists('get_setting') ? get_setting('site_logo_text', '') : '');
$activeLogoText = '';
$logoTextArr = $decodeSettingArray($site_logo_text_raw);
if (!empty($logoTextArr)) {
    $activeLogoText = trim((string)($logoTextArr[$current_lang] ?? ($logoTextArr['de'] ?? ($logoTextArr['en'] ?? ($logoTextArr['ar'] ?? '')))));
} elseif (is_string($site_logo_text_raw)) {
    $activeLogoText = trim($site_logo_text_raw);
}

if ($activeLogoText === '') {
    $activeLogoText = ($current_lang === 'ar')
        ? 'بيتهوفن سيتي للخدمات (BCS)'
        : 'Beethoven City Services (BCS)';
}

// 5. جلب اللغات المفعلة من قاعدة البيانات (دون الكتابة فوق مصفوفة $lang)
$languages_raw = $data['languages'] ?? (function_exists('get_setting') ? get_setting('languages', []) : []);
$available_languages = $decodeSettingArray($languages_raw);
if (empty($available_languages)) {
    $available_languages = [
        ['code' => 'de', 'name' => 'Deutsch', 'active' => true],
        ['code' => 'en', 'name' => 'English', 'active' => true],
        ['code' => 'ar', 'name' => 'العربية', 'active' => true],
    ];
}
$active_languages = array_values(array_filter($available_languages, static fn($item) => is_array($item) && !empty($item['active'])));

$current_lang_name = 'Deutsch';
foreach ($active_languages as $langItem) {
    if (($langItem['code'] ?? '') === $current_lang) {
        $current_lang_name = (string)($langItem['name'] ?? 'Deutsch');
        break;
    }
}

// 6. جلب القوائم ومنصات التواصل الاجتماعي
$menu_links = array_values($decodeSettingArray($data['menu_links'] ?? (function_exists('get_setting') ? get_setting('menu_links', []) : [])));
$social_links = array_values($decodeSettingArray($data['social_links'] ?? (function_exists('get_setting') ? get_setting('social_links', []) : [])));

// 7. استخراج وتأمين بيانات الإعلان (Announcement)
$rawAnnouncement = $data['announcement'] ?? (function_exists('get_setting') ? get_setting('announcement', []) : []);
$allAds = $decodeSettingArray($rawAnnouncement);
$ad = $allAds[$current_lang] ?? ($allAds['de'] ?? ($allAds['en'] ?? ($allAds['ar'] ?? (isset($allAds['status']) ? $allAds : []))));
if (!is_array($ad)) {
    $ad = [];
}

$adType     = (string)($ad['type'] ?? 'text');
$hasText    = ($adType === 'text') && trim((string)($ad['announcement_text'] ?? '')) !== '';
$hasImage   = ($adType === 'image') && !empty($ad['image_path']) && $ad['image_path'] !== 'assets/img/default-ad.png';
$hasContent = ($hasText || $hasImage);

$status       = (string)($ad['status'] ?? 'Draft');
$current_time = date('Y-m-d\TH:i');

$is_in_time = true;
if (!empty($ad['start_date']) && $current_time < (string)$ad['start_date']) {
    $is_in_time = false;
}
if (!empty($ad['end_date']) && $current_time > (string)$ad['end_date']) {
    $is_in_time = false;
}

$is_visible = (($status === 'Published' || $status === 'active') && $hasContent && $is_in_time);

$rawAdLink = trim((string)($ad['link'] ?? ''));
$safeAdLink = ($rawAdLink !== '' && !preg_match('/^(javascript|data|vbscript):/i', $rawAdLink)) ? $rawAdLink : '';

// دالة مساعدة لاستخراج عنوان القائمة
$getMenuTitle = static function (array $link) use ($current_lang): string {
    if (isset($link[$current_lang]['title']) && is_string($link[$current_lang]['title'])) {
        return $link[$current_lang]['title'];
    }
    if (isset($link['title'])) {
        if (is_array($link['title'])) {
            return (string)($link['title'][$current_lang] ?? ($link['title']['de'] ?? ($link['title']['en'] ?? ($link['title']['ar'] ?? ''))));
        }
        return (string)$link['title'];
    }
    return (string)($link['de']['title'] ?? ($link['en']['title'] ?? ($link['ar']['title'] ?? '')));
};

// دالة مساعدة لبناء رابط القائمة بلغة المستخدم الحالية بأمان
$buildLocalizedNavUrl = static function (mixed $rawUrl) use ($current_lang_code): string {
    $urlStr = trim(is_array($rawUrl) ? (string)reset($rawUrl) : (string)$rawUrl);
    if ($urlStr === '' || $urlStr === '#') {
        return '/' . $current_lang_code . '/home';
    }
    if (preg_match('#^(https?://|mailto:|tel:)#i', $urlStr)) {
        return preg_match('/^(javascript|data|vbscript):/i', $urlStr) ? '#' : $urlStr;
    }
    $cleanSlug = ltrim($urlStr, '/');
    $cleanSlug = preg_replace('#^public/#i', '', $cleanSlug);
    $cleanSlug = preg_replace('#^(ar|en|de)(/|$)#i', '', $cleanSlug);
    $cleanSlug = trim((string)$cleanSlug, '/');
    return '/' . $current_lang_code . '/' . ($cleanSlug !== '' ? $cleanSlug : 'home');
};

$csrf_token = $csrf_token ?? ($data['csrf_token'] ?? \App\Core\Security::generateCsrfToken());
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($current_lang_code, ENT_QUOTES, 'UTF-8'); ?>" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8'); ?>">

  <title><?php echo htmlspecialchars((string)($page_title ?? 'BCS || Beethoven City Services'), ENT_QUOTES, 'UTF-8'); ?></title>
  
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
          $cleanCss = ltrim((string)$css_file, '/');
          $fullCssPath = __DIR__ . '/../../../public/' . $cleanCss;
          $ver = is_file($fullCssPath) ? (string)filemtime($fullCssPath) : '1.0.0';
          echo '<link rel="stylesheet" href="/' . htmlspecialchars($cleanCss, ENT_QUOTES, 'UTF-8') . '?v=' . htmlspecialchars($ver, ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;
      }
  }
  ?>
</head>

<body>

<header>
    <!-- NavTop -->
    <nav class="nav-top navbar py-2">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- اللوجو الذكي: أيقونة + نص متعدد اللغات (CSS Stacking) -->
        <div class="navbar-brand-wrapper">
          <div class="editable-wrapper d-inline-block position-relative">
            <?php if ($is_admin): ?>
                <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#logoEditModal" title="<?php echo htmlspecialchars((string)($lang['manage_site_logo'] ?? 'Edit Logo'), ENT_QUOTES, 'UTF-8'); ?>">
                    <i class="bi bi-pencil-fill"></i>
                </button>
            <?php endif; ?>

            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/<?php echo htmlspecialchars($current_lang_code, ENT_QUOTES, 'UTF-8'); ?>/home">
              <img src="<?php echo htmlspecialchars(get_image_url($site_logo_icon, 'assets/img/icon.png'), ENT_QUOTES, 'UTF-8'); ?>" width="45" height="45" loading="lazy" alt="BCS Icon" style="object-fit: contain; flex-shrink: 0;">
              <span class="logo-text-stacked" style="display: inline-block; max-width: 130px; font-weight: 800; font-size: 0.95rem; line-height: 1.15; color: #0f172a; word-break: normal; white-space: normal;">
                  <?php echo htmlspecialchars($activeLogoText, ENT_QUOTES, 'UTF-8'); ?>
              </span>
            </a>
          </div>
        </div>

        <!-- منطقة الإعلان العلوي -->
        <div class="flex-grow-1 d-none d-lg-flex justify-content-center align-items-center px-4">
          <?php if ($is_visible || $is_admin): ?>
            <div class="editable-wrapper d-block w-100" style="max-width: 500px;">
              <?php if ($is_admin): ?>
                  <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#announcementEditModal" title="<?php echo htmlspecialchars((string)($lang['announcement_settings'] ?? 'Edit Announcement'), ENT_QUOTES, 'UTF-8'); ?>">
                      <i class="bi bi-pencil-fill"></i>
                  </button>
              <?php endif; ?>

              <?php if ($safeAdLink !== '' && $hasContent): ?>
                  <a href="<?php echo htmlspecialchars($safeAdLink, ENT_QUOTES, 'UTF-8'); ?>" <?php echo (!empty($ad['open_new_tab']) ? 'target="_blank" rel="noopener noreferrer"' : ''); ?> class="text-decoration-none">
              <?php endif; ?>
              
                <?php if ($adType === 'text'): ?>
                  <?php if ($hasText): ?>
                    <div class="p-2 rounded shadow-sm announcement-ticker-container" style="background-color: <?php echo htmlspecialchars((string)($ad['bg_color'] ?? '#f1f5f9'), ENT_QUOTES, 'UTF-8'); ?>; color: <?php echo htmlspecialchars((string)($ad['text_color'] ?? '#1e293b'), ENT_QUOTES, 'UTF-8'); ?>; font-size: <?php echo htmlspecialchars((string)($ad['font_size'] ?? '16'), ENT_QUOTES, 'UTF-8'); ?>px;">
                      <div class="announcement-ticker-track">
                          <span><?php echo htmlspecialchars((string)$ad['announcement_text'], ENT_QUOTES, 'UTF-8'); ?></span>
                      </div>
                    </div>
                  <?php elseif ($is_admin): ?>
                    <div class="p-2 rounded border border-dashed text-center text-muted small bg-light">
                        <i class="bi bi-megaphone"></i> <?php echo htmlspecialchars((string)($lang['announcement_settings'] ?? 'Announcement (Hidden / Empty)'), ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                  <?php endif; ?>
                <?php else: ?>
                  <div class="rounded overflow-hidden shadow-sm text-center" style="max-height: 65px;">
                    <img src="<?php echo htmlspecialchars(get_image_url($ad['image_path'] ?? 'assets/img/default-ad.png'), ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid" style="object-fit: cover; max-height: 65px;" alt="Advertisement">
                  </div>
                <?php endif; ?>
                
              <?php if ($safeAdLink !== '' && $hasContent): ?></a><?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- السوشيال ميديا + زر العودة للداشبورد للمشرف -->
        <div class="d-none d-lg-flex align-items-center gap-3">
          <?php if ($is_admin): ?>
            <a href="/admin/dashboard" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
          <?php endif; ?>

          <div class="editable-wrapper d-inline-block">
            <?php if ($is_admin): ?>
                <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#socialLinksEditModal" title="<?php echo htmlspecialchars((string)($lang['manage_social_platforms'] ?? 'Edit Social Links'), ENT_QUOTES, 'UTF-8'); ?>">
                    <i class="bi bi-pencil-fill"></i>
                </button>
            <?php endif; ?>

            <div class="social-icons d-flex gap-3">
              <?php foreach ($social_links as $s): 
                  if (!is_array($s)) continue;
                  $sUrl = trim((string)($s['url'] ?? '#'));
                  if (preg_match('/^(javascript|data|vbscript):/i', $sUrl)) {
                      $sUrl = '#';
                  }
              ?>
                  <a href="<?php echo htmlspecialchars($sUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo htmlspecialchars((string)($s['name'] ?? 'Social'), ENT_QUOTES, 'UTF-8'); ?>">
                    <img src="<?php echo htmlspecialchars(get_image_url($s['img'] ?? null), ENT_QUOTES, 'UTF-8'); ?>" width="28" alt="<?php echo htmlspecialchars((string)($s['name'] ?? 'social'), ENT_QUOTES, 'UTF-8'); ?>">
                  </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- Main Header -->
    <nav id="main-header" class="navbar navbar-expand-lg py-3" aria-label="Main Navigation">
      <div class="container-fluid custom-container d-flex align-items-center justify-content-between">
        
        <!-- اللوجو (Mobile Only) -->
        <div class="d-lg-none">
          <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/<?php echo htmlspecialchars($current_lang_code, ENT_QUOTES, 'UTF-8'); ?>/home">
            <img src="<?php echo htmlspecialchars(get_image_url($site_logo_icon, 'assets/img/icon.png'), ENT_QUOTES, 'UTF-8'); ?>" width="42" height="42" alt="BCS Icon" style="object-fit: contain; flex-shrink: 0;">
            <span class="logo-text-stacked" style="display: inline-block; max-width: 120px; font-weight: 800; font-size: 0.85rem; line-height: 1.15; color: #0f172a;">
                <?php echo htmlspecialchars($activeLogoText, ENT_QUOTES, 'UTF-8'); ?>
            </span>
          </a>
        </div>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse">
          <div class="editable-wrapper d-inline-block w-100">
            <?php if ($is_admin): ?>
                <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#menuEditModal" title="<?php echo htmlspecialchars((string)($lang['manage_main_menu'] ?? 'Edit Menu'), ENT_QUOTES, 'UTF-8'); ?>">
                    <i class="bi bi-pencil-fill"></i>
                </button>
            <?php endif; ?>

            <ul class="navbar-nav gap-3">
              <?php foreach ($menu_links as $link): 
                  if (!is_array($link)) continue;
                  $navHref = $buildLocalizedNavUrl($link['url'] ?? '');
                  $link_title = $getMenuTitle($link);
              ?>
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo htmlspecialchars($navHref, ENT_QUOTES, 'UTF-8'); ?>">
                      <?php echo htmlspecialchars($link_title, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                  </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>

        <!-- Controls & Languages Switcher -->
        <div class="d-flex align-items-center gap-3">
          <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          
          <div class="dropdown">
            <div class="editable-wrapper d-inline-block">
                <?php if ($is_admin): ?>
                    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#langEditModal" title="<?php echo htmlspecialchars((string)($lang['manage_languages'] ?? 'Edit Languages'), ENT_QUOTES, 'UTF-8'); ?>">
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
                    <?php foreach ($active_languages as $langOption): 
                        $code = (string)($langOption['code'] ?? 'de');
                        $name = (string)($langOption['name'] ?? 'Deutsch');
                        $switchHref = '/' . $code . $clean_uri . $query_str;
                    ?>
                        <li><a class="dropdown-item <?php echo ($code === $current_lang) ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($switchHref, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- Offcanvas (Mobile Menu) -->
    <div class="offcanvas <?php echo ($current_dir === 'rtl') ? 'offcanvas-start' : 'offcanvas-end'; ?>" tabindex="-1" id="offcanvasNavbar" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title m-0">
          <a class="d-inline-flex align-items-center gap-2 text-decoration-none" href="/<?php echo htmlspecialchars($current_lang_code, ENT_QUOTES, 'UTF-8'); ?>/home">
            <img src="<?php echo htmlspecialchars(get_image_url($site_logo_icon, 'assets/img/icon.png'), ENT_QUOTES, 'UTF-8'); ?>" width="42" height="42" alt="BCS Icon" style="object-fit: contain; flex-shrink: 0;">
            <span class="logo-text-stacked" style="display: inline-block; max-width: 140px; font-weight: 800; font-size: 0.9rem; line-height: 1.15; color: #0f172a;">
                <?php echo htmlspecialchars($activeLogoText, ENT_QUOTES, 'UTF-8'); ?>
            </span>
          </a>
        </h5>
        <button type="button" class="btn-close m-0" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
            <?php foreach ($menu_links as $link): 
                if (!is_array($link)) continue;
                $navHref = $buildLocalizedNavUrl($link['url'] ?? '');
                $link_title = $getMenuTitle($link);
            ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo htmlspecialchars($navHref, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($link_title, ENT_QUOTES, 'UTF-8'); ?>
                  </a>
                </li>
            <?php endforeach; ?>
            <?php if ($is_admin): ?>
                <li class="nav-item mt-3 pt-3 border-top">
                  <a class="nav-link text-primary fw-bold" href="/admin/dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                  </a>
                </li>
            <?php endif; ?>
        </ul>
      </div>
    </div>
</header>
