<?php 
// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي (لأن الملف داخل مجلد فرعي job-services)
$path_prefix = '../'; 

// 2. تمرير ملف الـ CSS الخاص بـ edu-services ديناميكياً بناءً على طلبك
$page_css = [
    'edu-services/css/edu-services.css'
];

// 3. استدعاء الهيدر المشترك
include $path_prefix . 'includes/header.php'; 
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>job.php">التدريب المهني</a></li>
        <li class="breadcrumb-item" aria-current="page">باقة التدريب الطبي</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container">
      <div class="coverLetter-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/job/servicesimg3.png'); background-position: center center;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">عرض وإتفاقية التدريب المهني</h2>
        <p class="par-text">كل عرضٍ (حالة) له تكلفة الخدمة الخاصة به حيث أن كل عرض يتضمن خدمات مختلفة وبذلك يتطلب إجراءات ومراسلات وجهود مختلفة. للحصول على فكرةٍ عامة عن العرض الخاص بك وتكلفة الخدمات الخاصة به، تجد أدناه العروض الأكثر طلباً (مثال لكل عرض).</p>
      </div>

      <div class="advice-stars pt-5">
        <h5 class="note-text">ملاحظات هامة !!</h5>
        <ul class="star-list">
          <li>
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="تنبيه" class="ms-2" />جميع العروض والاتفاقيات تكتب وتملأ باللغة الإنجليزية، للإستفسار عن أي بند أو شرح أي معلومات، لا تتردد <a href="<?php echo $path_prefix; ?>contact.php" class="fw-bold" style="color: #66aeee; text-decoration: none;">بالتواصل معنا</a>.</p>
          </li>
        </ul>
      </div>

      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="ملف PDF" />
              <div class="dl-info">
                <div class="dl-title">عرض واتفاقية التدريب الطبي</div>
                <div class="dl-sub">Example</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">...........................................................................................................</span>
              <a class="download-link" href="<?php echo $path_prefix; ?>assets/files/medical_training_agreement.pdf" download>Download</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 4. استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
