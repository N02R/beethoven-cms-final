<?php 
// تعريف ملفات الـ CSS الخاصة بصفحة التعليم العالي فقط
$page_css = [
    'assets/css/education.css',
    'assets/css/responsive-education.css'
];

// استدعاء الهيدر المشترك (يحتوي تلقائياً على شريط التنقل والروابط المحدثة)
include 'includes/header.php'; 
?>

  <!-- education start -->
  <section class="education py-5">
    <div class="custom-container">
      <div class="row align-items-stretch g-5">
        <div class="col-lg-6">
          <div class="img-hero"></div>
        </div>
        <div class="col-lg-6">
          <div class="education-info pt-2">
            <h2 class="sec-title">ابدأ رحلتك التعليمية في ألمانيا – مع خدماتنا، التعليم العالي أقرب إليك من أي وقت مضى!
            </h2>
            <p class="main-p">نوفر لك دعمًا شاملًا في كل خطوة.. من اختيار التخصص والجامعة، إلى التقديم والحصول على
              القبول، وحتى تأمين السكن والتأشيرة، و بفضل خبرتنا ووجودنا داخل ألمانيا، نضمن لك تجربة سلسة وموثوقة، بلغتك،
              وبأسعار تنافسية</p>
            <a href="#" class="btn btn-education">ابدأ الآن</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- education end -->

  <!-- why study start -->
  <section class="study py-5">
    <div class="custom-container">
      <div class=" mb-5">
        <h2 class="sec-title">لماذا الدراسة في ألمانيا؟</h2>
        <p class="main-p">إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي
        </p>
      </div>
      <div class="row g-3">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services1.png" alt="" /></a>
              <h5 class="card-title">جودة التعليم العالمي</h5>
              <p class="card-text">جامعات ألمانية مرموقة وبرامج أكاديمية معترف بها دوليًا.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services2.png" alt=""></a>
              <h5 class="card-title">شهادات معترف بها دوليًا</h5>
              <p class="card-text">الدراسة في ألمانيا تضمن لك شهادة معترف بها وفرص عمل ومستقبل مهني ناجح.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services3.png" alt=""></a>
              <h5 class="card-title">تدريب عملي إلى جانب الدراسة</h5>
              <p class="card-text">الدراسة في ألمانيا تجمع بين التعلم النظري والتدريب العملي مع شركات حقيقية.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services4.png" alt=""></a>
              <h5 class="card-title">تخصصات متنوعة</h5>
              <p class="card-text">معاهد ألمانيا تقدم آلاف البرامج والشهادات لتناسب جميع اهتمامات الطلاب.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services5.png" alt="" /></a>
              <h5 class="card-title">رسوم دراسية منخفضة</h5>
              <p class="card-text">تعليم برسوم رمزية في الجامعات الحكومية، حتى للطلاب الخليجيين.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services6.png" alt=""></a>
              <h5 class="card-title">فرصة لاكتشاف أوروبا</h5>
              <p class="card-text">تأشيرة الطالب تتيح لك الإقامة في ألمانيا والسفر بحرية داخل أوروبا بدون تأشيرة.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services7.png" alt=""></a>
              <h5 class="card-title">الدراسة بالإنجليزية أو الألمانية</h5>
              <p class="card-text">ألمانيا تقدم آلاف البرامج الدراسية باللغة الإنجليزية لجميع الطلاب.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services8.png" alt=""></a>
              <h5 class="card-title">إمكانية العمل أثناء الدراسة</h5>
              <p class="card-text">تكلفة المعيشة في ألمانيا معقولة، ويمكنك العمل أثناء الدراسة لتساعد نفسك.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services9.png" alt="" /></a>
              <h5 class="card-title">فرص توظيف بعد التخرج</h5>
              <p class="card-text">بعد التخرج، يمكنك البقاء في ألمانيا لفترة للبحث عن وظيفة.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services8.png" alt=""></a>
              <h5 class="card-title">بلد آمن ومستقر</h5>
              <p class="card-text">ألمانيا بلد آمن جدًا، يمكنك التنقل بحرية بدون خوف من الجريمة أو العنصرية.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services11.png" alt=""></a>
              <h5 class="card-title">تعلم الألمانية = فرص أكبر</h5>
              <p class="card-text">الألمانية قريبة من الإنجليزية وتزيد فرصك في الدراسة والشغل.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/education/edu-services12.png" alt=""></a>
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
          <img src="assets/img/vector/Vector.png" alt="base" class="line-base">
          <img src="assets/img/vector/Vector-1.png" alt="active" class="line-active">
          <div class="step-wrapper step-1">
            <div class="step-img-num"><img src="assets/img/vector/Group1.png" alt="01"></div>
            <div class="icon-main"><img src="assets/img/vector/Grouptime1.png" alt=""></div>
            <div class="info-content">
              <h3>استشارة أولية </h3>
              <span class="dot bg-blue"></span>
              <h4>نرسم معك طريقك الدراسي في ألمانيا</h4>
              <p>نساعدك على تحديد التخصص والجامعة المناسبة حسب أهدافك الأكاديمية والمهنية.</p>
            </div>
          </div>
          <div class="step-wrapper step-2">
            <img src="assets/img/vector/Group2.png" class="step-img-num" alt="02">
            <div class="icon-main"><img src="assets/img/vector/Grouptime2.png" alt=""></div>
            <div class="info-content">
              <h3>تجهيز المستندات</h3>
              <span class="dot bg-green"></span>
              <h4>نجهز ملفك بالشكل المثالي</h4>
              <p>ترجمة، تصديق، تنسيق السيرة الذاتية، كتابة خطاب الدافع وكل ما تحتاجه لتقديم قوي</p>
            </div>
          </div>
          <div class="step-wrapper step-3">
            <img src="assets/img/vector/Group3.png" class="step-img-num" alt="03">
            <div class="icon-main"><img src="assets/img/vector/Grouptime3.png" alt=""></div>
            <div class="info-content">
              <h3>تقديم الطلبات</h3>
              <span class="dot bg-yellow"></span>
              <h4>نقدم لك على أفضل الجامعات</h4>
              <p>نختار أفضل الجامعات ونرسل طلباتك ونتابع الردود معك</p>
            </div>
          </div>
          <div class="step-wrapper step-4">
            <img src="assets/img/vector/Group4.png" class="step-img-num" alt="04">
            <div class="icon-main"><img src="assets/img/vector/Grouptime4.png" alt=""></div>
            <div class="info-content">
              <h3>دعم التأشيرة</h3>
              <span class="dot bg-orange"></span>
              <h4>نضمن جهوزيتك الكاملة للمقابلة</h4>
              <p>نعد معك ملف الفيزا بالكامل ونرشدك خلال الإجراءات الرسمية خطوة بخطوة</p>
            </div>
          </div>
          <div class="step-wrapper step-5">
            <img src="assets/img/vector/Group5.png" class="step-img-num" alt="05">
            <div class="icon-main"><img src="assets/img/vector/Grouptime5.png" alt=""></div>
            <div class="info-content">
              <h3>الوصول والاستقرار</h3>
              <span class="dot bg-orange"></span>
              <h4>نستقبلك ونرتب تفاصيل حياتك</h4>
              <p>من الاستقبال في المطار، إلى السكن، إلى التسجيل في المدينة وفتح الحساب البنكي</p>
            </div>
          </div>
          <div class="step-wrapper step-6">
            <img src="assets/img/vector/Group6.png" class="step-img-num" alt="06">
            <div class="icon-main"><img src="assets/img/vector/Grouptime6.png" alt=""></div>
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
              <div class="m-number-box">
                  <span class="m-num">01</span>
              </div>
              <div class="m-content">
                  <div class="m-header">
                      <div class="m-icon"><img src="assets/img/vector/Grouptime1.png" alt=""></div>
                      <h3>استشارة أولية</h3>
                  </div>
                  <h4>نرسم معك طريقك الدراسي في ألمانيا</h4>
                  <p>نساعدك على تحديد التخصص والجامعة المناسبة حسب أهدافك الأكاديمية والمهنية.</p>
              </div>
          </div>

          <div class="m-step">
              <div class="m-number-box">
                  <span class="m-num">02</span>
              </div>
              <div class="m-content">
                  <div class="m-header">
                      <div class="m-icon"><img src="assets/img/vector/Grouptime2.png" alt=""></div>
                      <h3>تجهيز المستندات</h3>
                  </div>
                  <h4>نجهز ملفك بالشكل المثالي</h4>
                  <p>ترجمة، تصديق، تنسيق السيرة الذاتية، كتابة خطاب الدافع وكل ما تحتاجه لتقديم قوي.</p>
              </div>
          </div>

          <div class="m-step">
              <div class="m-number-box">
                  <span class="m-num">03</span>
              </div>
              <div class="m-content">
                  <div class="m-header">
                      <div class="m-icon"><img src="assets/img/vector/Grouptime3.png" alt=""></div>
                      <h3>تقديم الطلبات</h3>
                  </div>
                  <h4>نقدم لك على أفضل الجامعات</h4>
                  <p>نختار أفضل الجامعات ونرسل طلباتك ونتابع الردود معك خطوة بخطوة.</p>
              </div>
          </div>

          <div class="m-step">
              <div class="m-number-box">
                  <span class="m-num">04</span>
              </div>
              <div class="m-content">
                  <div class="m-header">
                      <div class="m-icon"><img src="assets/img/vector/Grouptime4.png" alt=""></div>
                      <h3>دعم التأشيرة</h3>
                  </div>
                  <h4>نضمن جهوزيتك الكاملة للمقابلة</h4>
                  <p>نعد معك ملف الفيزا بالكامل ونرشدك خلال الإجراءات الرسمية لضمان القبول.</p>
              </div>
          </div>

          <div class="m-step">
              <div class="m-number-box">
                  <span class="m-num">05</span>
              </div>
              <div class="m-content">
                  <div class="m-header">
                      <div class="m-icon"><img src="assets/img/vector/Grouptime5.png" alt=""></div>
                      <h3>الوصول والاستقرار</h3>
                  </div>
                  <h4>نستقبلك ونرتب تفاصيل حياتك</h4>
                  <p>من الاستقبال في المطار إلى السكن، التسجيل في المدينة وفتح الحساب البنكي.</p>
              </div>
          </div>

          <div class="m-step">
              <div class="m-number-box">
                  <span class="m-num">06</span>
              </div>
              <div class="m-content">
                  <div class="m-header">
                      <div class="m-icon"><img src="assets/img/vector/Grouptime6.png" alt=""></div>
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

  <!-- education services start -->
  <section class="edu-services py-5">
    <div class="container">
      <h2 class="sec-title mb-3">ماذا تقدم في بيتهوفن سيتي؟</h2>
      <p class="mb-5 main-p">توفر شركة بيتهوفن سيتي خدمات متكاملة للطلبة الراغبين بالالتحاق بالجامعات الألمانية
        بما في ذلك طلبة الدراسات العليا</p>
      <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 text-center">
        
        <!-- ملحوظة: تم تعديل المسارات لتتجه لملفات php داخل المجلد الفرعي لتجهيزها لاحقاً للـ CMS -->
        <div class="col">
          <a href="edu-services/coverletter.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg1.jpg');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/coverletter.php">خطاب الطلب</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/cv.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg2.jpg');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/cv.php">السيرة الذاتية (CV)</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/motivitionletter.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg3.png');background-position: right center;">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/motivitionletter.php">رسالة الدافع والتحفيز</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/germanlang.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg4.png'); background-position: -31px center;">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/germanlang.php">دورات اللغة الألمانية</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/englishlang.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg5.png'); background-position:  center bottom;">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/englishlang.php">مساقات اللغة الإنجليزية</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/health.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg6.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/health.php">التأمين الصحي</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/financial.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg7.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/financial.php">الضمانات المالية</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/living.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg8.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/living.php">تكلفة المعيشة في آلمانيا</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/arrival.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg9.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/arrival.php">الوصول والإقامة</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/pakeges.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg10.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/pakeges.php">برامجنا الأكاديمية</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/foundation.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/serviceimg11.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/foundation.php">الدورة التأسيسية</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/courses.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg12.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/courses.php">الدورة التحضيرية</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/check.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg13.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/check.php">تحقق من شهادتك</a> </h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/general.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg14.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/general.php">متطلبات التأشيرة</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/services-cost.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/education/servicesimg15.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/services-cost.php">قائمة أسعار الخدمات</a></h6>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- education services end -->

<?php 
// استدعاء الفوتر المشترك
include 'includes/footer.php'; 
?>
