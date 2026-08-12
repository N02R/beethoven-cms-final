<!-- بداية قسم الهيرو -->
<?php 
  $hero_exists = isset($data['hero']);
  echo "<!-- هل بيانات الهيرو موجودة؟ " . ($hero_exists ? 'نعم' : 'لا') . " -->";
?>
<!-- hero start -->
<section class="hero py-5" aria-label="قسم البداية" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#heroEditModal" title="تعديل الهيرو">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <?php 
    $hero = $data['hero'] ?? [];
    $hero_bg = get_image_url($hero['img'] ?? null, '/assets/img/home/home1.png');
    ?>
    <div class="hero-container" style="background: url('<?php echo htmlspecialchars($hero_bg); ?>') center/cover no-repeat;">
      <div class="hero-content">
        <h1><?php echo htmlspecialchars($hero['title'] ?? 'عنوان افتراضي'); ?></h1>
        <p><?php echo htmlspecialchars($hero['desc'] ?? 'وصف افتراضي للقسم'); ?></p>
        <a href="<?php echo htmlspecialchars($hero['btn_url'] ?? '#'); ?>" class="btn btn-lg hero-btn">
          <?php echo htmlspecialchars($hero['btn_text'] ?? 'اضغط هنا'); ?>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- hero end -->

<!-- services start -->
<section class="services py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#servicesEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <h2 class="mb-3 sec-title">
        <?php echo htmlspecialchars($data['services_section_title'] ?? 'خدماتنا المميزة'); ?>
    </h2>
    
    <?php if (!empty($data['services_section_desc'])): ?>
          <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars($data['services_section_desc']); ?>
        </p>
    <?php endif; ?>

    <div class="row g-4">
      <?php 
      $services = $data['services'] ?? []; 
      foreach ($services as $service): 
        $service_img = get_image_url($service['img'] ?? null, '/assets/img/home/default.jpg');
      ?>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <a href="<?php echo htmlspecialchars($service['url'] ?? '#'); ?>" class="card-link text-decoration-none d-block">
            <div class="card" style="background: url('<?php echo htmlspecialchars($service_img); ?>') no-repeat center/cover;">
              <div class="card-info">
                <h3><?php echo htmlspecialchars($service['title'] ?? 'عنوان الخدمة'); ?></h3>
                <img src="<?php echo get_image_url('assets/img/home/ArrowLink.svg.webp'); ?>" alt="Arrow">
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
<section class="choose py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#chooseEditModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;">
        <i class="bi bi-pencil-fill"></i>
    </button>
  <?php endif; ?>

  <div class="container-fluid custom-container choose-container">
    <h2 class="mb-5 sec-title"><?php echo htmlspecialchars($data['choose_title'] ?? 'ما الذي يميز بيتهوفن سيتي'); ?></h2>
    <?php if (!empty($data['choose_section_desc'])): ?>
        <p class="mb-5 text-muted" style="max-width: 700px;">
            <?php echo htmlspecialchars($data['choose_section_desc']); ?>
        </p>
    <?php endif; ?>
    <div class="row g-3">
      <?php foreach (($data['choose_items'] ?? []) as $item): ?>
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card choose-card">
            <div class="card-body">
              <a href="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>">
                <img src="<?php echo get_image_url($item['img'] ?? null); ?>" alt="icon">
              </a>
              <h5 class="card-title"><?php echo htmlspecialchars($item['title'] ?? ''); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($item['desc'] ?? ''); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!-- choose end -->
  
