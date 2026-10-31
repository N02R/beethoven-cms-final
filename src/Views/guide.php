<?php
declare(strict_types=1);

/**
 * صفحة الدليل الشامل - Guide Page View
 * العرض الديناميكي المباشر المستمد من الـ SiteModel / HomeModel
 */

$current_lang = $current_lang ?? get_current_lang();
$current_dir  = $current_dir ?? (($current_lang === 'ar') ? 'rtl' : 'ltr');

// 1. جلب البيانات من المودل أو الإعدادات العامة
$guide_raw = $currentSettings['guide'] ?? ($data['guide'] ?? null);

// 🌟 [حماية أمان إضافية]: إن لم تكن موجودة في المتغيرات، نقوم بجلبها مباشرة من قاعدة البيانات كضمان هندسي مطلق
if (empty($guide_raw)) {
    try {
        $pdoConn = \App\Config\Database::getConnection();
        $stmtGuide = $pdoConn->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'guide'");
        $stmtGuide->execute();
        $guide_raw = $stmtGuide->fetchColumn() ?: '{}';
    } catch (\Exception $e) {
        $guide_raw = '{}';
    }
}

$guide_all = is_string($guide_raw) ? (json_decode($guide_raw, true) ?? []) : (is_array($guide_raw) ? $guide_raw : []);

// 2. استخراج بيانات اللغة الحالية بدقة (ar, en, de) مع التراجع الآمن للعربية
$lang_guide_data = $guide_all[$current_lang] ?? ($guide_all['ar'] ?? []);

$guide_title    = $lang_guide_data['title'] ?? ($data['guide_title'] ?? ('دليل بيتهوفن الشامل'));
$guide_desc     = $lang_guide_data['desc'] ?? ($data['guide_desc'] ?? '');

// 🌟 جلب نص زر "قراءة المزيد" المخزن في قاعدة البيانات لكل لغة بدقة تامة
$read_more_text = $lang_guide_data['read_more_btn'] ?? ($lang['read_more'] ?? 'قراءة المزيد');

$guide_items    = $lang_guide_data['items'] ?? ($data['guide_items'] ?? []);

if (!is_array($guide_items)) {
    $guide_items = [];
}
?>

<!-- ===== GUIDE PAGE START ===== -->
<section class="guide py-5 editable-wrapper" style="position: relative;">
  <?php if (!empty($is_admin) && $is_admin === true): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideEditModal" title="تعديل الدليل الشامل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container pt-3">
    <div class="text-center mb-5">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars((string)$guide_title, ENT_QUOTES, 'UTF-8'); ?></h2>
      <?php if (!empty($guide_desc)): ?>
        <p class="main-p mx-auto" style="max-width: 700px;">
          <?php echo nl2br(htmlspecialchars((string)$guide_desc, ENT_QUOTES, 'UTF-8')); ?>
        </p>
      <?php endif; ?>
    </div>

    <div class="row g-4">
      <?php if (!empty($guide_items) && is_array($guide_items)): ?>
        <?php foreach ($guide_items as $index => $item): 
            if (!is_array($item)) continue;
            
            $item_title = $item['title'] ?? '';
            $item_desc  = $item['desc'] ?? '';
            $item_img   = get_image_url($item['img'] ?? null);
            $arrow_img  = get_image_url('assets/img/ArrowLeft.svg.webp');
            $raw_url    = $item['url'] ?? ($item['link'] ?? '#');
            $final_url  = ($raw_url !== '#' && !str_starts_with($raw_url, 'http')) ? '/' . $current_lang . '/' . ltrim($raw_url, '/') : $raw_url;
        ?>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
              <?php if (!empty($item_img)): ?>
                <div class="card-img-wrapper">
                  <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top img-fluid">
                </div>
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class="card-text flex-grow-1"><?php echo htmlspecialchars($item_desc, ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="<?php echo htmlspecialchars($final_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-link text-decoration-none fw-bold p-0 mt-3 d-flex align-items-center gap-2">
                  <?php echo htmlspecialchars((string)$read_more_text, ENT_QUOTES, 'UTF-8'); ?>
                  <img src="<?php echo htmlspecialchars($arrow_img, ENT_QUOTES, 'UTF-8'); ?>" alt="arrow" width="18" style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p><?php echo $lang['no_guide_articles'] ?? 'لا توجد مقالات مضافة في الدليل حالياً.'; ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ===== GUIDE PAGE END ===== -->
