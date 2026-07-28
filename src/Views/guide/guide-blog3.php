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
      <div class="custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/guide/image.jpg');">
      </div>
    </div>
  </section>
  <!-- custom-services end-->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">معالجة طلبات العملاء في بيتهوفن سيتي للخدمات (BCS)</h2>
        <p class="par-text">
          في عالم الهجرة والدراسة والعمل في ألمانيا، لا يكفي تقديم معلومات عامة، بل تحتاج إلى من يرافقك خطوة بخطوة حتى
          تصل إلى هدفك بثقة وسلاسة. في بيتهوفن سيتي للخدمات (BCS)، نعالج طلبات العملاء بأسلوب احترافي ومنظّم، يضمن لك
          متابعة طلبك في كل مرحلة، وتسهيل رحلتك من لحظة تواصلك معنا وحتى وصولك إلى ألمانيا.
        </p>
      </div>
      <div class="advice-stars">
        <h5 class="mb-4 advice-text">الذي يجعل معالجة طلبات العملاء لدينا مختلفة</h5>
        <ul class="star-list">
          <li>
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold">وضوح الخطوات: </span>
              نخبرك دائمًا بما يلي بعد كل مرحلة.</p>
          </li>
          <li>
            <p> <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold">التواصل المستمر:</span>
               فريقنا معك للإجابة على استفساراتك عبر الواتساب والبريد الإلكتروني.</p>
          </li>
          <li>
            <p> <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold"> أسعار تنافسية وشفافية:</span>
              خدماتنا بأسعار مناسبة، ونشرح لك كل رسوم المعاهد والخدمات مقدمًا.</p>
          </li>
          <li>
            <p> <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2" /><span class="fw-bold"> خبرة:</span>
              نعرف متطلبات المعاهد الألمانية والقنصليات جيدًا</p>
          </li>
        </ul>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

  <!-- time line start -->
  <section class="timeline-section py-5">
    <div class="custom-container">
      <div class="mb-5">
        <h2 class="sec-title">مراحل معالجة طلبك لدينا</h2>
        <p class="main-p">في بيتهوفن سيتي للخدمات، نقسم معالجة طلبك إلى خطوات واضحة:</p>
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

  <!-- contact-guide start -->
  <section class="contact-guide py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">جاهز لبدء رحلتك?</h2>
        <p class="par-text">
          احجز استشارتك المجانية الآن مع فريق BCS وابدأ خطواتك نحو ألمانيا بثقة: <span style="color: #66aaee;" class="fw-bold">تواصل معنا</span>
        </p>
      </div>
    </div>
  </section>
  <!-- contact-guide end -->


