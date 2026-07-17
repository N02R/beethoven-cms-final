<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/header.php'; 
?>

<?php
// جلب بيانات الـ hero من المصفوفة $data الموجودة في header.php
$hero_data = $data['hero'] ?? [];
$hero_title = $hero_data['title'] ?? 'ابدأ رحلتك الأكاديمية في ألمانيا مع BCS';
$hero_desc = $hero_data['desc'] ?? 'هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة...';
$hero_btn_text = $hero_data['btn_text'] ?? 'احجز استشارتك الآن';
$hero_btn_url = $hero_data['btn_url'] ?? '#contact';
$hero_img = $hero_data['img'] ?? 'assets/img/home/home1.png';
?>

<section class="hero py-5 editable-wrapper" aria-label="قسم البداية">
    <?php if ($is_admin): ?>
        <button class="admin-edit-btn" data-bs-toggle="modal" data-bs-target="#heroEditModal"><i class="bi bi-pencil"></i></button>
    <?php endif; ?>
    
    <div class="custom-container">
      <div class="hero-container">
        <div class="hero-content">
          <h1><?php echo htmlspecialchars($hero_title); ?></h1>
          <p><?php echo htmlspecialchars($hero_desc); ?></p>
          <a href="<?php echo htmlspecialchars($hero_btn_url); ?>" class="btn btn-lg hero-btn">
            <?php echo htmlspecialchars($hero_btn_text); ?>
          </a>
        </div>
      </div>
    </div>
