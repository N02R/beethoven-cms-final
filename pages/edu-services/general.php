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
        <li class="breadcrumb-item" aria-current="page">متطلبات التأشيرة</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container ">
      <div class="coverLetter-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg14.png'); ">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">قائمة مراجعة عامة لِمتطلبات تأشيرات مختلفة</h2>
        <p class="par-text">من واقع خبرتنا، قمنا بتجميع أهم المتطلبات لتأشيرة الطالب، التدريب المهني، التدريب الطبي، والعمل في قائمة مراجعة عامة لتسهيل عملية جمع الوثائق المطلوبة، بالإضافة إلى جعل العملية أكثر وضوحًا وسهولة.</p>
      </div>

      <div class="advice-stars py-5">
        <h5 class="mb-3 note-text"> ملاحظة !!</h5>
        <ul class="star-list">
          <li>
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2" />تستطيع تَعديل القائمة وإختيار المُتطلبات الخاصة بك وذلك بحسب نوع التأشيرة التي ترغب بالحصول عليها.</p>
          </li>
        </ul>
      </div>

      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card mb-3">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="ملف PDF" />
              <div class="dl-info">
                <div class="dl-title">قائمة مراجعات عامة لِمتطلبات تأشيرات مختلفة</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">..............................................................................</span>
              <a class="download-link" href="<?php echo $path_prefix; ?>assets/files/checklist.pdf" download>Download</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 4. استدعاء الفوتر المشترك

?>
