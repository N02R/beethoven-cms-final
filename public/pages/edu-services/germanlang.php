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
        <li class="breadcrumb-item" aria-current="page">دورات اللغة الألمانية</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container ">
      <div class="germanlang-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg4.png');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">دورات اللغة الألمانية باحتراف تدعم خطتك الأكاديمية والمهنية</h2>
        <p class="par-text">اتقن اللغة الألمانية من البداية حتى التفوق مع دوراتنا المعتمدة حسب المستويات الرسمية (A1-C2), تُعدك للدراسة, العمل, التقديم للسفارة, او الحياة اليومية في ألمانيا.</p>
      </div>
      
      <div class="advice-stars my-5">
        <h5 class="mb-4 advice-text">المستويات المتوفرة (طبقًا ل CEFR)</h5>
        <ul class="star-list">
          <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>المستوى A1: للمبتدئين.</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>المستوى A2: المعرفة الأساسية في اللغة.</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>المستوى B1: قبل المتوسط.</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>المستوى B2: معرفة متوسطة في اللغة.</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>المستوى C1: المستوى العلوي.</p>
              </li>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>المستوى C2: مُتقدم.</p>
              </li>
            </div>
          </div>
        </ul>
      </div>

      <div class="advice-check">
        <h5 class="advice-text mb-4">مميزات دوراتنا</h5>
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-12">
            <p>✅ معتمدون من CEFR.</p>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12">
            <p>✅ مدرسون ناطقون أصليون.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ شهادات مقبولة للسفارات والجامعات.</p>
          </div>
          <div class="col-lg-12 col-md-6 col-sm-12">
            <p>✅ دعم في التقدم للإمتحانات (DSH, TestDaf).</p>
          </div>
        </div>

        <h5 class="mt-5 mb-4 advice-text">نصائح للنجاح في الدراسة بالألمانية</h5>
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-12">
            <p>✅ حضّر لامتحانات اللغة مبكرًا.</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <p>✅ مارس مهارات الاستماع والمحادثة يوميًا.</p>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <p>✅ راقب تقدمك من خلال اختبارات دورية.</p>
          </div>
          <div class="col-lg-12 col-md-6 col-sm-12">
            <p>✅ استخدم موارد مساعدة مثل كتب ووسائط صوت.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 4. استدعاء الفوتر المشترك

?>
