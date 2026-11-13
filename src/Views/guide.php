<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Guide Page View (Gold Standard MVC, Multilingual & Security Hardened)
 * متطابق بالكامل مع المعيار الهندسي المعتمد في صفحات النظام.
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

$supported_langs = ['de', 'en', 'ar'];
$current_lang = $current_lang ?? (function_exists('get_current_lang') ? get_current_lang() : ($_SESSION['site_lang'] ?? 'de'));
if (!in_array($current_lang, $supported_langs, true)) {
    $current_lang = 'de';
}
$current_dir = $current_dir ?? (($current_lang === 'ar') ? 'rtl' : 'ltr');

// نصوص احتياطية مترجمة للغات الثلاث
$i18nDefaults = [
    'de' => [
        'guide_title' => 'Beethoven Gesamtführer',
        'read_more'   => 'Mehr lesen',
        'no_articles' => 'Derzeit sind keine Artikel im Leitfaden verfügbar.',
        'edit_guide'  => 'Gesamtführer bearbeiten',
    ],
    'en' => [
        'guide_title' => 'Beethoven Comprehensive Guide',
        'read_more'   => 'Read More',
        'no_articles' => 'No articles are currently available in the guide.',
        'edit_guide'  => 'Edit Comprehensive Guide',
    ],
    'ar' => [
        'guide_title' => 'دليل بيتهوفن الشامل',
        'read_more'   => 'قراءة المزيد',
        'no_articles' => 'لا توجد مقالات مضافة في الدليل حالياً.',
        'edit_guide'  => 'تعديل الدليل الشامل',
    ],
];
$txt = $i18nDefaults[$current_lang];

$data = is_array($data ?? null) ? $data : [];
$currentSettings = is_array($currentSettings ?? null) ? $currentSettings : (is_array($settings ?? null) ? $settings : []);
$lang = is_array($lang ?? null) ? $lang : [];

// 1. استخراج بيانات قسم الدليل متعدد اللغات بأمان تام مع تراجع قياسي (de -> en -> ar)
$guide_raw = $data['guide'] ?? ($currentSettings['guide'] ?? get_setting('guide', []));
if (is_string($guide_raw)) {
    $decoded = json_decode($guide_raw, true);
    $guide_all = is_array($decoded) ? $decoded : [];
} else {
    $guide_all = is_array($guide_raw) ? $guide_raw : [];
}

if (isset($guide_all[$current_lang]) && is_array($guide_all[$current_lang])) {
    $lang_guide_data = $guide_all[$current_lang];
} elseif (isset($guide_all['de']) && is_array($guide_all['de'])) {
    $lang_guide_data = $guide_all['de'];
} elseif (isset($guide_all['en']) && is_array($guide_all['en'])) {
    $lang_guide_data = $guide_all['en'];
} elseif (isset($guide_all['ar']) && is_array($guide_all['ar'])) {
    $lang_guide_data = $guide_all['ar'];
} else {
    $lang_guide_data = $guide_all;
}

$guide_title    = $lang_guide_data['title'] ?? ($data['guide_title'] ?? $txt['guide_title']);
$guide_desc     = $lang_guide_data['desc'] ?? ($data['guide_desc'] ?? '');
$read_more_text = $lang_guide_data['read_more_btn'] ?? ($lang['read_more'] ?? $txt['read_more']);
$guide_items    = $lang_guide_data['items'] ?? ($data['guide_items'] ?? []);

if (!is_array($guide_items)) {
    $guide_items = [];
}

// 2. دالة تنقية الروابط وربطها ببادئة اللغة الحالية دون تكرار (منع XSS و Open Redirect)
$buildLocalizedUrl = static function (?string $rawUrl) use ($current_lang): string {
    $url = trim((string)($rawUrl ?? '#'));
    if ($url === '' || $url === '#') {
        return '#';
    }
    if (preg_match('/^(javascript|data|vbscript|file):/i', $url)) {
        return '#';
    }
    if (preg_match('/^(https?:\/\/|mailto:|tel:|#)/i', $url)) {
        return $url;
    }

    $cleanPath = preg_replace('/^(ar|en|de)\//i', '', ltrim($url, '/'));
    return '/' . $current_lang . '/' . $cleanPath;
};

$is_admin_active = !empty($is_admin) && $is_admin === true;
?>

<!-- ================= GUIDE PAGE START ================= -->
<section class="guide py-5 editable-wrapper" style="position: relative;" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
  <?php if ($is_admin_active): ?>
    <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideEditModal" title="<?php echo htmlspecialchars($txt['edit_guide'], ENT_QUOTES, 'UTF-8'); ?>">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="text-center mb-5">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$guide_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($guide_desc)): ?>
        <p class="main-p mx-auto" style="max-width: 700px;">
          <?php echo nl2br(htmlspecialchars((string)$guide_desc, ENT_QUOTES, 'UTF-8')); ?>
        </p>
      <?php endif; ?>
    </div>

    <div class="row g-4">
      <?php if (!empty($guide_items)): ?>
        <?php foreach ($guide_items as $item): 
            if (!is_array($item)) continue;
            
            $item_title = (string)($item['title'] ?? '');
            $item_desc  = (string)($item['desc'] ?? '');
            $item_img   = get_image_url($item['img'] ?? null, '');
            $arrow_img  = get_image_url('assets/img/ArrowLeft.svg.webp');
            $final_url  = $buildLocalizedUrl($item['url'] ?? ($item['link'] ?? '#'));
        ?>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card h-100">
              <div class="card-body d-flex flex-column">
                <?php if ($item_img !== ''): ?>
                  <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid guide-img">
                <?php endif; ?>
                <h5 class="card-title fw-bold mt-3"><?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class="card-text flex-grow-1"><?php echo htmlspecialchars($item_desc, ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="<?php echo htmlspecialchars($final_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn fw-bold mt-auto d-flex align-items-center gap-2">
                  <?php echo htmlspecialchars((string)$read_more_text, ENT_QUOTES, 'UTF-8'); ?>
                  <img src="<?php echo htmlspecialchars($arrow_img, ENT_QUOTES, 'UTF-8'); ?>" 
                       alt="arrow" 
                       class="<?php echo ($current_dir === 'ltr' ? 'ms-2' : 'me-2'); ?>" 
                       width="18" 
                       style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p><?php echo htmlspecialchars((string)($lang['no_guide_articles'] ?? $txt['no_articles']), ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ================= GUIDE PAGE END ================= -->

<?php
// تضمين موديل تعديل الدليل الشامل للمشرف فقط إن لم يكن مضمّناً مسبقاً
$guide_modals_file = __DIR__ . '/admin/admin_guide_modals.php';
if ($is_admin_active && is_file($guide_modals_file)) {
    include_once $guide_modals_file;
}
?>