</section>

  
  <!-- services start -->
  <section class="services py-5">
    <div class="custom-container">
      <h2 class="mb-5 sec-title">خدماتنا المميزة</h2>
      <div class="row g-4">
        <!-- الخدمة الأولى -->
        <div class="col-lg-6 col-md-6 col-sm-12">
          <a href="education.php" class="card-link text-decoration-none d-block">
            <div class="card" style="background: url('assets/img/home/education.jpg') no-repeat center/cover;">
              <div class="card-info">
                <h3>التعليم العالي والجامعي</h3>
                <img src="assets/img/home/Arrow.svg" alt="Arrow">
              </div>
            </div>
          </a>
        </div>
        <!-- الخدمة الثانية -->
        <div class="col-lg-6 col-md-6 col-sm-12">
          <a href="job.php" class="card-link text-decoration-none d-block">
            <div class="card" style="background: url('assets/img/home/traning.jpg') no-repeat center/cover;">
              <div class="card-info">
                <h3>التأهيل المهني والعملي</h3>
                <img src="assets/img/home/Arrow.svg" alt="Arrow">
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- services end -->
  
  <!-- choose start -->
  <section class="choose py-5">
    <div class="container-fluid custom-container choose-container">
      <h2 class="mb-5 sec-title">ما الذي يميز بيتهوفن سيتي</h2>
      <div class="row g-3">
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card choose-card">
            <div class="card-body">
              <a href="#"><img src="assets/img/home/Grouphome1.svg" alt="" /></a>
              <h5 class="card-title">خدمة متعددة اللغات</h5>
              <p class="card-text">هذا النص هو مثال لنص يستبدل في نفس المساحة، حيث يمكنك توليد النص</p>
            </div>
          </div>
        </div>
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card active choose-card">
            <div class="card-body">
              <a href="#"><img src="assets/img/home/Grouphome2.svg" alt=""></a>
              <h5 class="card-title">خبرة في السوق الألماني</h5>
              <p class="card-text">هذا النص هو مثال لنص يستبدل في نفس المساحة ،حيث يمكنك توليد النص</p>
            </div>
          </div>
        </div>
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card choose-card">
            <div class="card-body">
              <a href="#"><img src="assets/img/home/Grouphome3.svg" alt=""></a>
              <h5 class="card-title">متابعة شاملة للعميل</h5>
              <p class="card-text">هذا النص هو مثال لنص يستبدل في نفس المساحة ،حيث يمكنك توليد النص</p>
            </div>
          </div>
        </div>
        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card choose-card">
            <div class="card-body">
              <a href="#"><img src="assets/img/home/Grouphome4.svg" alt=""></a>
              <h5 class="card-title">أسعار تنافسية</h5>
              <p class="card-text">هذا النص هو مثال لنص يستبدل في نفس المساحة ،حيث يمكنك توليد النص</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- choose end -->
  
  <!-- review start -->
  <section class="reviews py-5">
    <div class="reviews-bg">
      <div class="custom-container">
        <h2 class="py-5 text-center sec-title">
          شاهد ماذا يقول عملاؤنا عنا
        </h2>
      </div>
    </div>

    <div class="custom-container">
      <div class="reviews-carousel-wrapper">
        <div id="carousel-reviews" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" dir="rtl">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="video-wrapper">
                <div class="video-container">
                  <iframe src="https://www.youtube.com/embed/tu51jzzZO9Q?si=12WzAl2S_7VIR0GC" allowfullscreen></iframe>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="video-wrapper">
                <div class="video-container">
                  <iframe src="https://www.youtube.com/embed/xcgM1t5H6dU?si=7xKFttEK2kLpMODI" allowfullscreen></iframe>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="video-wrapper">
                <div class="video-container">
                  <iframe src="https://www.youtube.com/embed/jpaOEC2pVRc?si=XjB-kB9KnTuduh73" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          </div>

          <div class="dots mt-4">
            <span class="dot active" data-bs-target="#carousel-reviews" data-bs-slide-to="0"></span>
            <span class="dot" data-bs-target="#carousel-reviews" data-bs-slide-to="1"></span>
            <span class="dot" data-bs-target="#carousel-reviews" data-bs-slide-to="2"></span>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- review end -->

  <!-- guide start -->
  <section class="guide py-2">
    <div class="custom-container">
      <h2 class="mb-2 pt-5 sec-title">دليل بيتهوفن الشامل</h2>
      <p class="main-p">هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، حيث يمكنك أن تولد مثل هذا النص</p>

      <div id="carousel-guide" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
        <div class="carousel-inner" dir="rtl">

          <!-- Slide 1 -->
          <div class="carousel-item active">
            <div class="row g-4">
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <img src="assets/img/home/image(0).jpg" alt="لماذا يختار الطلاب الدراسة في ألمانيا" class="img-fluid guide-img">
                    <h5 class="card-title fw-bold mt-3">لماذا يختار الطلاب الدراسة في ألمانيا ؟</h5>
                    <p class="card-text">أكثر من 380 ألف طالب دولي- بنهم الاف العرب - يختارون ألمانيا سنويا لجودة تعليمها,رسومها المنخفضة,وفرص العمل بعد التخرج..</p>
                    <a href="guide/guide-blog1.php" class="btn fw-bold mt-auto">قراءة المزيد <img src="assets/img/home/Arrow..svg" alt="" class="me-2"></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <img src="assets/img/guide/image (1).jpg" alt="التعليم والعمل في ألمانيا" class="img-fluid guide-img">
                    <h5 class="card-title fw-bold mt-3">التعليم والعمل في ألمانيا</h5>
                    <p class="card-text">اكتشف بيئة تعليمية متقدمة تفتح أمامك أبواب التدريب والعمل في مجالات مطلوبة عالميا. ألمانيا تجمع بين الدراسة النوعية وفرص التطور المهني.</p>
                    <a href="guide/guide-blog2.php" class="btn fw-bold mt-auto">قراءة المزيد <img src="assets/img/home/Arrow..svg" alt="" class="me-2"></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <img src="assets/img/guide/image.jpg" alt="معالجة طلبات العملاء لدى BCS" class="img-fluid guide-img">
                    <h5 class="card-title fw-bold mt-3">معالجة طلبات العملاء لدى BCS</h5>
                    <p class="card-text">نرافق عملاؤنا خطوة بخطوة من لحظة التواصل الأول حتى الوصول لألمانيا, عبر متابعة دقيقة وشفافة تضمن سرعة الاستجابة وجودة التنفيذ.</p>
                    <a href="guide/guide-blog3.php" class="btn fw-bold mt-auto">قراءة المزيد <img src="assets/img/home/Arrow..svg" alt="" class="me-2"></a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2 -->
          <div class="carousel-item">
            <div class="row g-4">
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <img src="assets/img/guide/image (2).jpg" alt="تعلم اللغة الالمانية" class="img-fluid guide-img">
                    <h5 class="card-title fw-bold mt-3">تعلم اللغة الالمانية بسرعة وكفاءة: دليل المبتدئين</h5>
                    <p class="card-text">دليلك السريع لتعلم اللغة الألمانية بفعالية, من الأساسيات حتى التحدث بثقة, باستخدام طرق بسيطة تناسب المبتدئين.</p>
                    <a href="guide/guide-blog4.php" class="btn fw-bold mt-auto">قراءة المزيد <img src="assets/img/home/Arrow..svg" alt="" class="me-2"></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <img src="assets/img/guide/image (3).jpg" alt="الستودينكولينغ" class="img-fluid guide-img">
                    <h5 class="card-title fw-bold mt-3">ما هو الستودينكولينغ؟ وهل تحتاجه فعلا؟</h5>
                    <p class="card-text">الستودينكولينغ هو برنامج تحضيري للطلاب الأجانب قبل دخول الجامعة, وقد تحتاجه اذا كانت شهادتك الثانوية لا تعادل النظام الألماني.</p>
                    <a href="guide/guide-blog5.php" class="btn fw-bold mt-auto">قراءة المزيد <img src="assets/img/home/Arrow..svg" alt="" class="me-2"></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <img src="assets/img/guide/image (4).jpg" alt="سكن طلابي في ألمانيا" class="img-fluid guide-img">
                    <h5 class="card-title fw-bold mt-3">كيف تحصل على سكن طلابي في ألمانيا؟</h5>
                    <p class="card-text">السكن الطلابي في ألمانيا محدود, لذا ينصح بالبدء في البحث والتقديم مبكرا عبر منصات السكن الجامعي أو المواقع الخاصة, مع تجهيز المستندات المطلوبة مسبقا.</p>
                    <a href="guide/guide-blog6.php" class="btn fw-bold mt-auto">قراءة المزيد <img src="assets/img/home/Arrow..svg" alt="" class="me-2"></a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Slide 3 -->
          <div class="carousel-item">
            <div class="row g-4">
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <img src="assets/img/guide/image (5).jpg" alt="الأوسبيلدونغ" class="img-fluid guide-img">
                    <h5 class="card-title fw-bold mt-3">ما هو الأوسبيلدونغ؟ وهل هو مناسب لك؟</h5>
                    <p class="card-text">تدريب مهني عملي في ألمانيا يمكنك من دخول سوق العمل دون دراسة جامعية, وهو خيار مثالي لمن يفضل التعلم التطبيقي والحصول على راتب أثناء التدريب.</p>
                    <a href="guide/guide-blog7.php" class="btn fw-bold mt-auto">قراءة المزيد <img src="assets/img/home/Arrow..svg" alt="" class="me-2"></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <img src="assets/img/guide/image (6).jpg" alt="أهم المهن المطلوبة في ألمانيا" class="img-fluid guide-img">
                    <h5 class="card-title fw-bold mt-3">أهم المهن المطلوبة في ألمانيا</h5>
                    <p class="card-text">يشهد سوق العمل الألماني طلبا متزايدا على المهن التقنية, والرعاية الصحية, وتكنولوجيا المعلومات, ما يجعلها فرصا واعدة للمهنيين المؤهلين من خارج ألمانيا.</p>
                    <a href="guide/guide-blog8.php" class="btn fw-bold mt-auto">قراءة المزيد <img src="assets/img/home/Arrow..svg" alt="" class="me-2"></a>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100">
                  <div class="card-body d-flex flex-column">
                    <img src="assets/img/guide/image (7).jpg" alt="السيرة الذاتية بالنمط الألماني" class="img-fluid guide-img">
                    <h5 class="card-title fw-bold mt-3">كيف تكتب سيرة ذاتية بالنمط الألماني؟</h5>
                    <p class="card-text">يركز النمط الألماني في كتابة السيرة الذاتية على البساطة, الترتيب الزمني, والمحتوى المهني المباشر. مع الحرص على تنظيم معلوماتك بدقة دون مبالغة في التنسيق أو التصميم.</p>
                    <a href="guide/guide-blog9.php" class="btn fw-bold mt-auto">قراءة المزيد <img src="assets/img/home/Arrow..svg" alt="" class="me-2"></a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Dots -->
        <div class="dots mt-4">
          <span class="dot active" data-bs-target="#carousel-guide" data-bs-slide-to="0"></span>
          <span class="dot" data-bs-target="#carousel-guide" data-bs-slide-to="1"></span>
          <span class="dot" data-bs-target="#carousel-guide" data-bs-slide-to="2"></span>
        </div>
      </div>
    </div>
  </section>
  <!-- guide end -->

  <!-- popular start -->
  <section class="popular py-5">
    <div class="container-fluid custom-container">
      <h2 class="sec-title mb-5">الأسئلة الشائعة</h2>
      <!-- Accordion -->
      <div class="accordion mb-5" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
              aria-expanded="true" aria-controls="collapseOne">
              ما هي المتطلبات الأساسية للتقديم على الجامعات الألمانية؟
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
              تحتاج إلى شهادة الثانوية العامة أو ما يعادلها، إثبات كفاءة في اللغة الألمانية أو الإنجليزية
              (حسب لغة البرنامج)، وسيرة ذاتية وخطاب تحفيز.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              هل يمكنني الدراسة في ألمانيا بدون لغة ألمانية؟
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
              نعم، بعض الجامعات الألمانية تقدم برامج باللغة الإنجليزية بالكامل، لكن يُنصح بتعلم اللغة
              الألمانية لتسهيل الحياة اليومية وفرص العمل لاحقًا.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              ما هي تكلفة الدراسة والمعيشة في ألمانيا؟
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
              معظم الجامعات الحكومية مجانية، لكن يجب توفير مصاريف المعيشة (حوالي 900 يورو شهريًا) والتي
              تشمل السكن، الطعام، والمواصلات.
            </div>
          </div>
        </div>
      </div>
      <!-- Form -->
      <div class="call bg-white shadow-sm py-5 px-4">
        <h5 class="mb-4">أخبرنا عمّا يدور في بالك أيضًا</h5>
        <form class="form mx-auto" action="send_inquiry.php" method="POST">
          <input type="text" name="question" placeholder="أدخل السؤال الذي يخطر ببالك لنقم بالرد عليك" required>
          <button type="submit"><img src="assets/img/home/send-2.svg" alt="إرسال"></button>
        </form>
      </div>
    </div>
  </section>
  <!-- popular end -->

<?php 
include 'includes/footer.php'; 
?>
