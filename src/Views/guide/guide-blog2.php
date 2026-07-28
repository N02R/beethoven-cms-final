<?php
// تأمين المتغيرات الافتراضية
$path_prefix = '/';

$guide_blog2_data = $data['guide_blog2_page'] ?? [
    'hero_img'           => 'assets/img/guide/image (1).jpg',
    'hero_position'      => 'center center',
    'main_title'         => 'التعليم والعمل في ألمانيا: فرص جديدة لحياة أفضل',
    'main_desc'          => 'تعتبر ألمانيا واحدة من أفضل الوجهات عالميًا للراغبين في إكمال تعليمهم أو بدء مسيرتهم المهنية، بفضل جودة التعليم المجاني، وتوفّر فرص التدريب المهني، وسوق العمل المستقر الذي يرحب بالكفاءات من جميع أنحاء العالم. في هذه المدونة، سنرشدك إلى كيفية الاستفادة من فرص التعليم والعمل في ألمانيا، والخطوات العملية للبدء، مع نصائح عملية تسهل رحلتك نحو حياة مستقرة وآمنة في أوروبا.',
    'why_title'          => 'لماذا التعليم والعمل في ألمانيا؟',
    'why_subtitle'       => 'إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي',
    'services_title'     => 'ماذا تقدم لك بيتهوفن سيتي للخدمات الطلابية؟',
    'service_1'          => 'تقديم استشارات فردية مصممة وفق احتياجاتك.',
    'service_2'          => 'مساعدتك في إعداد السيرة الذاتية ورسائل التحفيز باللغة الألمانية.',
    'service_3'          => 'التقديم على برامج التدريب المهني (Ausbildung) المناسبة لك',
    'service_4'          => 'التقديم على برامج الإقامة الطبية وخريجي الصحة',
    'service_5'          => 'دعمك في إعداد الوثائق، تعلم اللغة، والحصول على السكن في ألمانيا.',
    'tips_title'         => 'كيف نضمن لك تجربة سلسة وآمنة؟',
    'tip_1_bold'         => 'سهولة الوصول للمعلومات: ',
    'tip_1_text'         => 'جميع خطوات التقديم واضحة وستعرف ماذا عليك أن تفعل في كل مرحلة.',
    'tip_2_bold'         => 'التواصل المستمر: ',
    'tip_2_text'         => 'فريقنا معك للإجابة على استفساراتك عبر الواتساب والبريد الإلكتروني.',
    'tip_3_bold'         => 'أسعار تنافسية وشفافية: ',
    'tip_3_text'         => 'خدماتنا بأسعار مناسبة، ونشرح لك كل رسوم المعاهد والخدمات مقدمًا.'
];
?>

  <!-- custom-guide start-->
  <section class="custom-services custom-guide py-5" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
      <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2HeroModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل صورة الهيرو">
          <i class="bi bi-pencil-fill"></i>
      </button>
    <?php endif; ?>

    <div class="custom-container ">
      <div class="custom-hero" style="background-image: url('<?php echo htmlspecialchars($path_prefix . ltrim($guide_blog2_data['hero_img'] ?? 'assets/img/guide/image (1).jpg', '/') . '?v=' . time()); ?>'); background-position: <?php echo htmlspecialchars($guide_blog2_data['hero_position'] ?? 'center center'); ?>;">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2MainModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل العنوان والوصف">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="main-text"><?php echo htmlspecialchars($guide_blog2_data['main_title'] ?? ''); ?></h2>
        <p class="par-text">
          <?php echo nl2br(htmlspecialchars($guide_blog2_data['main_desc'] ?? '')); ?>
        </p>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

  <!-- why study start -->
  <section class="study py-5">
    <div class="custom-container">
      <div class=" mb-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2WhyModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل قسم لماذا ألمانيا">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h2 class="sec-title"><?php echo htmlspecialchars($guide_blog2_data['why_title'] ?? ''); ?></h2>
        <p class="main-p"><?php echo htmlspecialchars($guide_blog2_data['why_subtitle'] ?? ''); ?></p>
      </div>
      <div class="row g-3">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars($path_prefix); ?>assets/img/education/edu-services1.png" alt="" /></a>
              <h5 class="card-title">تعليم مجاني</h5>
              <p class="card-text">فرصة لدراسة تخصصك المفضل في نظام تعليمي قوي يجمع بين المعرفة والتطبيق. </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars($path_prefix); ?>assets/img/education/edu-services3.png" alt=""></a>
              <h5 class="card-title">فرص عمل ممتازة</h5>
              <p class="card-text">ابدأ مسيرتك المهنية فور تخرجك في سوق عمل يقدّر الكفاءات ويمنحك الاستقرار.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars($path_prefix); ?>assets/img/education/edu-services3.png" alt=""></a>
              <h5 class="card-title">إقامة دائمة</h5>
              <p class="card-text">حقق حلمك بالإقامة بعد سنوات محددة من الدراسة والعمل القانوني في ألمانيا.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo htmlspecialchars($path_prefix); ?>assets/img/education/edu-services4.png" alt=""></a>
              <h5 class="card-title">تخصصات متنوعة</h5>
              <p class="card-text">معاهد ألمانيا تقدم آلاف البرامج والشهادات لتناسب جميع اهتمامات الطلاب.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- why study end -->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="advice-check py-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2ServicesModal" style="position: absolute; top: 10px; right: 20px; z-index: 10;" title="تعديل الخدمات المقدمة">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="advice-text"><?php echo htmlspecialchars($guide_blog2_data['services_title'] ?? ''); ?></h5>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ <?php echo htmlspecialchars($guide_blog2_data['service_1'] ?? ''); ?></p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ <?php echo htmlspecialchars($guide_blog2_data['service_2'] ?? ''); ?></p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ <?php echo htmlspecialchars($guide_blog2_data['service_3'] ?? ''); ?></p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ <?php echo htmlspecialchars($guide_blog2_data['service_4'] ?? ''); ?></p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ <?php echo htmlspecialchars($guide_blog2_data['service_5'] ?? ''); ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end-->

  <!-- time line start -->
  <section class="timeline-section py-5">
    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title">رحلتك إلى ألمانيا خطوة بخطوة مع BCS</h2>
        <p class="main-p">نرشدك من أول استشارة حتى استقرارك في ألمانيا — إليك كيف تتم العملية معنا.</p>
      </div>
      <div class="mobile-timeline">

        <div class="m-step">
          <div class="m-number-box"><span class="m-num">01</span></div>
          <div class="m-content">
            <h4>استشارة مبدئية مجانية</h4>
            <p>تحديد التخصصات والفرص المناسبة لمؤهلاتك ورغباتك.</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box"><span class="m-num">02</span></div>
          <div class="m-content">
            <h4>تجهيز المستندات</h4>
            <p>نرشدك لترجمة وتصديق أوراقك وتجهيز السيرة الذاتية وخطاب الدافع باللغة الألمانية</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box"><span class="m-num">03</span></div>
          <div class="m-content">
            <h4>تعلم اللغة الالمانية</h4>
            <p>نساعدك في اختيار البرامج المناسبة للوصول لمستوى اللغة المطلوب (B1 أو B2 غالبًا).</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box"><span class="m-num">04</span></div>
          <div class="m-content">
            <h4>التقديم على الفرص المناسبة</h4>
            <p>نقوم بالتقديم على المعاهد المهنية أو الجامعات المناسبة لك، ومتابعة طلبك خطوة بخطوة</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box"><span class="m-num">05</span></div>
          <div class="m-content">
           <h4>القبول والتجهيز للسفر</h4>
           <p>نساعدك في الحصول على القبول وتحديد المواعيد لدى السفارة والتجهيز للسفر والإقامة في ألمانيا</p>
          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- time line end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="advice-stars my-5" style="position: relative;">
        <?php if (!empty($is_admin)): ?>
          <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#guideBlog2TipsModal" style="position: absolute; top: 0; right: 0; z-index: 10;" title="تعديل قسم النصائح والضمانات">
              <i class="bi bi-pencil-fill"></i>
          </button>
        <?php endif; ?>

        <h5 class="mb-4 advice-text"><?php echo htmlspecialchars($guide_blog2_data['tips_title'] ?? ''); ?></h5>
        <ul class="star-list">
          <li>
            <p><img src="<?php echo htmlspecialchars($path_prefix); ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold"><?php echo htmlspecialchars($guide_blog2_data['tip_1_bold'] ?? ''); ?></span>
              <?php echo htmlspecialchars($guide_blog2_data['tip_1_text'] ?? ''); ?></p>
          </li>
          <li>
            <p> <img src="<?php echo htmlspecialchars($path_prefix); ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold"><?php echo htmlspecialchars($guide_blog2_data['tip_2_bold'] ?? ''); ?></span>
            <?php echo htmlspecialchars($guide_blog2_data['tip_2_text'] ?? ''); ?></p>
          </li>
          <li>
            <p> <img src="<?php echo htmlspecialchars($path_prefix); ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold"><?php echo htmlspecialchars($guide_blog2_data['tip_3_bold'] ?? ''); ?></span>
               <?php echo htmlspecialchars($guide_blog2_data['tip_3_text'] ?? ''); ?></p>
          </li>
        </ul>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->
