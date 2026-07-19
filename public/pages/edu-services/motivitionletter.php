<?php 
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}
?>
<?php 
// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي
$path_prefix = '../'; 

// 2. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً ليتم حَقنه في الهيدر
$page_css = [
    'css/edu-services.css'
];

// 3. استدعاء الهيدر المشترك

?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">خطاب الدافع / التحفيز</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container ">
      <div class="motivition-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg3.png');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">خطاب دافع احترافي يعزز طلبك الأكاديمي أو المهني</h2>
        <p class="par-text">خطاب الدافع/التحفيز هي وثيقة من صفحة واحدة كحد أقصى. تكتُب فيها عن نفسك وتُظهر إهتمامك بالطلب الذي تتقدم إليه و الهدف الذي تريد تحقيقه مثل: (دورة لغة ألمانية، سنة تحضيرية بهدف دخول الجامعة، درجة البكالوريوس أو الماجستير، التدريب أو الزمالة الطبية، إلخ).<br>
          إضافة الى ذلك، يتركز الأمر أكثر على دراستك المستقبلية وخططك المهنية وكيف أن درجة البكالوريوس مثلا التي تتقدم إليها ستساعدك على تحقيق أهدافك المستقبلية. أيضا يمكنك أن تشرح بها الأسباب التي تجعل منك المرشح المثالي لهذا المنصب.</p>
      </div>

      <div class="advice-check py-5">
        <h5 class="advice-text mb-4">نصائح سريعة لكتابة خطاب الدافع</h5>
        <div class="row gy-3">
          <div class="col-lg-4 col-md-6 col-sm-12">
            <p class="mb-0">✅ ابدأ بمقدمة تلخّص دوافعك</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <p class="mb-0">✅ اذكر أمثلة ملموسة (دراسة، تدريب، تجربة)</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <p class="mb-0">✅ اربط خبراتك بأهدافك القادمة</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <p class="mb-0">✅ استخدم لغة واضحة وشخصية</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <p class="mb-0">✅ احصل على مراجعة من مختص أو ناطق أصلي.</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <p class="mb-0">✅ راجع الأخطاء اللغوية جيدًا.</p>
          </div>
        </div>
      </div>

      <div class="row gy-3">
        <!-- كرت تحميل ملف PDF -->
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="ملف PDF" />
              <div class="dl-info">
                <div class="dl-title">خطاب الدافع / التحفيز</div>
                <div class="dl-sub">Example (PDF)</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.........................................................................................................................</span>
              <a class="download-link" href="<?php echo $path_prefix; ?>assets/files/motivation_letter.pdf" download>Download</a>
            </div>
          </div>
        </div>
        
        <!-- كرت تحميل ملف Word -->
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Groupword.png" alt="ملف Word" />
              <div class="dl-info">
                <div class="dl-title">خطاب الدافع / التحفيز</div>
                <div class="dl-sub">Example (Word)</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">.........................................................................................................................</span>
              <a class="download-link" href="<?php echo $path_prefix; ?>assets/files/motivation_letter.docx" download>Download</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

