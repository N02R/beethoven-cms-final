<?php
/**
 * صفحة الدليل الشامل - Guide Page View
 * متوافقة مع الهيكل المدمج الحديث
 */

$current_lang = $current_lang ?? get_current_lang();
$current_dir  = $current_dir ?? (($current_lang === 'ar') ? 'rtl' : 'ltr');

$guide_title    = $guide_title ?? 'الدليل الشامل';
$guide_desc     = $guide_desc ?? '';
$read_more_text = $read_more_btn ?? 'قراءة المزيد';
$guide_items    = $guide_items ?? [];
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
        <?php foreach ($guide_items as $item): 
            if (!is_array($item)) continue;
            
            // استخراج الترجمة الخاصة باللغة الحالية، مع الاحتياط للعربية ثم الألمانية
            $langData   = $item[$current_lang] ?? ($item['ar'] ?? ($item['de'] ?? []));
            $item_title = $langData['title'] ?? '';
            $item_desc  = $langData['desc'] ?? '';

            $item_img  = get_image_url($item['img'] ?? null);
            $arrow_img = get_image_url('assets/img/ArrowLeft.svg.webp');
            $raw_url   = $item['url'] ?? '#';
            $final_url = ($raw_url !== '#' && !str_starts_with($raw_url, 'http')) ? '/' . $current_lang . '/' . ltrim($raw_url, '/') : $raw_url;
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
          <p>لا توجد مقالات مضافة في الدليل حالياً.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ===== GUIDE PAGE END ===== -->

