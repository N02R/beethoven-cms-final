<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Home View (Production-Ready & Secure)
 * تم الحفاظ على التصميم بالكامل مع إعادة هندسة المنطق، منع التكرار، وتأمين البيانات.
 */

$current_lang = $current_lang ?? get_current_lang();
$current_dir = $current_dir ?? (($current_lang === 'ar') ? 'rtl' : 'ltr');
$is_admin = $is_admin ?? false;

// دالة مساعدة داخلية موحدة لاستخراج بيانات القسم متعدد اللغات بسلامة تامة (DRY Principle)
$getLocalizedData = function(string $settingKey, mixed $default = []): mixed {
    global $current_lang;
    $raw = get_setting($settingKey, $default);
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $data = is_array($decoded) ? $decoded : $default;
    } else {
        $data = is_array($raw) ? $raw : $default;
    }

    // إرجاع بيانات اللغة الحالية أو بدائل آمنة
    return $data[$current_lang] ?? ($data['de'] ?? ($data['ar'] ?? ($data['en'] ?? $data)));
};
?>

<!-- بداية قسم الهيرو -->
<?php
  $hero_exists = isset($data['hero']);
  echo "<!-- هل بيانات الهيرو موجودة؟ " . ($hero_exists ? 'نعم' : 'لا') . " -->";
