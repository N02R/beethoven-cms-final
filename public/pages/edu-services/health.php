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
        <li class="breadcrumb-item" aria-current="page">التأمين الصحي</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container ">
      <div class="health-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg6.png');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">التأمين الصحي للطلاب، الزائرين، المتدربين، العاملين، وغيرهم.</h2>
        <p class="par-text">نوفر لك خيارات تأمين صحي حكومي وخاص تناسب حالتك وتعتمد من السفارات والجامعات الألمانية، لتبدأ رحلتك بثقة وبدون تعقيدات.</p>
      </div>

      <div class="advice-check">
        <h5 class="advice-text mb-4">لماذا التأمين الصحي مهم؟</h5>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ شرط أساسي لقبول تأشيرة الدراسة أو التدريب.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ يحميك من تكاليف العلاج المرتفعة.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ مطلوب عند التسجيل في الجامعة.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ للتسجيل الرسمي لدى التأمين العام (Barmer, AOK, TK...)</p>
          </div>
        </div>
      </div>

      <div class="advice-stars my-5">
        <h5 class="advice-text mb-4">الوثائق المكملة</h5>
        <ul class="star-list">
          <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" />قبول جامعي أو من معهد اللغة.</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" />صورة جواز السفر</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" />بيانات الوصول.</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" />إثبات الدفع.</p>
              </li>
            </div>
          </div>
        </ul>
      </div>

      <p class="advice-text mt-4">نحن نتعامل مع أحد الخبراء الألمان البارزين في تلبية خدمات التأمين الصحي للزوار الأجانب في ألمانيا والذين تتجاوز خبرتهم أكثر من 60 عامًا في هذا المجال.. يمكنك مباشرة أن تختار وتحجز تأمينك الصحي المناسب من خلال أحد الروابط التالية:</p>
      
      <!-- تحويل كتل حجز الشركات إلى روابط قابلة للضغط -->
      <a href="https://www.dr-walter.com/" target="_blank" rel="noopener" class="link active d-block text-decoration-none">
        <p class="text-center mb-0">إحجز التأمين الصحي الخاص بإقامة التعليم في ألمانيا وأوروبا من خلال شركة - Dr.WALTER</p>
      </a>
      
      <a href="https://www.fintiba.com/" target="_blank" rel="noopener" class="link mt-4 d-block text-decoration-none">
        <p class="text-center mb-0">إحجز التأمين الصحي الخاص بإقامة التعليم في ألمانيا وأوروبا من خلال شركة - FINTIBA</p>
      </a>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 4. استدعاء الفوتر المشترك

?>
