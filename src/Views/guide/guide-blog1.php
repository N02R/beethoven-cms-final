<?php
/**
 * @var array $settings
 * @var bool $isAdmin
 */
?>

<!-- custom-guide start-->
<section class="custom-services custom-guide py-5 position-relative">
  <?php if ($isAdmin): ?>
    <!-- زر تعديل الهيرو الخاص بلوحة التحكم -->
    <button type="button" class="btn btn-sm btn-warning position-absolute top-0 start-0 m-3 z-3 edit-section-btn" data-bs-toggle="modal" data-bs-target="#editHeroModal">
      <i class="fas fa-edit ms-1"></i> تعديل الغلاف
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="custom-hero" style="background-image: url('<?= htmlspecialchars($settings['hero_img'] ?? '../assets/img/home/image(0).jpg') ?>');">
    </div>
  </div>
</section>
<!-- custom-services end-->

<!-- custom-services-info start -->
<section class="custom-services-info py-5 position-relative">
  <?php if ($isAdmin): ?>
    <button type="button" class="btn btn-sm btn-warning position-absolute top-0 start-0 m-3 z-3 edit-section-btn" data-bs-toggle="modal" data-bs-target="#editContentInfoModal">
      <i class="fas fa-edit ms-1"></i> تعديل النصوص والملاحظات
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="head-info">
      <h2 class="main-text"><?= htmlspecialchars($settings['main_title'] ?? '') ?></h2>
      <p class="par-text">
        <?= nl2br(htmlspecialchars($settings['main_desc'] ?? '')) ?>
      </p>
    </div>
    <div class="advice-stars my-5">
      <h5 class="mb-4 note-text"><?= htmlspecialchars($settings['notes_title'] ?? 'ملاحظات هامة جداً') ?></h5>
      <ul class="star-list">
        <li>
          <p class="fw-bold"><img src="../assets/img/education/starList.svg" alt="" class="ms-2" /><?= htmlspecialchars($settings['note_1_bold'] ?? '') ?></p>
          <p><span class="fw-bold">فصل الشتاء: </span><?= htmlspecialchars($settings['note_winter'] ?? '') ?></p>
          <p><span class="fw-bold">فصل الصيف:</span><?= htmlspecialchars($settings['note_summer'] ?? '') ?></p>
        </li>
        <li>
          <p class="fw-bold"><img src="../assets/img/education/starList.svg" alt="" class="ms-2" /> <?= htmlspecialchars($settings['note_2_text'] ?? '') ?></p>
        </li>
        <li>
          <p class="fw-bold"><img src="../assets/img/education/starList.svg" alt="" class="ms-2" /><?= htmlspecialchars($settings['note_3_title'] ?? '') ?></p>
          <ul>
            <ol>
              <p>1. <?= htmlspecialchars($settings['faq_1'] ?? '') ?></p>
            </ol>
            <ol>
              <p>2. <?= htmlspecialchars($settings['faq_2_prefix'] ?? 'لمعرفة') ?> <a href="<?= htmlspecialchars($settings['faq_2_url'] ?? 'visa') ?>" style="text-decoration: underline; color: #66aaee;"><?= htmlspecialchars($settings['faq_2_link_text'] ?? 'متطلبات تأشيرة الدراسة') ?></a> <?= htmlspecialchars($settings['faq_2_suffix'] ?? '') ?></p>
            </ol>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</section>
<!-- custom-services-info end -->