?>
<!-- hero start -->
<section class="hero py-5 editable-wrapper" aria-label="قسم البداية" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#heroEditModal" title="تعديل الهيرو">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $hero = $getLocalizedData('hero', []);
    
    // استخراج محتوى الهيرو بطريقة نظيفة ومؤمنة
    $hero_title  = $hero['title'] ?? ($hero['hero_title'] ?? 'عنوان افتراضي');
    $hero_desc   = $hero['desc'] ?? ($hero['hero_desc'] ?? 'وصف افتراضي للقسم');
    $hero_btn    = $hero['btn_text'] ?? 'اضغط هنا';
    $hero_url    = $hero['btn_url'] ?? '#';
    $bg_img_path = $hero['img'] ?? null;
    
    $hero_bg = get_image_url($bg_img_path, '/assets/img/home/home1.png');
    ?>
    
    <div class="hero-container" style="background: url('<?php echo htmlspecialchars($hero_bg, ENT_QUOTES, 'UTF-8'); ?>') center/cover no-repeat;">
      <div class="hero-content">
        <h1><?php echo htmlspecialchars($hero_title, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars($hero_desc, ENT_QUOTES, 'UTF-8'); ?></p>
        <a href="<?php echo htmlspecialchars($hero_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-lg hero-btn">
          <?php echo htmlspecialchars($hero_btn, ENT_QUOTES, 'UTF-8'); ?>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- hero end -->

<!-- services start -->
<section class="services py-5 editable-wrapper" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#servicesEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخدمات">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php
    $s_data = $getLocalizedData('services', []);
    
    $sec_title = $s_data['section_title'] ?? ($s_data['title'] ?? 'خدماتنا المميزة');
    $sec_desc  = $s_data['section_subtitle'] ?? ($s_data['desc'] ?? '');
    $services  = $s_data['items'] ?? [];
    ?>

    <h2 class="mb-3 sec-title">
        <?php echo htmlspecialchars($sec_title, ENT_QUOTES, 'UTF-8'); ?>
    </h2>
    
    <?php if (!empty($sec_desc)): ?>
        <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars($sec_desc, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <div class="row g-4">
      <?php if (!empty($services) && is_array($services)): ?>
        <?php foreach ($services as $service): 
          $service_img   = get_image_url($service['img'] ?? null, '/assets/img/home/default.jpg');
          $service_title = $service['title'] ?? 'عنوان الخدمة';
          $service_url   = $service['url'] ?? '#';
        ?>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <a href="<?php echo htmlspecialchars($service_url, ENT_QUOTES, 'UTF-8'); ?>" class="card-link text-decoration-none d-block">
              <div class="card" style="background: url('<?php echo htmlspecialchars($service_img, ENT_QUOTES, 'UTF-8'); ?>') no-repeat center/cover;">
                <div class="card-info">
                  <h3><?php echo htmlspecialchars($service_title, ENT_QUOTES, 'UTF-8'); ?></h3>
                  <img src="<?php echo htmlspecialchars(get_image_url('assets/img/home/ArrowLink.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" 
                       alt="Arrow" 
                       style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted py-4">
          <p>لا توجد خدمات مضافة حالياً.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- services end -->

<!-- choose start -->
<section class="choose py-5 editable-wrapper" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#chooseEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل المميزات">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="container-fluid custom-container choose-container">
    <?php 
    $choose_section = $data['choose_section'] ?? [];
    if (empty($choose_section)) {
        $choose_section = $getLocalizedData('choose_items', []);
    }
    
    $choose_title = $choose_section['title'] ?? ($choose_section['choose_title'] ?? '');
    $choose_desc  = $choose_section['desc'] ?? ($choose_section['choose_section_desc'] ?? '');
    $choose_items = $choose_section['items'] ?? (is_array($choose_section) && !isset($choose_section['title']) ? $choose_section : []);
    ?>

    <?php if (!empty($choose_title)): ?>
      <h2 class="mb-5 sec-title"><?php echo htmlspecialchars($choose_title, ENT_QUOTES, 'UTF-8'); ?></h2>
    <?php endif; ?>
    
    <?php if (!empty($choose_desc)): ?>
        <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars($choose_desc, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($choose_items) && is_array($choose_items)): ?>
      <div class="row g-3">
        <?php 
        foreach ($choose_items as $item): 
          $raw_img    = !empty($item['img']) ? $item['img'] : 'assets/img/default-icon.png';
          $item_img   = get_image_url($raw_img);
          $item_title = $item['title'] ?? '';
          $item_desc  = $item['desc'] ?? '';
          $item_url   = $item['url'] ?? '#';
        ?>
          <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card choose-card">
              <div class="card-body">
                <a href="<?php echo htmlspecialchars($item_url, ENT_QUOTES, 'UTF-8'); ?>">
                  <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?>">
                </a>
                <h5 class="card-title"><?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($item_desc, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<!-- choose end -->

<!-- review start -->
<section class="reviews py-5 editable-wrapper" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#reviewsEditModal" title="تعديل التقييمات" style="position: absolute; top: 10px; right: 20px; z-index: 10;">
            <i class="bi bi-pencil-fill"></i>
        </button>
    <?php endif; ?>

    <div class="reviews-bg">
        <div class="custom-container">
            <?php 
            $reviews_title = $getLocalizedData('reviews_title', 'شاهد ماذا يقول عملاؤنا عنا');
            if (is_array($reviews_title)) {
                $reviews_title = $reviews_title['title'] ?? 'شاهد ماذا يقول عملاؤنا عنا';
            }
            ?>
            <h2 class="py-5 text-center sec-title">
                <?php echo htmlspecialchars((string)$reviews_title, ENT_QUOTES, 'UTF-8'); ?>
            </h2>
        </div>
    </div>

    <div class="custom-container">
        <div class="reviews-carousel-wrapper">
            <div id="carousel-reviews" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="carousel-inner">
                    <?php 
                    $reviews_items_raw = get_setting('reviews_items', []);
                    $reviews_items = is_string($reviews_items_raw) ? (json_decode($reviews_items_raw, true) ?? []) : (is_array($reviews_items_raw) ? $reviews_items_raw : []);

                    if (!empty($reviews_items) && is_array($reviews_items)): 
                        foreach ($reviews_items as $index => $item): 
                    ?>
                            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                                <div class="video-wrapper">
                                    <div class="video-container">
                                        <iframe src="<?php echo htmlspecialchars($item['url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" allowfullscreen title="Review Video"></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">لا توجد فيديوهات للعرض حالياً.</p>
                    <?php endif; ?>
                </div>

                <div class="dots mt-4">
                    <?php if (!empty($reviews_items) && is_array($reviews_items)): ?>
                        <?php foreach ($reviews_items as $index => $item): ?>
                            <span class="dot <?php echo ($index === 0) ? 'active' : ''; ?>" 
                                  data-bs-target="#carousel-reviews" 
                                  data-bs-slide-to="<?php echo $index; ?>">
                            </span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
</section>
<!-- review end -->

<!-- ===== GUIDE HOME SECTION START ===== -->
<section class="guide py-2 editable-wrapper" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الدليل الشامل">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
        $guide_title    = $getLocalizedData('guide_title', 'دليل بيتهوفن الشامل');
        $guide_desc     = $getLocalizedData('guide_desc', '');
        $read_more_text = $getLocalizedData('read_more_btn', 'قراءة المزيد');

        if (is_array($guide_title)) $guide_title = $guide_title['title'] ?? 'دليل بيتهوفن الشامل';
        if (is_array($guide_desc)) $guide_desc = $guide_desc['desc'] ?? '';
        if (is_array($read_more_text)) $read_more_text = $read_more_text['title'] ?? 'قراءة المزيد';

        $guide_items_raw = get_setting('guide_items', []);
        $guide_items = is_string($guide_items_raw) ? (json_decode($guide_items_raw, true) ?? []) : (is_array($guide_items_raw) ? $guide_items_raw : []);
    ?>
    <h2 class="mb-2 pt-5 sec-title"><?php echo htmlspecialchars((string)$guide_title, ENT_QUOTES, 'UTF-8'); ?></h2>
    <?php if (!empty($guide_desc)): ?>
        <p class="main-p"><?php echo nl2br(htmlspecialchars((string)$guide_desc, ENT_QUOTES, 'UTF-8')); ?></p>
    <?php endif; ?>

    <div id="carousel-guide" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
      <div class="carousel-inner">
        <?php if (!empty($guide_items) && is_array($guide_items)): ?>
          <?php $chunks = array_chunk($guide_items, 3); ?>
          <?php foreach ($chunks as $index => $slide_items): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
              <div class="row g-4">
                <?php foreach ($slide_items as $item): 
                  // جلب محتوى المقال حسب اللغة مع بدائل
                  $item_data  = isset($item[$current_lang]) ? $item[$current_lang] : ($item['ar'] ?? ($item['de'] ?? $item));
                  $item_title = $item_data['title'] ?? ($item['title'] ?? '');
                  $item_desc  = $item_data['desc'] ?? ($item['desc'] ?? '');
                  
                  $item_img  = get_image_url($item['img'] ?? null);
                  $raw_url   = $item['url'] ?? '#';
                  $final_url = ($raw_url !== '#' && !str_starts_with($raw_url, 'http')) ? '/' . $current_lang . '/' . ltrim($raw_url, '/') : $raw_url;
                ?>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 border-0 shadow-sm">
                      <?php if (!empty($item_img)): ?>
                        <div class="card-img-wrapper">
                          <img src="<?php echo htmlspecialchars($item_img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid guide-img">
                        </div>
                      <?php endif; ?>
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mt-3"><?php echo htmlspecialchars($item_title, ENT_QUOTES, 'UTF-8'); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo htmlspecialchars($item_desc, ENT_QUOTES, 'UTF-8'); ?></p>
                        <a href="<?php echo htmlspecialchars($final_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn fw-bold mt-auto d-flex align-items-center gap-2">
                          <?php echo htmlspecialchars((string)$read_more_text, ENT_QUOTES, 'UTF-8'); ?>
                          <img src="<?php echo htmlspecialchars(get_image_url('assets/img/ArrowLeft.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="arrow" class="mx-2" width="18" style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
                        </a>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="carousel-item active">
            <div class="col-12 text-center text-muted py-4">
              <p>لا توجد مقالات مضافة في الدليل حالياً.</p>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <?php if (!empty($chunks)): ?>
        <div class="dots mt-4">
          <?php foreach ($chunks as $index => $slide): ?>
            <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" data-bs-target="#carousel-guide" data-bs-slide-to="<?php echo $index; ?>"></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ===== GUIDE HOME SECTION END ===== -->

<!-- FAQ section start -->
<section class="popular py-5 editable-wrapper" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#faqEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الأسئلة الشائعة"><i class="bi bi-pencil-fill"></i></button>
  <?php endif; ?>

  <div class="container-fluid custom-container">
    <?php 
        $faq_title = $getLocalizedData('faq_title', 'الأسئلة الشائعة');
        if (is_array($faq_title)) $faq_title = $faq_title['title'] ?? 'الأسئلة الشائعة';

        $faq_items_raw = get_setting('faq_items', []);
        $faq_items = is_string($faq_items_raw) ? (json_decode($faq_items_raw, true) ?? []) : (is_array($faq_items_raw) ? $faq_items_raw : []);

        if (empty($faq_items)) {
            $faq_items = [
                [
                    'ar' => ['question' => 'ما هي المتطلبات الأساسية للتقديم على الجامعات الألمانية؟', 'answer' => 'تحتاج إلى شهادة الثانوية العامة أو ما يعادلها، إثبات كفاءة في اللغة الألمانية أو الإنجليزية.'],
                    'en' => ['question' => 'What are the basic requirements to apply to German universities?', 'answer' => 'You need a high school diploma or equivalent, proof of proficiency in German or English.'],
                    'de' => ['question' => 'Was sind die Grundvoraussetzungen für die Bewerbung?', 'answer' => 'Sie benötigen ein Abitur oder gleichwertigen Abschluss, Sprachnachweise.']
                ]
            ];
        }
    ?>

    <h2 class="sec-title mb-5"><?php echo htmlspecialchars((string)$faq_title, ENT_QUOTES, 'UTF-8'); ?></h2>
    
    <div class="accordion mb-5" id="accordionExample">
      <?php foreach ($faq_items as $index => $item): 
            $faq_question = '';
            $faq_answer = '';

            if (is_array($item)) {
                $q_data = isset($item[$current_lang]) ? $item[$current_lang] : ($item['ar'] ?? ($item['de'] ?? $item));
                $faq_question = $q_data['question'] ?? ($item['question'] ?? '');
                $faq_answer   = $q_data['answer'] ?? ($item['answer'] ?? '');
            }
      ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading<?php echo $index; ?>">
            <button class="accordion-button <?php echo ($index !== 0) ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="<?php echo ($index === 0) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $index; ?>">
              <?php echo htmlspecialchars($faq_question, ENT_QUOTES, 'UTF-8'); ?>
            </button>
          </h2>
          <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo ($index === 0) ? 'show' : ''; ?>" data-bs-parent="#accordionExample">
            <div class="accordion-body"><?php echo htmlspecialchars($faq_answer, ENT_QUOTES, 'UTF-8'); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!-- FAQ section end -->