<!-- review start -->
<section class="reviews py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#reviewsEditModal" title="تعديل التقييمات">
            <i class="bi bi-pencil-fill"></i>
        </button>
    <?php endif; ?>

    <div class="reviews-bg">
        <div class="custom-container">
            <h2 class="py-5 text-center sec-title">
                <?php echo htmlspecialchars($data['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا'); ?>
            </h2>
        </div>
    </div>

    <div class="custom-container">
        <div class="reviews-carousel-wrapper">
            <div id="carousel-reviews" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" dir="rtl">
                
                <div class="carousel-inner">
                    <?php if (!empty($data['reviews_items'])): ?>
                        <?php foreach ($data['reviews_items'] as $index => $item): ?>
                            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                                <div class="video-wrapper">
                                    <div class="video-container">
                                        <iframe src="<?php echo htmlspecialchars($item['url'] ?? ''); ?>" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">لا توجد فيديوهات للعرض حالياً.</p>
                    <?php endif; ?>
                </div>

                <div class="dots mt-4">
                    <?php if (!empty($data['reviews_items'])): ?>
                        <?php foreach ($data['reviews_items'] as $index => $item): ?>
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
<section class="guide py-2" style="position: relative;">
  <div class="custom-container">
    <?php 
        $guide_title = $data['guide_title'] ?? 'دليل بيتهوفن الشامل';
        $guide_desc  = $data['guide_desc'] ?? 'هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، حيث يمكنك أن تولد مثل هذا النص من مولد النص العربي.';
        $guide_items = $data['guide_items'] ?? [];
    ?>
    <h2 class="mb-2 pt-5 sec-title"><?php echo htmlspecialchars($guide_title); ?></h2>
    <p class="main-p"><?php echo nl2br(htmlspecialchars($guide_desc)); ?></p>

    <div id="carousel-guide" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
      <div class="carousel-inner" dir="rtl">
        <?php if (!empty($guide_items)): ?>
          <?php 
            // تقسيم المقالات إلى مجموعات (كل مجموعة تحتوي على 3 كروت كحد أقصى لتوافق الـ Carousel)
            $chunks = array_chunk($guide_items, 3);
          ?>
          <?php foreach ($chunks as $index => $slide_items): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
              <div class="row g-4">
                <?php foreach ($slide_items as $item): ?>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 border-0 shadow-sm">
                      <?php if (!empty($item['img'])): ?>
                        <div class="card-img-wrapper">
                          <img src="<?php echo get_image_url($item['img']); ?>" alt="<?php echo htmlspecialchars($item['title'] ?? 'guide image'); ?>" class="img-fluid guide-img">
                        </div>
                      <?php endif; ?>
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mt-3"><?php echo htmlspecialchars($item['title'] ?? ''); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo htmlspecialchars($item['desc'] ?? ''); ?></p>
                        <a href="<?php echo htmlspecialchars($item['url'] ?? '#'); ?>" class="btn fw-bold mt-auto d-flex align-items-center gap-2">
                          قراءة المزيد 
                          <img src="<?php echo get_image_url('assets/img/ArrowLeft.svg.webp'); ?>" alt="arrow" class="me-2" width="18">
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

      <!-- Dots (Indicators) بناءً على عدد الشرائح المتوفرة ديناميكياً -->
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


<section class="popular py-5" style="position: relative;">
  <?php if (!empty($is_admin)): ?>
    <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#faqEditModal"><i class="bi bi-pencil-fill"></i></button>
  <?php endif; ?>

  <div class="container-fluid custom-container">
    <h2 class="sec-title mb-5"><?php echo htmlspecialchars($data['faq_title'] ?? 'الأسئلة الشائعة'); ?></h2>
    <div class="accordion mb-5" id="accordionExample">
      <?php foreach (($data['faq_items'] ?? []) as $index => $item): ?>
        <div class="accordion-item">
          <h2 class="accordion-header" id="heading<?php echo $index; ?>">
            <button class="accordion-button <?php echo ($index !== 0) ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">
              <?php echo htmlspecialchars($item['question'] ?? ''); ?>
            </button>
          </h2>
          <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo ($index === 0) ? 'show' : ''; ?>" data-bs-parent="#accordionExample">
            <div class="accordion-body"><?php echo htmlspecialchars($item['answer'] ?? ''); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
