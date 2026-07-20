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
        <li class="breadcrumb-item" aria-current="page">الدورة التحضيرية لشهادات اللغة الألمانية</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container">
      <div class="coverLetter-hero custom-hero"
        style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg12.png');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">
          الدورات التحضيرية لشهادات اللغة الألمانية
        </h2>
        <p class="par-text">هي دورات مكثفة تقدم من قبل جامعات حكومية أو معاهد خاصة، تعد الطالب لإجتياز إحدى شهادات اللغة
          المعترف بها مثل :DSH "امتحان اللغة الألمانية للقبول الجامعي"، TestDaf "امتحان اللغة الألمانية كلغة أجنبية"،
          Goethe C2 أو غيرها من الشهادات.. مدة الدورة عادة فصل إلى فصلين دراسيين حسب مستوى الطالب.
        </p>
      </div>
      <div class="advice-check py-5">
        <h5 class="advice-text">أهداف الدورة التحضيرية</h5>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ تجهيز الطالب لغوياً لفهم المواد الجامعية باللغة الألمانية</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ تأهيله لاجتياز اختبار اللغة المطلوب للتخصص أو الجامعة</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅ تطوير المهارات اللغوية الأكاديمية اللازمة للنجاح.</p>
          </div>
        </div>
      </div>
      <p class="red-text">لا يوجد اختبار قبول مباشر، لكن يجب إثبات الكفاءة في اللغة بمستوى لا يقل عن B1 أو B2 للتسجيل في
        الدورة.</p>
      <div class="advice-list py-5">
        <h5 class="advice-text">اماكن الالتحاق والتكلفة</h5>
        <ul>
          <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
              <li><span>جامعات حكومية:</span> رسوم رمزية(150-350€) للفصل او احياناً مجاناً.</li>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
              <li><span>مدارس خاصة:</span> تكلفة شهرية حوالي 400€</li>
            </div>
          </div>
        </ul>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 4. استدعاء الفوتر المشترك

?>
