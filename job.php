<?php 
$path_prefix = ''; 
// تعريف ملفات الـ CSS الخاصة بصفحة التدريب المهني فقط
$page_css = [
    'assets/css/education.css', // الصفحة تشترك مع التعليم العالي في بعض التنسيقات مثل التايم لاين والخدمات
    'assets/css/responsive-education.css'
];

// استدعاء الهيدر المشترك (يحتوي تلقائياً على شريط التنقل والروابط المحدثة)
include 'includes/header.php'; 
?>

  <!-- job start -->
  <section class="job py-5">
    <div class="custom-container">
      <div class="row align-items-stretch g-5">
        <div class="col-lg-6">
          <div class="job-info pt-2">
            <h2 class="sec-title">ابدأ طريقك المهني في ألمانيا - تدريب عملي حقيقي، خبرة معترف بها، وفرص عمل تليق بطموحك!</h2>
            <p class="main-p">نساعدك على ربط تعليمك الأكاديمي بالحياة العملية في ألمانيا من خلال برامج تدريب احترافية، بشراكة مع شركات ألمانية حقيقية. فرصة ذهبية لاكتساب خبرة أوروبية تُعزز سيرتك الذاتية وتفتح لك أبواب سوق العمل الدولي</p>
            <a href="#" class="btn btn-job">ابدأ الآن</a>
          </div>
        </div>
         <div class="col-lg-6">
           <div class="img-hero"></div>
         </div>
      </div>
    </div>
  </section>
  <!-- job end -->

  <!-- why study start -->
  <section class="study py-5">
    <div class="custom-container">
      <div class=" mb-5">
        <h2 class="sec-title">لماذا التدريب معنا؟</h2>
        <p class="main-p">في بيتهوفن سيتي، نوفر لك تدريبًا مهنيًا مميزًا مع دعم مستمر وشراكات مع شركات ألمانية رائدة لبناء مستقبل مهني ناجح.</p>
      </div>
      <div class="row g-3">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services1.png" alt="" /></a>
              <h5 class="card-title">الكفاءة والإحتراف</h5>
              <p class="card-text">اكتساب مهارات نظرية وعملية متكاملة تُتقن بها مهنتك.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services2.png" alt=""></a>
              <h5 class="card-title">تعلم واحصل على أجر</h5>
              <p class="card-text">اكتساب راتب شهري أثناء فترة التدريب المهني.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services3.png" alt=""></a>
              <h5 class="card-title">مستقبل مهني بخيارات متنوعة</h5>
              <p class="card-text">فرص متنوعة بعد التدريب: دراسة جامعية، تأسيس عمل، أو دخول سوق العمل.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services4.png" alt=""></a>
              <h5 class="card-title">فرص كبيرة للتوظيف</h5>
              <p class="card-text">فرصة عالية للحصول على وظيفة دائمة في شركات التدريب بعد الانتهاء.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services5.png" alt="" /></a>
              <h5 class="card-title">شراكات حقيقية مع الشركات</h5>
              <p class="card-text">نوفر فرص تدريب عملي حقيقي مع شركاء معتمدين في ألمانيا.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services6.png" alt=""></a>
              <h5 class="card-title">دخل مضمون مع حد أدنى للأجور</h5>
              <p class="card-text">حتى كمتدرب، تحصل على دخل ثابت وفق الحد الأدنى القانوني، ما يوفر دعم مستمر</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services7.png" alt=""></a>
              <h5 class="card-title">إجازات مرضية مدفوعة</h5>
              <p class="card-text">في حال المرض، يحق لك الحصول على إجازة تصل إلى 6 أسابيع مدفوعة بالكامل.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services8.png" alt=""></a>
              <h5 class="card-title">فرص إقامة وسكن ميسرة</h5>
              <p class="card-text">نوفر لك دعمًا في تأمين سكن مريح طوال فترة التدريب.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services9.png" alt="" /></a>
              <h5 class="card-title">الأمان و الإستقرار</h5>
              <p>ألمانيا بلد آمن جدًا، يمكنك التنقل بحرية بدون خوف من الجريمة أو العنصرية.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services8.png" alt=""></a>
              <h5 class="card-title">دعم تأشيرات وسفر</h5>
              <p class="card-text">نساعدك في تجهيز التأشيرة وإجراءات السفر لدخول ألمانيا بسهولة.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services11.png" alt=""></a>
              <h5 class="card-title">تنمية لغتك ومهاراتك الثقافية</h5>
              <p class="card-text">تطور لغتك ومهاراتك الثقافية للاندماج السريع في بيئة العمل الألمانية.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="assets/img/job/job-services12.png" alt=""></a>
              <h5 class="card-title">دعم كامل باللغة العربية</h5>
              <p class="card-text">فريقنا يدعمك إداريًا ولغويًا بالعربية طوال فترة التدريب.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- why study end -->

  <!-- program start -->
  <section class="program py-5">
    <div class="container">
      <div class=" mb-5">
        <h2 class="sec-title fw-bold">أنواع التدريب المهني</h2>
        <p class="main-p">
        في ألمانيا، التدريب المهني ليس مسارًا واحدًا، بل يتفرّع إلى نوعين رئيسيين يلبيان احتياجات مختلفة للطلاب والمتدربين.
        </p>
      </div>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="program-info h-100">
            <div class="program-content">
              <img src="assets/img/job/job-program1.png" alt="التدريب المدرسي" class="mb-3">
              <h4 class="fw-bold mb-4">التدريب المهني المدرسي</h4>
              <p>
                التدريب المهني المدرسي يتم داخل مدرسة متخصصة، ويركز على الجوانب النظرية مع تطبيق عملي
                محدود.
                مناسب للمجالات الطبية والاجتماعية والتعليمية، ويكون مجانيًا أو برسوم حسب نوع المدرسة.
              </p>
            </div>

            <a href="#" class="btn-info-wrapper mt-4">
              <h3 class="mb-0">اطلب الآن</h3>
              <div class="arrow-icon">
                <img src="assets/img/home/Arrow.svg" alt="سهم">
              </div>
            </a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="program-info h-100 highlight-box">
            <div class="program-content">
              <img src="assets/img/job/job-program2.png" alt="التدريب المزدوج" class="mb-3">
              <h4 class="text-white fw-bold mb-4">التدريب المهني المزدوج</h4>
              <p class="text-white">
                التدريب المهني المزدوج في ألمانيا يجمع بين الدراسة في مدرسة مهنية والعمل داخل شركة، مما يوفر خبرة عملية حقيقية. يتمتع المتدرب بأجر شهري وتأمينات، وتكون لديه فرص عالية للتوظيف بعد الانتهاء.
              </p>
            </div>
            <a href="#" class="btn-info-wrapper mt-4 is-light">
              <h3 class="mb-0">اطلب الآن</h3>
              <div class="arrow-icon">
                <img src="assets/img/home/Arrow.svg" alt="سهم">
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- program end -->

  <!-- time line start -->
  <section class="timeline-section py-5">
    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title">كيف نساعدك خطوة بخطوة؟</h2>
        <p class="main-p">رحلة واضحة تبدأ بالاستشارة وتنتهي بشهادة تدريب وخبرة مهنية حقيقية.</p>
      </div>
      <div class="map-container d-none d-lg-block">
        <div class="map-box">
          <img src="assets/img/vector/Vector.png" alt="base" class="line-base">
          <img src="assets/img/vector/Vector-1.png" alt="active" class="line-active">
          <div class="step-wrapper step-1">
            <div class="step-img-num"><img src="assets/img/vector/Group1.png" alt="01"></div>
            <div class="icon-main"><img src="assets/img/vector/Grouptime1.png" alt=""></div>
            <div class="info-content">
              <h3>استشارة مهنية مخصصة</h3>
              <span class="dot bg-blue"></span>
              <h4>فهم أهدافك واختيار التخصص المناسب</h4>
              <p>مكالمة أولية لفهم خلفيتك واختيار المجال المناسب لك.</p>
            </div>
          </div>
          <div class="step-wrapper step-2">
            <img src="assets/img/vector/Group2.png" class="step-img-num" alt="02">
            <div class="icon-main"><img src="assets/img/vector/Group (6).png" alt=""></div>
            <div class="info-content">
              <h3>اختيار البرنامج الأنسب</h3>
              <span class="dot bg-green"></span>
              <h4>مطابقة بين مؤهلاتك والفرص المتاحة</h4>
              <p>مطابقة مؤهلاتك مع فرص تدريب متوفرة في شركات موثوقة.</p>
            </div>
          </div>
          <div class="step-wrapper step-3">
            <img src="assets/img/vector/Group3.png" class="step-img-num" alt="03">
            <div class="icon-main"><img src="assets/img/vector/Group (3).png" alt=""></div>
            <div class="info-content">
              <h3>تجهيز ملفك المهني</h3>
              <span class="dot bg-yellow"></span>
              <h4>عرض احترافي لسيرتك وخبراتك</h4>
              <p>إعداد سيرة ذاتية وخطاب تحفيزي وترجمة الشهادات.</p>
            </div>
          </div>
          <div class="step-wrapper step-4">
            <img src="assets/img/vector/Group4.png" class="step-img-num" alt="04">
            <div class="icon-main"><img src="assets/img/vector/Group (7).png" alt=""></div>
            <div class="info-content">
              <h3>التقديم والتنسيق</h3>
              <span class="dot bg-orange"></span>
              <h4>تقديم الطلبات وترتيب المقابلات</h4>
              <p>نقدّم الطلبات نيابة عنك وننسق مقابلاتك مع الشركات.</p>
            </div>
          </div>
          <div class="step-wrapper step-5">
            <img src="assets/img/vector/Group5.png" class="step-img-num" alt="05">
            <div class="icon-main"><img src="assets/img/vector/Group.png" alt=""></div>
            <div class="info-content">
              <h3>القبول والاستعداد</h3>
              <span class="dot bg-orange"></span>
              <h4>دعم كامل للسفر والسكن والتأشيرة</h4>
              <p>مساعدة في التأشيرة، السكن، والتحضير للخطوة العملية.</p>
            </div>
          </div>
          <div class="step-wrapper step-6">
            <img src="assets/img/vector/Group6.png" class="step-img-num" alt="06">
            <div class="icon-main"><img src="assets/img/vector/Grouptime6.png" alt=""></div>
            <div class="info-content">
              <h3>المرافقة خلال التدريب</h3>
              <span class="dot bg-red"></span>
              <h4>متابعة مستمرة حتى إنهاء التدريب بنجاح</h4>
              <p>متابعة مستمرة حتى نهاية البرنامج وإصدار شهادة رسمية.</p>
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
                    <h3>استشارة مهنية مخصصة</h3>
                </div>
                <h4>فهم أهدافك واختيار التخصص المناسب</h4>
                <p>مكالمة أولية لفهم خلفيتك واختيار المجال المناسب لك في سوق العمل الألماني.</p>
            </div>
        </div>

        <div class="m-step">
            <div class="m-number-box">
                <span class="m-num">02</span>
            </div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="assets/img/vector/Group (6).png" alt=""></div>
                    <h3>اختيار البرنامج الأنسب</h3>
                </div>
                <h4>مطابقة بين مؤهلاتك والفرص المتاحة</h4>
                <p>مطابقة مؤهلاتك مع فرص تدريب متوفرة في شركات ألمانية موثوقة وشريكة.</p>
            </div>
        </div>

        <div class="m-step">
            <div class="m-number-box">
                <span class="m-num">03</span>
            </div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="assets/img/vector/Group (3).png" alt=""></div>
                    <h3>تجهيز ملفك المهني</h3>
                </div>
                <h4>عرض احترافي لسيرتك وخبراتك</h4>
                <p>إعداد سيرة ذاتية (Lebenslauf) وخطاب تحفيزي احترافي وترجمة وتعديل الشهادات.</p>
            </div>
        </div>

        <div class="m-step">
            <div class="m-number-box">
                <span class="m-num">04</span>
            </div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="assets/img/vector/Group (7).png" alt=""></div>
                    <h3>التقديم والتنسيق</h3>
                </div>
                <h4>تقديم الطلبات وترتيب المقابلات</h4>
                <p>نقدّم الطلبات نيابة عنك وننسق مقابلاتك المباشرة مع الشركات الألمانية المانحة للتدريب.</p>
            </div>
        </div>

        <div class="m-step">
            <div class="m-number-box">
                <span class="m-num">05</span>
            </div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="assets/img/vector/Group.png" alt=""></div>
                    <h3>القبول والاستعداد</h3>
                </div>
                <h4>دعم كامل للسفر والسكن والتأشيرة</h4>
                <p>تأمين عقد التدريب، المساعدة في إجراءات التأشيرة، السكن، والتحضير للبدء في ألمانيا.</p>
            </div>
        </div>

        <div class="m-step">
            <div class="m-number-box">
                <span class="m-num">06</span>
            </div>
            <div class="m-content">
                <div class="m-header">
                    <div class="m-icon"><img src="assets/img/vector/Grouptime6.png" alt=""></div>
                    <h3>المرافقة خلال التدريب</h3>
                </div>
                <h4>متابعة مستمرة حتى إنهاء التدريب بنجاح</h4>
                <p>متابعة دورية خلال فترة العمل لضمان استقرارك، وحتى إصدار شهادة خبرة رسمية معتمدة.</p>
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
      <p class="mb-5 main-p">توفر شركة بيتهوفن سيتي خدمات متكاملة للطلبة الراغبين بالإلتحاق بالجامعات الألمانية بما في ذلك طلبة الدراسات العليا</p>
      <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 text-center">
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
              style="background-image: url('assets/img/job/servicesimg1.png'); background-position:  center bottom;">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/englishlang.php">التخصصات الطبية</a></h6>
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
              style="background-image: url('assets/img/job/servicesimg2.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/pakeges.php">باقة التدريب المهني</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/foundation.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/job/serviceimg3.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/foundation.php">باقة الإقامة الطبية</a></h6>
              </div>
            </div>
          </a>
        </div>
        <div class="col">
          <a href="edu-services/courses.php">
            <div class="card service-card text-white border-0 rounded-5"
              style="background-image: url('assets/img/job/servicesimg4.png');">
              <div class="card-body d-flex align-items-end justify-content-center">
                <h6 class="card-title"><a href="edu-services/courses.php">اتفاقية البحث عن عمل</a></h6>
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
