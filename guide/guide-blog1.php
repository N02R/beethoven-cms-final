<?php 
// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي (بما أن الملف داخل مجلد guide)
$path_prefix = '../'; 

// 2. تمرير ملفات الـ CSS الخاصة بالصفحة ديناميكياً ليتم حقنها داخل الهيدر المشترك
$page_css = [
    'assets/css/education.css',
    'edu-services/css/edu-services.css',
    'assets/css/responsive-education.css' // تم تصحيح خطأ الفاصلة ,, إلى مسار صحيح
];

// 3. استدعاء الهيدر المشترك
include $path_prefix . 'includes/header.php'; 
?>

  <!-- custom-guide start-->
  <section class="custom-services custom-guide py-5">
    <div class="custom-container ">
      <div class="custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/home/image(0).jpg');">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">تعتبر ألمانيا من أفضل الوجهات المفضلة للدراسة لكثير من الطلبة الأجانب</h2>
        <p class="par-text">
          في الوقت الراهن، من بين 2.7 مليون طالب يدرسون في الجامعات الألمانية هنالك أكثر من 380 ألف طالب أجنبي -من بينهم
          الكثير من الطلبه العرب-. وبالإعتماد على أحدث التقارير، فإن هذا العدد يزداد بإستمرار سنوياً.
        </p>
      </div>
      <div class="advice-stars my-5">
        <h5 class="mb-4 note-text">ملاحظات هامة جداً</h5>
        <ul class="star-list">
          <li>
            <p class="fw-bold"><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" />لدى الجامعات الألمانية مواعيد مختلفة لتقديم طلبات التسجيل، ويوجد غالباً مواعيد لفصول الشتاء و الصيف: </p>
            <p><span class="fw-bold">فصل الشتاء: </span>تبدأ عملية التقديم في مارس، يكون الموعد النهائي للتقديم هو 15 يوليو ويبدأ الفصل الدراسي في أكتوبر.</p>
            <p><span class="fw-bold">فصل الصيف:</span>تبدأ عملية التقديم في سبتمبر، يكون الموعد النهائي للتقديم هو 15 يناير ويبدأ الفصل الدراسي في مارس / أبريل</p>
          </li>
          <li>
            <p class="fw-bold"> <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" /> الأخذ بعين الإعتبار أن عملية التسجيل لدى الجامعة و تجهيز الوثائق اللازمة للتأشيرة الدخول إلى ألمانيا تستغرق من شهرين على الأقل لغاية أكثر من أربعة أشهر بحسب الحالة، لذلك ننصح بشدة البدء باكراً بإجراءات التسجيل.</p>
          </li>
          <li>
            <p class="fw-bold"> <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" />للإجابة  على أكثر الأسئلة التي يطرحُها أغلب الطلاب، نقول:</p>
            <ul>
              <li>
                <p> 1. الأوراق المطلوبة للحصول على قبول جامعي يَعتمِد كثيراً على الدرجة الجامِعية التي تريد أن تَحصل عليها و كذلك المستوى العلمي الذي حصلت عليه سابقاً، مثلاً لِدرجة البكالوريوس تحتاج إلى: شهادة ثانوية عامة مُصدقة من السفارة الألمانية، سيرة ذاتية، رسالة الدافع/التحفيز، صورة عن جواز السفر، شهادة إتمام سنة تحضيرية أو شهادة لغة ألمانية تُؤهلك لدخول الجامعة، أو أحياناً إثبات إتمام سنة جامعية في بلدك و شهادة لغة ألمانية تُؤهلك لدخول الجامعة معاً، تأمين صحي. أما لِدرجة الماجستير فتحتاج إلى درجة بكالوريوس مُعترف بها في ألمانيا مُصدقة من السفارة، شهادة لغة ألمانية تُؤهلك لدخول الجامعة أو شهادة لغة إنجليزية مُعترف بها إذا أردت الدراسة باللغة الإنجليزية، سيرة ذاتية، رسالة الدافع/التحفيز، صورة عن جواز السفر، تأمين صحي.</p>
              </li>
              <li>
                <p> 2. لمعرفة <span style="text-decoration: underline; color: #66aaee;">متطلبات تأشيرة الدراسة </span> لدى السفارة أو القنصلية الألمانية، أيضاً  قمنا بجمع معلومات قيِّمة تجدونها في أسفل الصفحة. علماً بأن هذه المُتطلبات تختلف بحسب نوع التأشيرة و الدولة.</p>
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
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services1.png" alt="" /></a>
              <h5 class="card-title">جودة التعليم العالمي</h5>
              <p class="card-text">جامعات ألمانية مرموقة وبرامج أكاديمية معترف بها دوليًا.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services2.png" alt=""></a>
              <h5 class="card-title">شهادات معترف بها دوليًا</h5>
              <p class="card-text">الدراسة في ألمانيا تضمن لك شهادة معترف بها وفرص عمل ومستقبل مهني ناجح.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services3.png" alt=""></a>
              <h5 class="card-title">تدريب عملي إلى جانب الدراسة</h5>
              <p class="card-text">الدراسة في ألمانيا تجمع بين التعلم النظري والتدريب العملي مع شركات حقيقية.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services4.png" alt=""></a>
              <h5 class="card-title">تخصصات متنوعة</h5>
              <p class="card-text">معاهد ألمانيا تقدم آلاف البرامج والشهادات لتناسب جميع اهتمامات الطلاب.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services5.png" alt="" /></a>
              <h5 class="card-title">رسوم دراسية منخفضة</h5>
              <p class="card-text">تعليم برسوم رمزية في الجامعات الحكومية، حتى للطلاب الخليجيين.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services6.png" alt=""></a>
              <h5 class="card-title">فرصة لاكتشاف أوروبا</h5>
              <p class="card-text">تأشيرة الطالب تتيح لك الإقامة في ألمانيا والسفر بحرية داخل أوروبا بدون تأشيرة.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services7.png" alt=""></a>
              <h5 class="card-title">الدراسة بالإنجليزية أو الألمانية</h5>
              <p class="card-text">ألمانيا تقدم آلاف البرامج الدراسية باللغة الإنجليزية لجميع الطلاب.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services8.png" alt=""></a>
              <h5 class="card-title">إمكانية العمل أثناء الدراسة</h5>
              <p class="card-text">تكلفة المعيشة في ألمانيا معقولة، ويمكنك العمل أثناء الدراسة لتساعد نفسك.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services9.png" alt="" /></a>
              <h5 class="card-title">فرص توظيف بعد التخرج</h5>
              <p class="card-text">بعد التخرج، يمكنك البقاء في ألمانيا لفترة للبحث عن وظيفة.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services8.png" alt=""></a>
              <h5 class="card-title">بلد آمن ومستقر</h5>
              <p class="card-text">ألمانيا بلد آمن جدًا، يمكنك التنقل بحرية بدون خوف من الجريمة أو العنصرية.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services11.png" alt=""></a>
              <h5 class="card-title">تعلم الألمانية = فرص أكبر</h5>
              <p class="card-text">الألمانية قريبة من الإنجليزية وتزيد فرصك في الدراسة والشغل.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services12.png" alt=""></a>
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
          <img src="<?php echo $path_prefix; ?>assets/img/vector/Vector.png" alt="base" class="line-base">
          <img src="<?php echo $path_prefix; ?>assets/img/vector/Vector-1.png" alt="active" class="line-active">
          <div class="step-wrapper step-1">
            <div class="step-img-num"><img src="<?php echo $path_prefix; ?>assets/img/vector/Group1.png" alt="01"></div>
            <div class="icon-main"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime1.png" alt=""></div>
            <div class="info-content">
              <h3>استشارة أولية </h3>
              <span class="dot bg-blue"></span>
              <h4>نرسم معك طريقك الدراسي في ألمانيا</h4>
              <p>نساعدك على تحديد التخصص والجامعة المناسبة حسب أهدافك الأكاديمية والمهنية.</p>
            </div>
          </div>
          <div class="step-wrapper step-2">
            <img src="<?php echo $path_prefix; ?>assets/img/vector/Group2.png" class="step-img-num" alt="02">
            <div class="icon-main"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime2.png" alt=""></div>
            <div class="info-content">
              <h3>تجهيز المستندات</h3>
              <span class="dot bg-green"></span>
              <h4>نجهز ملفك بالشكل المثالي</h4>
              <p>ترجمة، تصديق، تنسيق السيرة الذاتية، كتابة خطاب الدافع وكل ما تحتاجه لتقديم قوي</p>
            </div>
          </div>
          <div class="step-wrapper step-3">
            <img src="<?php echo $path_prefix; ?>assets/img/vector/Group3.png" class="step-img-num" alt="03">
            <div class="icon-main"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime3.png" alt=""></div>
            <div class="info-content">
              <h3>تقديم الطلبات</h3>
              <span class="dot bg-yellow"></span>
              <h4>نقدم لك على أفضل الجامعات</h4>
              <p>نختار أفضل الجامعات ونرسل طلباتك ونتابع الردود معك</p>
            </div>
          </div>
          <div class="step-wrapper step-4">
            <img src="<?php echo $path_prefix; ?>assets/img/vector/Group4.png" class="step-img-num" alt="04">
            <div class="icon-main"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime4.png" alt=""></div>
            <div class="info-content">
              <h3>دعم التأشيرة</h3>
              <span class="dot bg-orange"></span>
              <h4>نضمن جهوزيتك الكاملة للمقابلة</h4>
              <p>نعد معك ملف الفيزا بالكامل ونرشدك خلال الإجراءات الرسمية خطوة بخطوة</p>
            </div>
          </div>
          <div class="step-wrapper step-5">
            <img src="<?php echo $path_prefix; ?>assets/img/vector/Group5.png" class="step-img-num" alt="05">
            <div class="icon-main"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime5.png" alt=""></div>
            <div class="info-content">
              <h3>الوصول والاستقرار</h3>
              <span class="dot bg-orange"></span>
              <h4>نستقبلك ونرتب تفاصيل حياتك</h4>
              <p>من الاستقبال في المطار، إلى السكن، إلى التسجيل في المدينة وفتح الحساب البنكي</p>
            </div>
          </div>
          <div class="step-wrapper step-6">
            <img src="<?php echo $path_prefix; ?>assets/img/vector/Group6.png" class="step-img-num" alt="06">
            <div class="icon-main"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime6.png" alt=""></div>
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
                    <div class="m-icon"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime1.png" alt=""></div>
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
                    <div class="m-icon"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime2.png" alt=""></div>
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
                    <div class="m-icon"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime3.png" alt=""></div>
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
                    <div class="m-icon"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime4.png" alt=""></div>
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
                    <div class="m-icon"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime5.png" alt=""></div>
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
                    <div class="m-icon"><img src="<?php echo $path_prefix; ?>assets/img/vector/Grouptime6.png" alt=""></div>
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
// 4. استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