<!-- why study start -->
<section class="study py-5 position-relative">
  <?php if ($isAdmin): ?>
    <button type="button" class="btn btn-sm btn-warning position-absolute top-0 start-0 m-3 z-3 edit-section-btn" data-bs-toggle="modal" data-bs-target="#editWhyStudyModal">
      <i class="fas fa-edit ms-1"></i> تعديل الأسباب
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="mb-5">
      <h2 class="sec-title"><?= htmlspecialchars($settings['why_study_title'] ?? 'لماذا الدراسة في ألمانيا؟') ?></h2>
      <p class="main-p"><?= htmlspecialchars($settings['why_study_desc'] ?? '') ?></p>
    </div>
    <div class="row g-3">
      <?php 
      $defaultCards = [
          ['img' => '../assets/img/education/edu-services1.png', 'title' => 'جودة التعليم العالمي', 'text' => 'جامعات ألمانية مرموقة وبرامج أكاديمية معترف بها دوليًا.'],
          ['img' => '../assets/img/education/edu-services2.png', 'title' => 'شهادات معترف بها دوليًا', 'text' => 'الدراسة في ألمانيا تضمن لك شهادة معترف بها وفرص عمل ومستقبل مهني ناجح.'],
          ['img' => '../assets/img/education/edu-services3.png', 'title' => 'تدريب عملي إلى جانب الدراسة', 'text' => 'الدراسة في ألمانيا تجمع بين التعلم النظري والتدريب العملي مع شركات حقيقية.'],
          ['img' => '../assets/img/education/edu-services4.png', 'title' => 'تخصصات متنوعة', 'text' => 'معاهد ألمانيا تقدم آلاف البرامج والشهادات لتناسب جميع اهتمامات الطلاب.'],
          ['img' => '../assets/img/education/edu-services5.png', 'title' => 'رسوم دراسية منخفضة', 'text' => 'تعليم برسوم رمزية في الجامعات الحكومية، حتى للطلاب الخليجيين.'],
          ['img' => '../assets/img/education/edu-services6.png', 'title' => 'فرصة لاكتشاف أوروبا', 'text' => 'تأشيرة الطالب تتيح لك الإقامة في ألمانيا والسفر بحرية داخل أوروبا بدون تأشيرة.'],
          ['img' => '../assets/img/education/edu-services7.png', 'title' => 'الدراسة بالإنجليزية أو الألمانية', 'text' => 'ألمانيا تقدم آلاف البرامج الدراسية باللغة الإنجليزية لجميع الطلاب.'],
          ['img' => '../assets/img/education/edu-services8.png', 'title' => 'إمكانية العمل أثناء الدراسة', 'text' => 'تكلفة المعيشة في ألمانيا معقولة، ويمكنك العمل أثناء الدراسة لتساعد نفسك.'],
          ['img' => '../assets/img/education/edu-services9.png', 'title' => 'فرص توظيف بعد التخرج', 'text' => 'بعد التخرج، يمكنك البقاء في ألمانيا لفترة للبحث عن وظيفة.'],
          ['img' => '../assets/img/education/edu-services8.png', 'title' => 'بلد آمن ومستقر', 'text' => 'ألمانيا بلد آمن جدًا، يمكنك التنقل بحرية بدون خوف من الجريمة أو العنصرية.'],
          ['img' => '../assets/img/education/edu-services11.png', 'title' => 'تعلم الألمانية = فرص أكبر', 'text' => 'الألمانية قريبة من الإنجليزية وتزيد فرصك في الدراسة والشغل.'],
          ['img' => '../assets/img/education/edu-services12.png', 'title' => 'ثقافة غنية وتجربة حياتية مميزة', 'text' => 'مجتمع متنوع، صداقات دولية، وانفتاح ثقافي.']
      ];
      $cards = !empty($settings['content_sections']) ? $settings['content_sections'] : $defaultCards;
      
      foreach ($cards as $card):
      ?>
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card choose-card h-100">
          <div class="card-body">
            <a href="#"><img src="<?= htmlspecialchars($card['img']) ?>" alt="" /></a>
            <h5 class="card-title"><?= htmlspecialchars($card['title']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($card['text']) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!-- why study end -->

<!-- time line start -->
<section class="timeline-section py-5 position-relative">
  <?php if ($isAdmin): ?>
    <button type="button" class="btn btn-sm btn-warning position-absolute top-0 start-0 m-3 z-3 edit-section-btn" data-bs-toggle="modal" data-bs-target="#editTimelineModal">
      <i class="fas fa-edit ms-1"></i> تعديل الخط الزمني
    </button>
  <?php endif; ?>

  <div class="custom-container">
    <div class="mb-5">
      <h2 class="sec-title"><?= htmlspecialchars($settings['timeline_title'] ?? 'رحلتك إلى ألمانيا خطوة بخطوة مع BCS') ?></h2>
      <p class="main-p"><?= htmlspecialchars($settings['timeline_desc'] ?? '') ?></p>
    </div>
    
    <!-- خط زمني للشاشات الكبيرة -->
    <div class="map-container d-none d-lg-block">
      <div class="map-box">
        <img src="../assets/img/vector/Vector.png" alt="base" class="line-base">
        <img src="../assets/img/vector/Vector-1.png" alt="active" class="line-active">
        
        <?php
        $defaultSteps = [
            ['num' => '01', 'dot' => 'bg-blue', 'icon' => '../assets/img/vector/Grouptime1.png', 'title' => 'استشارة أولية', 'subtitle' => 'نرسم معك طريقك الدراسي في ألمانيا', 'desc' => 'نساعدك على تحديد التخصص والجامعة المناسبة حسب أهدافك الأكاديمية والمهنية.'],
            ['num' => '02', 'dot' => 'bg-green', 'icon' => '../assets/img/vector/Grouptime2.png', 'title' => 'تجهيز المستندات', 'subtitle' => 'نجهز ملفك بالشكل المثالي', 'desc' => 'ترجمة، تصديق، تنسيق السيرة الذاتية، كتابة خطاب الدافع وكل ما تحتاجه لتقديم قوي'],
            ['num' => '03', 'dot' => 'bg-yellow', 'icon' => '../assets/img/vector/Grouptime3.png', 'title' => 'تقديم الطلبات', 'subtitle' => 'نقدم لك على أفضل الجامعات', 'desc' => 'نختار أفضل الجامعات ونرسل طلباتك ونتابع الردود معك'],
            ['num' => '04', 'dot' => 'bg-orange', 'icon' => '../assets/img/vector/Grouptime4.png', 'title' => 'دعم التأشيرة', 'subtitle' => 'نضمن جهوزيتك الكاملة للمقابلة', 'desc' => 'نعد معك ملف الفيزا بالكامل ونرشدك خلال الإجراءات الرسمية خطوة بخطوة'],
            ['num' => '05', 'dot' => 'bg-orange', 'icon' => '../assets/img/vector/Grouptime5.png', 'title' => 'الوصول والاستقرار', 'subtitle' => 'نستقبلك ونرتب تفاصيل حياتك', 'desc' => 'من الاستقبال في المطار، إلى السكن، إلى التسجيل في المدينة وفتح الحساب البنكي'],
            ['num' => '06', 'dot' => 'bg-red', 'icon' => '../assets/img/vector/Grouptime6.png', 'title' => 'دعم بعد الوصول', 'subtitle' => 'نبقى معك حتى تستقر تمامًا', 'desc' => 'دعم دائم بعد الوصول يشمل الإرشاد، المتابعة الدراسية، وحل أي تحديات تواجهها']
        ];
        $steps = !empty($settings['timeline_steps']) ? $settings['timeline_steps'] : $defaultSteps;
        
        foreach ($steps as $index => $step):
            $stepNum = $index + 1;
        ?>
        <div class="step-wrapper step-<?= $stepNum ?>">
          <div class="step-img-num"><img src="../assets/img/vector/Group<?= $stepNum ?>.png" alt="0<?= $stepNum ?>"></div>
          <div class="icon-main"><img src="<?= htmlspecialchars($step['icon']) ?>" alt=""></div>
          <div class="info-content">
            <h3><?= htmlspecialchars($step['title']) ?></h3>
            <span class="dot <?= htmlspecialchars($step['dot'] ?? 'bg-blue') ?>"></span>
            <h4><?= htmlspecialchars($step['subtitle']) ?></h4>
            <p><?= htmlspecialchars($step['desc']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- خط زمني للهواتف -->
    <div class="mobile-timeline d-lg-none">
      <?php foreach ($steps as $index => $step): 
          $stepNum = str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT);
      ?>
      <div class="m-step">
        <div class="m-number-box">
            <span class="m-num"><?= $stepNum ?></span>
        </div>
        <div class="m-content">
            <div class="m-header">
                <div class="m-icon"><img src="<?= htmlspecialchars($step['icon']) ?>" alt=""></div>
                <h3><?= htmlspecialchars($step['title']) ?></h3>
            </div>
            <h4><?= htmlspecialchars($step['subtitle']) ?></h4>
            <p><?= htmlspecialchars($step['desc']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<!-- time line end -->

<?php 
// تضمين ملف نوافذ لوحة التحكم إذا كان المستخدم مشرفاً
if ($isAdmin) {
    include __DIR__ . '/admin_guide_blog_one_modals.php';
}
?>
