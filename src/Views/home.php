<?php
/**
 * صفحة الرئيسية - Home View
 * تم التحقق من مطابقة المتطلبات وقاعدة البيانات وتطبيق التحسينات اللازمة.
 */
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
    $hero_raw = get_setting('hero', []);
    
    // فك الـ JSON بطريقة آمنة
    if (is_string($hero_raw)) {
        $hero = json_decode($hero_raw, true) ?? [];
    } else {
        $hero = is_array($hero_raw) ? $hero_raw : [];
    }
    
    // استخراج المحتوى بطريقة ذكية تدعم متعدد اللغات والبيانات القديمة
    $hero_title = '';
    $hero_desc  = '';
    $hero_btn   = '';
    $hero_url   = '#';
    $bg_img_path = null;

    // 1. التحقق إذا كانت البيانات مخزنة بالهيكلة الجديدة للغات (ar, en, de)
    if (isset($hero[$current_lang]) && is_array($hero[$current_lang])) {
        $curr_data  = $hero[$current_lang];
        $hero_title = $curr_data['title'] ?? '';
        $hero_desc  = $curr_data['desc'] ?? '';
        $hero_btn   = $curr_data['btn_text'] ?? '';
        $hero_url   = $curr_data['btn_url'] ?? '#';
        $bg_img_path = $curr_data['img'] ?? null;
    } 
    // 2. بدائل احتياطية في حال لم تتوفر اللغة الحالية (تجربة الألمانية ثم العربية)
    elseif (isset($hero['de']) && is_array($hero['de'])) {
        $curr_data  = $hero['de'];
        $hero_title = $curr_data['title'] ?? '';
        $hero_desc  = $curr_data['desc'] ?? '';
        $hero_btn   = $curr_data['btn_text'] ?? '';
        $hero_url   = $curr_data['btn_url'] ?? '#';
        $bg_img_path = $curr_data['img'] ?? null;
    } 
    elseif (isset($hero['ar']) && is_array($hero['ar'])) {
        $curr_data  = $hero['ar'];
        $hero_title = $curr_data['title'] ?? '';
        $hero_desc  = $curr_data['desc'] ?? '';
        $hero_btn   = $curr_data['btn_text'] ?? '';
        $hero_url   = $curr_data['btn_url'] ?? '#';
        $bg_img_path = $curr_data['img'] ?? null;
    }

    // 3. إذا كانت البيانات مخزنة بالشكل القديم المسطح (مباشرة بدون مفاتيح لغات)
    if (empty($hero_title) && isset($hero['title'])) {
        $hero_title = $hero['title'];
        $hero_desc  = $hero['desc'] ?? '';
        $hero_btn   = $hero['btn_text'] ?? '';
        $hero_url   = $hero['btn_url'] ?? '#';
        $bg_img_path = $hero['img'] ?? null;
    }

    // القيم الافتراضية النهائية في حال كانت القاعدة فارغة تماماً
    $hero_title = !empty($hero_title) ? $hero_title : 'عنوان افتراضي';
    $hero_desc  = !empty($hero_desc) ? $hero_desc : 'وصف افتراضي للقسم';
    $hero_btn   = !empty($hero_btn) ? $hero_btn : 'اضغط هنا';
    
    // جلب رابط الصورة النهائي
    $hero_bg = get_image_url($bg_img_path ?? $hero['img'] ?? null, '/assets/img/home/home1.png');
    ?>
    
    <div class="hero-container" style="background: url('<?php echo htmlspecialchars($hero_bg); ?>') center/cover no-repeat;">
      <div class="hero-content">
        <h1><?php echo htmlspecialchars($hero_title); ?></h1>
        <p><?php echo htmlspecialchars($hero_desc); ?></p>
        <a href="<?php echo htmlspecialchars($hero_url); ?>" class="btn btn-lg hero-btn">
          <?php echo htmlspecialchars($hero_btn); ?>
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
    // جلب ومعالجة عنوان القسم
    $sec_title_raw = get_setting('services_section_title', 'خدماتنا المميزة');
    if (is_string($sec_title_raw) && str_starts_with(trim($sec_title_raw), '{')) {
        $sec_title_arr = json_decode($sec_title_raw, true) ?? [];
        $sec_title = $sec_title_arr[$current_lang] ?? $sec_title_arr['de'] ?? $sec_title_arr['ar'] ?? 'خدماتنا المميزة';
    } else {
        $sec_title = $sec_title_raw;
    }

    // جلب ومعالجة وصف القسم
    $sec_desc_raw = get_setting('services_section_desc', '');
    $sec_desc = '';
    if (is_string($sec_desc_raw) && str_starts_with(trim($sec_desc_raw), '{')) {
        $sec_desc_arr = json_decode($sec_desc_raw, true) ?? [];
        $sec_desc = $sec_desc_arr[$current_lang] ?? $sec_desc_arr['de'] ?? $sec_desc_arr['ar'] ?? '';
    } else {
        $sec_desc = $sec_desc_raw;
    }
    ?>

    <h2 class="mb-3 sec-title">
        <?php echo htmlspecialchars($sec_title); ?>
    </h2>
    
    <?php if (!empty($sec_desc)): ?>
          <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars($sec_desc); ?>
        </p>
    <?php endif; ?>

    <div class="row g-4">
      <?php 
      $services_raw = get_setting('services', []);
      $services = is_string($services_raw) ? (json_decode($services_raw, true) ?? []) : $services_raw;
      
      foreach ($services as $service): 
        $service_img = get_image_url($service['img'] ?? null, '/assets/img/home/default.jpg');
        
        // استخراج عنوان الخدمة حسب اللغة الحالية مع بدائل آمنة
        $service_title = $service[$current_lang]['title'] ?? $service['de']['title'] ?? $service['ar']['title'] ?? ($service['title'] ?? 'عنوان الخدمة');
      ?>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <a href="<?php echo htmlspecialchars($service['url'] ?? '#'); ?>" class="card-link text-decoration-none d-block">
            <div class="card" style="background: url('<?php echo htmlspecialchars($service_img); ?>') no-repeat center/cover;">
              <div class="card-info">
                <h3><?php echo htmlspecialchars($service_title); ?></h3>
               <img src="<?php echo get_image_url('assets/img/home/ArrowLink.svg.webp'); ?>" 
     alt="Arrow" 
     style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">

              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
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
    // معالجة عنوان القسم متعدد اللغات
    $choose_title_raw = get_setting('choose_title', 'ما الذي يميز بيتهوفن سيتي');
    if (is_string($choose_title_raw) && str_starts_with(trim($choose_title_raw), '{')) {
        $choose_title_arr = json_decode($choose_title_raw, true) ?? [];
        $choose_title = $choose_title_arr[$current_lang] ?? $choose_title_arr['de'] ?? $choose_title_arr['ar'] ?? 'ما الذي يميز بيتهوفن سيتي';
    } else {
        $choose_title = $choose_title_raw;
    }

    // معالجة وصف القسم متعدد اللغات (إن وُجد)
    $choose_desc_raw = get_setting('choose_section_desc', '');
    $choose_sec_desc = '';
    if (is_string($choose_desc_raw) && str_starts_with(trim($choose_desc_raw), '{')) {
        $choose_desc_arr = json_decode($choose_desc_raw, true) ?? [];
        $choose_sec_desc = $choose_desc_arr[$current_lang] ?? $choose_desc_arr['de'] ?? $choose_desc_arr['ar'] ?? '';
    } else {
        $choose_sec_desc = $choose_desc_raw;
    }
    ?>

    <h2 class="mb-5 sec-title"><?php echo htmlspecialchars($choose_title); ?></h2>
    
    <?php if (!empty($choose_sec_desc)): ?>
        <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars($choose_sec_desc); ?>
        </p>
    <?php endif; ?>

    <div class="row g-3">
      <?php 
      $choose_items_raw = get_setting('choose_items', []);
      $choose_items = is_string($choose_items_raw) ? (json_decode($choose_items_raw, true) ?? []) : $choose_items_raw;

      foreach ($choose_items as $item): 
        $item_img = get_image_url($item['img'] ?? null);
        
        // استخراج العنوان والوصف الخاص بكل ميزة حسب اللغة الحالية مع بدائل آمنة
        $item_title = $item[$current_lang]['title'] ?? $item['de']['title'] ?? $item['ar']['title'] ?? ($item['title'] ?? '');
        $item_desc  = $item[$current_lang]['desc'] ?? $item['de']['desc'] ?? $item['ar']['desc'] ?? ($item['desc'] ?? '');
      ?>
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card choose-card">
            <div class="card-body">
              <a href="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>">
                <img src="<?php echo htmlspecialchars($item_img); ?>" alt="icon">
              </a>
              <h5 class="card-title"><?php echo htmlspecialchars($item_title); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($item_desc); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
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
            // معالجة عنوان قسم التقييمات متعدد اللغات
            $reviews_title_raw = get_setting('reviews_title', 'شاهد ماذا يقول عملاؤنا عنا');
            if (is_string($reviews_title_raw) && str_starts_with(trim($reviews_title_raw), '{')) {
                $reviews_title_arr = json_decode($reviews_title_raw, true) ?? [];
                $reviews_title = $reviews_title_arr[$current_lang] ?? $reviews_title_arr['de'] ?? $reviews_title_arr['ar'] ?? 'شاهد ماذا يقول عملاؤنا عنا';
            } else {
                $reviews_title = $reviews_title_raw;
            }
            ?>
            <h2 class="py-5 text-center sec-title">
                <?php echo htmlspecialchars($reviews_title); ?>
            </h2>
        </div>
    </div>

    <div class="custom-container">
        <div class="reviews-carousel-wrapper">
            <!-- تحديد اتجاه الكاروسيل ديناميكياً بناءً على لغة النظام -->
            <div id="carousel-reviews" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" dir="<?php echo $current_dir; ?>">
                
                <div class="carousel-inner">
                    <?php 
                    $reviews_items_raw = get_setting('reviews_items', []);
                    $reviews_items = is_string($reviews_items_raw) ? (json_decode($reviews_items_raw, true) ?? []) : $reviews_items_raw;

                    if (!empty($reviews_items)): 
                        foreach ($reviews_items as $index => $item): 
                    ?>
                            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                                <div class="video-wrapper">
                                    <div class="video-container">
                                        <iframe src="<?php echo htmlspecialchars($item['url'] ?? ''); ?>" allowfullscreen title="Review Video"></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">لا توجد فيديوهات للعرض حالياً.</p>
                    <?php endif; ?>
                </div>

                <div class="dots mt-4">
                    <?php if (!empty($reviews_items)): ?>
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
        // جلب العنوان والوصف باللغة الحالية مباشرة (الأولوية للغة الحالية ثم العربية)
        $guide_title_raw = get_setting('guide_title', 'دليل بيتهوفن الشامل');
        if (is_string($guide_title_raw) && str_starts_with(trim($guide_title_raw), '{')) {
            $gt_arr = json_decode($guide_title_raw, true) ?? [];
            $guide_title = $gt_arr[$current_lang] ?? $gt_arr['ar'] ?? 'دليل بيتهوفن الشامل';
        } else {
            $guide_title = $guide_title_raw;
        }

        $guide_desc_raw = get_setting('guide_desc', '');
        if (is_string($guide_desc_raw) && str_starts_with(trim($guide_desc_raw), '{')) {
            $gd_arr = json_decode($guide_desc_raw, true) ?? [];
            $guide_desc = $gd_arr[$current_lang] ?? $gd_arr['ar'] ?? '';
        } else {
            $guide_desc = $guide_desc_raw;
        }

        // معالجة نص زر "قراءة المزيد"
        $rm_raw = get_setting('read_more_btn', 'قراءة المزيد');
        if (is_string($rm_raw) && str_starts_with(trim($rm_raw), '{')) {
            $rm_arr = json_decode($rm_raw, true) ?? [];
            $read_more_text = $rm_arr[$current_lang] ?? $rm_arr['ar'] ?? 'قراءة المزيد';
        } else {
            $read_more_text = $rm_raw;
        }

        $guide_items_raw = get_setting('guide_items', []);
        $guide_items = is_string($guide_items_raw) ? (json_decode($guide_items_raw, true) ?? []) : $guide_items_raw;
    ?>
    <h2 class="mb-2 pt-5 sec-title"><?php echo htmlspecialchars($guide_title); ?></h2>
    <?php if (!empty($guide_desc)): ?>
        <p class="main-p"><?php echo nl2br(htmlspecialchars($guide_desc)); ?></p>
    <?php endif; ?>

    <div id="carousel-guide" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" dir="<?php echo $current_dir; ?>">
      <div class="carousel-inner">
        <?php if (!empty($guide_items)): ?>
          <?php 
            $chunks = array_chunk($guide_items, 3);
          ?>
          <?php foreach ($chunks as $index => $slide_items): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
              <div class="row g-4">
                <?php foreach ($slide_items as $item): 
                  // استخراج عنوان ووصف المقال حسب اللغة الحالية مع البديل العربي
                  $item_title = $item[$current_lang]['title'] ?? $item['ar']['title'] ?? ($item['title'] ?? '');
                  $item_desc  = $item[$current_lang]['desc'] ?? $item['ar']['desc'] ?? ($item['desc'] ?? '');
                  
                  $item_img = get_image_url($item['img'] ?? null);
                  $raw_url = $item['url'] ?? '#';
                  $final_url = ($raw_url !== '#' && !str_starts_with($raw_url, 'http')) ? ($path_prefix ?? '') . ltrim($raw_url, '/') : $raw_url;
                ?>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 border-0 shadow-sm">
                      <?php if (!empty($item_img)): ?>
                        <div class="card-img-wrapper">
                          <img src="<?php echo htmlspecialchars($item_img); ?>" alt="<?php echo htmlspecialchars($item_title); ?>" class="img-fluid guide-img">
                        </div>
                      <?php endif; ?>
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mt-3"><?php echo htmlspecialchars($item_title); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo htmlspecialchars($item_desc); ?></p>
                        <a href="<?php echo htmlspecialchars($final_url); ?>" class="btn fw-bold mt-auto d-flex align-items-center gap-2">
                          <?php echo htmlspecialchars($read_more_text); ?>
                          <img src="<?php echo get_image_url('assets/img/ArrowLeft.svg.webp'); ?>" alt="arrow" class="mx-2" width="18" style="<?php echo ($current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
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
        // 1. معالجة عنوان الأسئلة الشائعة بطريقة آمنة
        $faq_title_raw = get_setting('faq_title', 'الأسئلة الشائعة');
        $faq_title = 'الأسئلة الشائعة';
        if (is_string($faq_title_raw) && str_starts_with(trim($faq_title_raw), '{')) {
            $ft_arr = json_decode($faq_title_raw, true);
            if (is_array($ft_arr)) {
                $faq_title = $ft_arr[$current_lang] ?? $ft_arr['ar'] ?? $ft_arr['de'] ?? 'الأسئلة الشائعة';
            }
        } elseif (!empty($faq_title_raw)) {
            $faq_title = $faq_title_raw;
        }

        // 2. معالجة عناصر الأسئلة بطريقة آمنة تمنع الاختفاء
        $faq_items_raw = get_setting('faq_items', []);
        $faq_items = [];
        
        if (is_string($faq_items_raw)) {
            $decoded = json_decode($faq_items_raw, true);
            if (is_array($decoded)) {
                $faq_items = $decoded;
            }
        } elseif (is_array($faq_items_raw)) {
            $faq_items = $faq_items_raw;
        }

        // في حال لم تكن هناك بيانات مخزنة، نعرض أسئلة افتراضية لكي لا يظهر القسم فارغاً
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

    <h2 class="sec-title mb-5"><?php echo htmlspecialchars($faq_title); ?></h2>
    
    <div class="accordion mb-5" id="accordionExample">
      <?php foreach ($faq_items as $index => $item): 
            // استخراج السؤال والجواب بناءً على اللغة الحالية مع بدائل آمنة
            $faq_question = '';
            $faq_answer = '';

            if (is_array($item)) {
                if (isset($item[$current_lang])) {
                    $faq_question = $item[$current_lang]['question'] ?? '';
                    $faq_answer   = $item[$current_lang]['answer'] ?? '';
                } elseif (isset($item['ar'])) {
                    $faq_question = $item['ar']['question'] ?? '';
                    $faq_answer   = $item['ar']['answer'] ?? '';
                } else {
                    // للتوافق مع الشكل القديم إن وجد
                    $faq_question = $item['question'] ?? '';
                    $faq_answer   = $item['answer'] ?? '';
                }
            }
      ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading<?php echo $index; ?>">
            <button class="accordion-button <?php echo ($index !== 0) ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="<?php echo ($index === 0) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $index; ?>">
              <?php echo htmlspecialchars($faq_question); ?>
            </button>
          </h2>
          <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo ($index === 0) ? 'show' : ''; ?>" data-bs-parent="#accordionExample">
            <div class="accordion-body"><?php echo htmlspecialchars($faq_answer); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!-- FAQ section end -->
