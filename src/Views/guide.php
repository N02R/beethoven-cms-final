<?php
/**
 * صفحة الدليل الشامل - Guide Page View
 */
?>

<!-- ===== GUIDE PAGE START ===== -->
<section class="guide py-5 editable-wrapper" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الدليل الشامل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
        // معالجة عنوان الدليل متعدد اللغات
        $guide_title_raw = get_setting('guide_title', 'الدليل الشامل');
        if (is_string($guide_title_raw) && str_starts_with(trim($guide_title_raw), '{')) {
            $gt_arr = json_decode($guide_title_raw, true) ?? [];
            $guide_title = $gt_arr[$current_lang] ?? $gt_arr['de'] ?? $gt_arr['ar'] ?? 'الدليل الشامل';
        } else {
            $guide_title = $guide_title_raw;
        }

        // معالجة وصف الدليل متعدد اللغات
        $guide_desc_raw = get_setting('guide_desc', '');
        if (is_string($guide_desc_raw) && str_starts_with(trim($guide_desc_raw), '{')) {
            $gd_arr = json_decode($guide_desc_raw, true) ?? [];
            $guide_desc = $gd_arr[$current_lang] ?? $gd_arr['de'] ?? $gd_arr['ar'] ?? '';
        } else {
            $guide_desc = $guide_desc_raw;
        }

        // معالجة نص زر "قراءة المزيد"
        $rm_raw = get_setting('read_more_btn', 'قراءة المزيد');
        if (is_string($rm_raw) && str_starts_with(trim($rm_raw), '{')) {
            $rm_arr = json_decode($rm_raw, true) ?? [];
            $read_more_text = $rm_arr[$current_lang] ?? $rm_arr['de'] ?? $rm_arr['ar'] ?? 'قراءة المزيد';
        } else {
            $read_more_text = $rm_raw;
        }

        $guide_items_raw = get_setting('guide_items', []);
        $guide_items = is_string($guide_items_raw) ? (json_decode($guide_items_raw, true) ?? []) : $guide_items_raw;
    ?>

    <div class="text-center mb-5">
      <h2 class="sec-title mb-3"><?php echo htmlspecialchars($guide_title); ?></h2>
      <?php if (!empty($guide_desc)): ?>
        <p class="main-p mx-auto" style="max-width: 700px;">
          <?php echo nl2br(htmlspecialchars($guide_desc)); ?>
        </p>
      <?php endif; ?>
    </div>

    <div class="row g-4">
      <?php if (!empty($guide_items)): ?>
        <?php foreach ($guide_items as $item): 
            $item_title = $item[$current_lang]['title'] ?? $item['de']['title'] ?? $item['ar']['title'] ?? ($item['title'] ?? '');
            $item_desc  = $item[$current_lang]['desc'] ?? $item['de']['desc'] ?? $item['ar']['desc'] ?? ($item['desc'] ?? '');

            $item_img = get_image_url($item['img'] ?? null);
            $arrow_img = get_image_url('assets/img/ArrowLeft.svg.webp');
            $raw_url = $item['url'] ?? '#';
            $final_url = ($raw_url !== '#' && !str_starts_with($raw_url, 'http')) ? ($path_prefix ?? '') . ltrim($raw_url, '/') : $raw_url;
        ?>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
              <?php if (!empty($item_img)): ?>
                <div class="card-img-wrapper">
                  <img src="<?php echo htmlspecialchars($item_img); ?>" alt="<?php echo htmlspecialchars($item_title); ?>" class="card-img-top img-fluid">
                </div>
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($item_title); ?></h5>
                <p class="card-text flex-grow-1"><?php echo htmlspecialchars($item_desc); ?></p>
                <a href="<?php echo htmlspecialchars($final_url); ?>" class="btn btn-link text-decoration-none fw-bold p-0 mt-3 d-flex align-items-center gap-2">
                  <?php echo htmlspecialchars($read_more_text); ?>
                  <img src="<?php echo htmlspecialchars($arrow_img); ?>" alt="arrow" width="18" style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
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
