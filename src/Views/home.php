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
    // 1. جلب البيانات من قاعدة البيانات وفك الـ JSON بأسلوب آمن
    $services_raw = get_setting('services', []);
    $services_all = is_string($services_raw) ? (json_decode($services_raw, true) ?? []) : (is_array($services_raw) ? $services_raw : []);
    
    // 2. استخراج بيانات اللغة الحالية مع بدائل احتياطية (ar ثم de)
    $s_data = $services_all[$current_lang] ?? ($services_all['ar'] ?? ($services_all['de'] ?? $services_all));
    
    // 3. قراءة العنوان والعنوان الفرعي مع دعم المسميات المختلفة
    $sec_title = $s_data['section_title'] ?? ($s_data['title'] ?? 'خدماتنا المميزة');
    $sec_desc  = $s_data['section_subtitle'] ?? ($s_data['desc'] ?? '');
    $services  = $s_data['items'] ?? [];

    // بديل أخير لكروت الخدمات إذا كانت فارغة في اللغة الحالية
    if (empty($services)) {
        foreach (['ar', 'de', 'en'] as $fallback_lang) {
            if (!empty($services_all[$fallback_lang]['items'])) {
                $services = $services_all[$fallback_lang]['items'];
                break;
            }
        }
    }
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
      <?php if (!empty($services)): ?>
        <?php foreach ($services as $service): 
          // تحديد صورة خلفية الكرت
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
                       style="<?php echo (isset($current_dir) && $current_dir === 'ltr') ? 'transform: scaleX(-1);' : ''; ?>">
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
    // 1. جلب سجل choose_items بالكامل وفك الـ JSON
    $choose_raw  = get_setting('choose_items', '{}');
    $choose_data = is_string($choose_raw) ? (json_decode($choose_raw, true) ?? []) : $choose_raw;

    // 2. اختيار بيانات اللغة الحالية مع Fallback للعربية ثم الألمانية
    $lang_key       = $current_lang ?? 'ar';
    $current_choose = $choose_data[$lang_key] ?? $choose_data['ar'] ?? $choose_data['de'] ?? [];

    // 3. استخراج العنوان والوصف وقائمة الكروت للغة المحددة
    $choose_title = $current_choose['title'] ?? '';
    $choose_desc  = $current_choose['desc'] ?? '';
    $choose_items = $current_choose['items'] ?? [];
    ?>

    <?php if (!empty($choose_title)): ?>
      <h2 class="mb-5 sec-title"><?php echo htmlspecialchars($choose_title); ?></h2>
    <?php endif; ?>
    
    <?php if (!empty($choose_desc)): ?>
        <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars($choose_desc); ?>
        </p>
    <?php endif; ?>

    <div class="row g-3">
      <?php 
      foreach ($choose_items as $item): 
        $item_img   = get_image_url($item['img'] ?? null);
        $item_title = $item['title'] ?? '';
        $item_desc  = $item['desc'] ?? '';
        $item_url   = $item['url'] ?? '#';
      ?>
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card choose-card">
            <div class="card-body">
              <a href="<?php echo htmlspecialchars($item_url); ?>">
                <img src="<?php echo htmlspecialchars($item_img); ?>" alt="<?php echo htmlspecialchars($item_title); ?>">
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
