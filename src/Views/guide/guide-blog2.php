<?php 
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>
<?php 
// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي (بما أن الملف داخل مجلد فرعي)
$path_prefix = '../'; 

// 2. تمرير ملفات الـ CSS الخاصة بالصفحة ديناميكياً ليتم حقنها داخل الهيدر المشترك
$page_css = [
    'assets/css/education.css',
    'edu-services/css/edu-services.css'
];


?>

  <!-- custom-guide start-->
  <section class="custom-services custom-guide py-5">
    <div class="custom-container ">
      <div class="custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/guide/image (1).jpg');">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">التعليم والعمل في ألمانيا: فرص جديدة لحياة أفضل</h2>
        <p class="par-text">
          تعتبر ألمانيا واحدة من أفضل الوجهات عالميًا للراغبين في إكمال تعليمهم أو بدء مسيرتهم المهنية، بفضل جودة
          التعليم المجاني، وتوفّر فرص التدريب المهني، وسوق العمل المستقر الذي يرحب بالكفاءات من جميع أنحاء العالم.
          في هذه المدونة، سنرشدك إلى كيفية الاستفادة من فرص التعليم والعمل في ألمانيا، والخطوات العملية للبدء، مع نصائح
          عملية تسهل رحلتك نحو حياة مستقرة وآمنة في أوروبا.
        </p>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

  <!-- why study start -->
  <section class="study py-5">
    <div class="custom-container">
      <div class=" mb-5">
        <h2 class="sec-title">لماذا التعليم والعمل في ألمانيا؟</h2>
        <p class="main-p">إنها بيئة مثالية للطلاب الطموحين من جميع أنحاء العالم لبناء مستقبل أكاديمي ومهني قوي</p>
      </div>
      <div class="row g-3">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services1.png" alt="" /></a>
              <h5 class="card-title">تعليم مجاني</h5>
              <p class="card-text">فرصة لدراسة تخصصك المفضل في نظام تعليمي قوي يجمع بين المعرفة والتطبيق. </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services3.png" alt=""></a>
              <h5 class="card-title">فرص عمل ممتازة</h5>
              <p class="card-text">ابدأ مسيرتك المهنية فور تخرجك في سوق عمل يقدّر الكفاءات ويمنحك الاستقرار.</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
          <div class="card choose-card h-100">
            <div class="card-body">
              <a href="#"><img src="<?php echo $path_prefix; ?>assets/img/education/edu-services3.png" alt=""></a>
              <h5 class="card-title">إقامة دائمة</h5>
              <p class="card-text">حقق حلمك بالإقامة بعد سنوات محددة من الدراسة والعمل القانوني في ألمانيا.</p>
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
      </div>
    </div>
  </section>
  <!-- why study end -->

  <!-- custom-services-info start-->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="advice-check py-5">
        <h5 class="advice-text">ماذا تقدم لك بيتهوفن سيتي للخدمات الطلابية؟</h5>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ تقديم استشارات فردية مصممة وفق احتياجاتك.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ مساعدتك في إعداد السيرة الذاتية ورسائل التحفيز باللغة الألمانية.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ التقديم على برامج التدريب المهني (Ausbildung) المناسبة لك</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ التقديم على برامج الإقامة الطبية وخريجي الصحة</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ دعمك في إعداد الوثائق، تعلم اللغة، والحصول على السكن في ألمانيا.</p>
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
          <div class="m-number-box">
            <span class="m-num">01</span>
          </div>
          <div class="m-content">
            <h4>استشارة مبدئية مجانية</h4>
            <p>تحديد التخصصات والفرص المناسبة لمؤهلاتك ورغباتك.</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num">02</span>
          </div>
          <div class="m-content">
            <h4>تجهيز المستندات</h4>
            <p>نرشدك لترجمة وتصديق أوراقك وتجهيز السيرة الذاتية وخطاب الدافع باللغة الألمانية</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num">03</span>
          </div>
          <div class="m-content">
            <h4>تعلم اللغة الالمانية</h4>
            <p>نساعدك في اختيار البرامج المناسبة للوصول لمستوى اللغة المطلوب (B1 أو B2 غالبًا).</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num">04</span>
          </div>
          <div class="m-content">
            <h4>التقديم على الفرص المناسبة</h4>
            <p>نقوم بالتقديم على المعاهد المهنية أو الجامعات المناسبة لك، ومتابعة طلبك خطوة بخطوة</p>
          </div>
        </div>

        <div class="m-step">
          <div class="m-number-box">
            <span class="m-num">05</span>
          </div>
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
      <div class="advice-stars my-5">
        <h5 class="mb-4 advice-text">كيف نضمن لك تجربة سلسة وآمنة؟</h5>
        <ul class="star-list">
          <li>
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold">سهولة الوصول للمعلومات: </span>
              جميع خطوات التقديم واضحة وستعرف ماذا عليك أن تفعل في كل مرحلة.</p>
          </li>
          <li>
            <p> <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold">التواصل المستمر:</span>
            فريقنا معك للإجابة على استفساراتك عبر الواتساب والبريد الإلكتروني.</p>
          </li>
          <li>
            <p> <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold"> أسعار تنافسية وشفافية:</span>
               خدماتنا بأسعار مناسبة، ونشرح لك كل رسوم المعاهد والخدمات مقدمًا.</p>
          </li>
        </ul>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->


