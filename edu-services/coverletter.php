<?php 
// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي
$path_prefix = '../'; 

// 2. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً ليتم حَقنه في الهيدر
$page_css = [
    'css/edu-services.css'
];

// 3. استدعاء الهيدر المشترك
include $path_prefix . 'includes/header.php'; 

// 4. مصفوفة نقاط النصائح لتوليدها ديناميكياً لتجنب تكرار كود الـ HTML
$advicePoints = [
    "المعلومات الشخصية الأساسية.",
    "معلومات عن التعليم الخاص بك.",
    "الهدف من زيارتك إلى ألمانيا.",
    "الوضع المالي ونوع الدعم المالي.",
    "سبب اختيارك للتخصص والجامعة.",
    "الوثائق المرفقة مع التأشيرة."
];
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item" aria-current="page">خطاب الطلب</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg1.jpg'); background-position: center -30px;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">
          رسالة تعريف/خطاب طلب احترافي يدعم طلبك، أياً كان هدفك أو وجهتك
        </h2>
        <p class="par-text">رسالة التعريف أو ما يعرف "Cover Letter" هي مستند موجز من صفحة واحدة يوضح فيها مقدم الطلب
          (بغض النظر عن نوع التأشيرة: طالب، تعلم لغة، تدريب، عمل، علاج، أو غيرها) هدفه التوجه إلى ألمانيا مع توضيح بعض
          التفاصيل الأساسية. كما تتضمن الرسالة قائمة منظمة بجميع الوثائق المرفقة مع الطلب، مما يعزز فرص قبول طلبك بشكل
          احترافي وواضح.
        </p>
      </div>

      <div class="advice-stars my-5">
        <h5 class="advice-text">النقاط التي يجب مراعاتها عند كتابة رسالة التعريف</h5>
        <div class="row star-list mt-4">
          <?php foreach ($advicePoints as $point): ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-3">
              <div class="d-flex align-items-center">
                <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" class="ms-2" alt="نجمة">
                <p class="mb-0"><?php echo $point; ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="advice-check py-5">
        <h5 class="note-text">ملاحظات هامة !!</h5>
        <div class="row">
          <div class="col-lg-4 col-md-6 col-sm-6"><p>✅ اكتب الخطاب بأسلوب رسمي وواضح.</p></div>
          <div class="col-lg-4 col-md-6 col-sm-6"><p>✅ راجع الأخطاء اللغوية قبل الإرسال.</p></div>
          <div class="col-lg-4 col-md-6 col-sm-5"><p>✅ لا تكرر معلومات السيرة الذاتية.</p></div>
          <div class="col-lg-4 col-md-6 col-sm-7"><p>✅ استخدم الألمانية أو الإنجليزية حسب الحاجة.</p></div>
          <div class="col-lg-4 col-md-6 col-sm-12"><p>✅ تواصل معنا لمساعدتك في مراجعة خطابك.</p></div>
        </div>
      </div>

      <!-- Row: PDF & Word examples -->
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card mb-3">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="PDF Icon" />
              <div class="dl-info">
                <div class="dl-title">رسالة التعريف/ خطاب الطلب</div>
                <div class="dl-sub">Example</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................</span>
              <a class="download-link" href="downloads/cover-letter.pdf" download>Download</a>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Groupword.png" alt="Word Icon" />
              <div class="dl-info">
                <div class="dl-title">رسالة التعريف/ خطاب الطلب</div>
                <div class="dl-sub">Example</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................</span>
              <a class="download-link" href="downloads/cover-letter.docx" download>Download</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 5. استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
