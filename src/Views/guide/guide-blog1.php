  <!-- custom-guide start-->
  <section class="custom-services custom-guide py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideHeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container ">
      <div class="custom-hero" style="background-image: url('<?php echo htmlspecialchars(get_image_url($guide_data['hero_img'] ?? 'assets/img/home/image(0).jpg')); ?>'); background-position: <?php echo htmlspecialchars($guide_data['hero_position'] ?? 'center center'); ?>;">
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
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideMainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>
        <h2 class="main-text"><?php echo htmlspecialchars($guide_data['main_title'] ?? ''); ?></h2>
        <p class="par-text"><?php echo nl2br(htmlspecialchars($guide_data['main_desc'] ?? '')); ?></p>
      </div>

      <!-- الملاحظات الهامة -->
      <div class="advice-stars my-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideNotesModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل الملاحظات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-4 note-text"><?php echo htmlspecialchars($guide_data['notes_title'] ?? 'ملاحظات هامة جداً'); ?></h5>
        <ul class="star-list">
          <li>
            <p class="fw-bold">
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="نجمة" class="ms-2" />
              <?php echo htmlspecialchars($guide_data['note_1_bold'] ?? ''); ?>
            </p>
            <p><span class="fw-bold">فصل الشتاء: </span><?php echo htmlspecialchars($guide_data['note_winter'] ?? ''); ?></p>
            <p><span class="fw-bold">فصل الصيف:</span><?php echo htmlspecialchars($guide_data['note_summer'] ?? ''); ?></p>
          </li>
          <li>
            <p class="fw-bold"> 
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="نجمة" class="ms-2" /> 
              <?php echo htmlspecialchars($guide_data['note_2_text'] ?? ''); ?>
            </p>
          </li>
          <li>
            <p class="fw-bold"> 
              <img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/starList.svg')); ?>" alt="نجمة" class="ms-2" />
              <?php echo htmlspecialchars($guide_data['note_3_title'] ?? ''); ?>
            </p>
            <ul>
              <li>
                <p><?php echo htmlspecialchars($guide_data['faq_1'] ?? ''); ?></p>
              </li>
              <li>
                <p> 
                  <?php echo htmlspecialchars($guide_data['faq_2_prefix'] ?? '2. لمعرفة'); ?> 
                  <a href="<?php echo htmlspecialchars(($path_prefix ?? '/') . ltrim($guide_data['faq_2_url'] ?? 'contact', '/')); ?>" style="text-decoration: underline; color: #66aaee;"><?php echo htmlspecialchars($guide_data['faq_2_link_text'] ?? 'متطلبات تأشيرة الدراسة'); ?></a> 
                  <?php echo htmlspecialchars($guide_data['faq_2_suffix'] ?? ''); ?>
                </p>
              </li>
            </ul>
          </li>
        </ul>
      </div>

    </div>
  </section>
  <!-- custom-services-info end -->

  <!-- why study start -->
  <section class="study py-5">
    <div class="custom-container">
      <div class=" mb-5">
        <h2 class="sec-title">لماذا الدراسة في ألمانيا؟</h2>
        <p class="main-p">إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي</p>
      </div>
      <div class="row g-3">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services1.png')); ?>" alt="" /></a>
              <h5 class="card-title">جودة التعليم العالمي</h5>
              <p class="card-text">جامعات ألمانية مرموقة وبرامج أكاديمية معترف بها دوليًا.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services2.png')); ?>" alt=""></a>
              <h5 class="card-title">شهادات معترف بها دوليًا</h5>
              <p class="card-text">الدراسة في ألمانيا تضمن لك شهادة معترف بها وفرص عمل ومستقبل مهني ناجح.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services3.png')); ?>" alt=""></a>
              <h5 class="card-title">تدريب عملي إلى جانب الدراسة</h5>
              <p class="card-text">الدراسة في ألمانيا تجمع بين التعلم النظري والتدريب العملي مع شركات حقيقية.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services4.png')); ?>" alt=""></a>
              <h5 class="card-title">تخصصات متنوعة</h5>
              <p class="card-text">معاهد ألمانيا تقدم آلاف البرامج والشهادات لتناسب جميع اهتمامات الطلاب.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services5.png')); ?>" alt="" /></a>
              <h5 class="card-title">رسوم دراسية منخفضة</h5>
              <p class="card-text">تعليم برسوم رمزية في الجامعات الحكومية، حتى للطلاب الخليجيين.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services6.png')); ?>" alt=""></a>
              <h5 class="card-title">فرصة لاكتشاف أوروبا</h5>
              <p class="card-text">تأشيرة الطالب تتيح لك الإقامة في ألمانيا والسفر بحرية داخل أوروبا بدون تأشيرة.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services7.png')); ?>" alt=""></a>
              <h5 class="card-title">الدراسة بالإنجليزية أو الألمانية</h5>
              <p class="card-text">ألمانيا تقدم آلاف البرامج الدراسية باللغة الإنجليزية لجميع الطلاب.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services8.png')); ?>" alt=""></a>
              <h5 class="card-title">إمكانية العمل أثناء الدراسة</h5>
              <p class="card-text">تكلفة المعيشة في ألمانيا معقولة، ويمكنك العمل أثناء الدراسة لتساعد نفسك.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services9.png')); ?>" alt="" /></a>
              <h5 class="card-title">فرص توظيف بعد التخرج</h5>
              <p class="card-text">بعد التخرج، يمكنك البقاء في ألمانيا لفترة للبحث عن وظيفة.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services8.png')); ?>" alt=""></a>
              <h5 class="card-title">بلد آمن ومستقر</h5>
              <p class="card-text">ألمانيا بلد آمن جدًا، يمكنك التنقل بحرية بدون خوف من الجريمة أو العنصرية.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services11.png')); ?>" alt=""></a>
              <h5 class="card-title">تعلم الألمانية = فرص أكبر</h5>
              <p class="card-text">الألمانية قريبة من الإنجليزية وتزيد فرصك في الدراسة والشغل.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/education/edu-services12.png')); ?>" alt=""></a>
              <h5 class="card-title">ثقافة غنية وتجربة حياتية مميزة</h5>
              <p class="card-text">مجتمع متنوع، صداقات دولية، وانفتاح ثقافي.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- why study end -->

  <!-- time line start -->
  <section class="timeline-section py-5">
    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title">رحلتك إلى ألمانيا خطوة بخطوة مع BCS</h2>
        <p class="main-p">نرشدك من أول استشارة حتى استقرارك في ألمانيا — إليك كيف تتم العملية معنا.</p>
      </div>
      <div class="map-container d-none d-lg-block">
        <div class="map-box">
          <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector.png')); ?>" alt="base" class="line-base">
          <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Vector-1.png')); ?>" alt="active" class="line-active">
          <div class="step-wrapper step-1">
            <div class="step-img-num"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Group1.png')); ?>" alt="01"></div>
            <div class="icon-main"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime1.png')); ?>" alt=""></div>
            <div class="info-content">
              <h3>استشارة أولية </h3>
              <span class="dot bg-blue"></span>
              <h4>نرسم معك طريقك الدراسي في ألمانيا</h4>
              <p>نساعدك على تحديد التخصص والجامعة المناسبة حسب أهدافك الأكاديمية والمهنية.</p>
            </div>
          </div>
          <div class="step-wrapper step-2">
            <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Group2.png')); ?>" class="step-img-num" alt="02">
            <div class="icon-main"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime2.png')); ?>" alt=""></div>
            <div class="info-content">
              <h3>تجهيز المستندات</h3>
              <span class="dot bg-green"></span>
              <h4>نجهز ملفك بالشكل المثالي</h4>
              <p>ترجمة، تصديق، تنسيق السيرة الذاتية، كتابة خطاب الدافع وكل ما تحتاجه لتقديم قوي</p>
            </div>
          </div>
          <div class="step-wrapper step-3">
            <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Group3.png')); ?>" class="step-img-num" alt="03">
            <div class="icon-main"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime3.png')); ?>" alt=""></div>
            <div class="info-content">
              <h3>تقديم الطلبات</h3>
              <span class="dot bg-yellow"></span>
              <h4>نقدم لك على أفضل الجامعات</h4>
              <p>نختار أفضل الجامعات ونرسل طلباتك ونتابع الردود معك</p>
            </div>
          </div>
          <div class="step-wrapper step-4">
            <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Group4.png')); ?>" class="step-img-num" alt="04">
            <div class="icon-main"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime4.png')); ?>" alt=""></div>
            <div class="info-content">
              <h3>دعم التأشيرة</h3>
              <span class="dot bg-orange"></span>
              <h4>نضمن جهوزيتك الكاملة للمقابلة</h4>
              <p>نعد معك ملف الفيزا بالكامل ونرشدك خلال الإجراءات الرسمية خطوة بخطوة</p>
            </div>
          </div>
          <div class="step-wrapper step-5">
            <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Group5.png')); ?>" class="step-img-num" alt="05">
            <div class="icon-main"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime5.png')); ?>" alt=""></div>
            <div class="info-content">
              <h3>الوصول والاستقرار</h3>
              <span class="dot bg-orange"></span>
              <h4>نستقبلك ونرتب تفاصيل حياتك</h4>
              <p>من الاستقبال في المطار، إلى السكن، إلى التسجيل في المدينة وفتح الحساب البنكي</p>
            </div>
          </div>
          <div class="step-wrapper step-6">
            <img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Group6.png')); ?>" class="step-img-num" alt="06">
            <div class="icon-main"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime6.png')); ?>" alt=""></div>
            <div class="info-content">
              <h3>دعم بعد الوصول</h3>
              <span class="dot bg-red"></span>
              <h4>نبقى معك حتى تستقر تمامًا</h4>
              <p>دعم دائم بعد الوصول يشمل الإرشاد، المتابعة الدراسية، وحل أي تحديات تواجهها</p>
            </div>
          </div>
        </div>
      </div>
      <div class="mobile-timeline d-lg-none">
        <div class="m-step">
            <div class="m-number-box"><span class="m-num">01</span></div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime1.png')); ?>" alt=""></div>
                    <h3>استشارة أولية</h3>
                </div>
                <h4>نرسم معك طريقك الدراسي في ألمانيا</h4>
                <p>نساعدك على تحديد التخصص والجامعة المناسبة حسب أهدافك الأكاديمية والمهنية.</p>
            </div>
        </div>
        <div class="m-step">
            <div class="m-number-box"><span class="m-num">02</span></div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime2.png')); ?>" alt=""></div>
                    <h3>تجهيز المستندات</h3>
                </div>
                <h4>نجهز ملفك بالشكل المثالي</h4>
                <p>ترجمة، تصديق، تنسيق السيرة الذاتية، كتابة خطاب الدافع وكل ما تحتاجه لتقديم قوي.</p>
            </div>
        </div>
        <div class="m-step">
            <div class="m-number-box"><span class="m-num">03</span></div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime3.png')); ?>" alt=""></div>
                    <h3>تقديم الطلبات</h3>
                </div>
                <h4>نقدم لك على أفضل الجامعات</h4>
                <p>نختار أفضل الجامعات ونرسل طلباتك ونتابع الردود معك خطوة بخطوة.</p>
            </div>
        </div>
        <div class="m-step">
            <div class="m-number-box"><span class="m-num">04</span></div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime4.png')); ?>" alt=""></div>
                    <h3>دعم التأشيرة</h3>
                </div>
                <h4>نضمن جهوزيتك الكاملة للمقابلة</h4>
                <p>نعد معك ملف الفيزا بالكامل ونرشدك خلال الإجراءات الرسمية لضمان القبول.</p>
            </div>
        </div>
        <div class="m-step">
            <div class="m-number-box"><span class="m-num">05</span></div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime5.png')); ?>" alt=""></div>
                    <h3>الوصول والاستقرار</h3>
                </div>
                <h4>نستقبلك ونرتب تفاصيل حياتك</h4>
                <p>من الاستقبال في المطار إلى السكن، التسجيل في المدينة وفتح الحساب البنكي.</p>
            </div>
        </div>
        <div class="m-step">
            <div class="m-number-box"><span class="m-num">06</span></div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="<?php echo htmlspecialchars(get_image_url('assets/img/vector/Grouptime6.png')); ?>" alt=""></div>
                    <h3>دعم بعد الوصول</h3>
                </div>
                <h4>نبقى معك حتى تستقر تمامًا</h4>
                <p>دعم دائم يشمل الإرشاد، المتابعة الدراسية، وحل أي تحديات تواجهها في ألمانيا.</p>
            </div>
        </div>
      </div>
    </div>
  </section>
  <!-- time line end -->

  <?php
    $guide_modals_file = __DIR__ . '/includes/admin_guide_modals.php';
    if (!empty($is_admin) && file_exists($guide_modals_file)) { 
        include_once $guide_modals_file; 
    }
  ?>
