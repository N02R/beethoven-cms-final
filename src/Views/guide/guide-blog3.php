<!-- Breadcrumb start-->
<?php
    $from = $_GET['from'] ?? 'education';
    
    if ($from === 'job') {
        $parent_url = ($path_prefix ?? '') . 'job';
        $parent_name = 'التدريب المهني';
    } else {
        $parent_url = ($path_prefix ?? '') . 'education';
        $parent_name = 'التعليم العالي';
    }
?>
<div class="custom-container pt-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBreadcrumbModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb justify-content-start">
      <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($path_prefix ?? '../'); ?>">الرئيسية</a></li>
      <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($parent_url); ?>"><?php echo htmlspecialchars($parent_name); ?></a></li>
      <li class="breadcrumb-item" aria-current="page">
        <a href="<?php echo htmlspecialchars($guide_data['page_breadcrumb_url'] ?? '#'); ?>">
          <?php echo htmlspecialchars($guide_data['page_breadcrumb'] ?? 'معالجة طلبات العملاء في بيتهوفن سيتي للخدمات (BCS)'); ?>
        </a>
      </li>
    </ol>
  </nav>
</div>
<!-- Breadcrumb end-->

<!-- custom-guide start-->
<section class="custom-services custom-guide py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>
  <div class="custom-container">
    <?php 
        // معالجة مسار الصورة وإضافة بصمة زمنية لمنع التخزين المؤقت (Cache Buster)
        $raw_hero_img = $guide_data['hero_img'] ?? 'assets/img/guide/image.jpg';
        $hero_resolved_url = function_exists('get_image_url') ? get_image_url($raw_hero_img, '../assets/img/guide/image.jpg') : $raw_hero_img;
        if (!preg_match('/^http/', $hero_resolved_url) && $hero_resolved_url[0] !== '/') {
            $hero_resolved_url = '/' . ltrim($hero_resolved_url, './');
        }
        $hero_resolved_url .= '?v=' . time();
    ?>
    <div class="custom-hero" style="background-image: url('<?php echo htmlspecialchars($hero_resolved_url, ENT_QUOTES, 'UTF-8'); ?>');">
    </div>
  </div>
</section>
<!-- custom-services end-->

<!-- custom-services-info start -->
<section class="custom-services-info py-5">
  <div class="custom-container">
    
    <!-- العنوان والوصف -->
    <div class="head-info" style="position: relative;">
      <?php if (!empty($is_admin)): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>
      <h2 class="main-text"><?php echo htmlspecialchars($guide_data['main_title'] ?? 'معالجة طلبات العملاء في بيتهوفن سيتي للخدمات (BCS)'); ?></h2>
      <?php if (!empty($guide_data['main_desc'])): ?>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($guide_data['main_desc'])); ?></p>
      <?php endif; ?>
    </div>

    <!-- ميزات معالجة الطلبات (ملاحظات/قائمة النجوم) -->
    <div class="advice-stars my-5" style="position: relative;">
      <?php if (!empty($is_admin)): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل">
            <i class="bi bi-pencil-fill"></i>
        </button>
      <?php endif; ?>
      <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($guide_data['notes_title'] ?? 'الذي يجعل معالجة طلبات العملاء لدينا مختلفة'); ?></h5>
      <ul class="star-list">
        <?php foreach (($guide_data['notes_items'] ?? []) as $note): ?>
          <li>
            <p>
              <img src="<?php echo htmlspecialchars($path_prefix ?? '../'); ?>assets/img/education/starList.svg" alt="" class="ms-2" />
              <span class="fw-bold"><?php echo htmlspecialchars($note['title'] ?? ''); ?></span>
              <?php echo htmlspecialchars($note['text'] ?? ''); ?>
            </p>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>
</section>
<!-- custom-services-info end -->

<!-- time line start -->
<section class="timeline-section py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideTimelineModal" style="position: absolute; top: 20px; right: 20px; z-index: 10;" title="تعديل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>
  <div class="custom-container">
    <div class="mb-5">
      <h2 class="sec-title"><?php echo htmlspecialchars($guide_data['timeline_title'] ?? 'مراحل معالجة طلبك لدينا'); ?></h2>
      <p class="main-p"><?php echo htmlspecialchars($guide_data['timeline_desc'] ?? 'في بيتهوفن سيتي للخدمات، نقسم معالجة طلبك إلى خطوات واضحة:'); ?></p>
    </div>

    <div class="mobile-timeline">
      <?php 
        $steps = $guide_data['timeline_steps'] ?? [];
        foreach ($steps as $index => $step): 
            $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
      ?>
        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num"><?php echo $num; ?></span>
          </div>
          <div class="m-content">
            <h4><?php echo htmlspecialchars($step['subtitle'] ?? $step['title'] ?? ''); ?></h4>
            <p><?php echo htmlspecialchars($step['desc'] ?? ''); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<!-- time line end -->

<!-- Contact Guide Section (إذا أردت جعلها ديناميكية مستقبلاً أو إبقائها ثابتة) -->
<section class="contact-guide py-5">
  <div class="custom-container">
    <div class="head-info">
      <h2 class="main-text">جاهز لبدء رحلتك؟</h2>
      <p class="par-text">
        احجز استشارتك المجانية الآن مع فريق BCS وابدأ خطواتك نحو ألمانيا بثقة: <span style="color: #66aaee;" class="fw-bold">تواصل معنا</span>
      </p>
    </div>
  </div>
</section>

<?php 
    // استدعاء مودلز لوحة التحكم الخاصة بالمقال الثالث للمشرفين
    $guide_modals_file = __DIR__ . '/includes/admin_guide_blog3_modals.php';
    if (!empty($is_admin) && file_exists($guide_modals_file)) { 
        include_once $guide_modals_file; 
    }
?>
