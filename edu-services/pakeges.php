<?php 
// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي
$path_prefix = '../'; 

// 2. تمرير ملف الـ CSS الخاص بالمجلد الفرعي ديناميكياً ليتم حَقنه في الهيدر
$page_css = [
    'css/edu-services.css'
];

// 3. استدعاء الهيدر المشترك
include $path_prefix . 'includes/header.php'; 
?>

  <!-- Breadcrumb start-->
  <div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item active" aria-current="page">العروض والاتفاقيات</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start-->
  <section class="custom-services py-5">
    <div class="custom-container ">
      <div class="pakeges-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg10.png');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">العروض والاتفاقيات </h2>
        <p class="par-text">كل عرضٍ (حالة) له تكلفة الخدمة الخاصة به حيث أن كل عرض يتضمن خدمات مختلفة وبذلك يتطلب إجراءات ومراسلات وجهود مختلفة. للحصول على فكرةٍ عامة عن العرض الخاص بك وتكلفة الخدمات الخاصة به، تجد أدناه العروض الأكثر طلباً (مثال لكل عرض).</p>
      </div>

      <div class="advice-stars py-5">
        <h5 class="note-text mb-3">ملاحظات هامة !!</h5>
        <ul class="star-list list-unstyled p-0">
          <li class="d-flex align-items-start">
            <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2 mt-1" />
            <p class="mb-0">جميع العروض والاتفاقيات تكتب وتملأ باللغة الإنجليزية، للإستفسار عن أي بند أو شرح أي معلومات، لا تتردد <a href="<?php echo $path_prefix; ?>contact.php" class="fw-bold" style="color: #66aeee; text-decoration: none;">بالتواصل معنا</a>.</p>
          </li>
        </ul>
      </div>

      <div class="dl-card py-5">
        <div class="row g-4">
          <!-- كرت البكالوريوس -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card h-100">
              <h5 class="text-bg text-center">بكالوريوس</h5>
              <div class="card-body d-flex align-items-center gap-3">
                <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="ملف PDF" />
                <div class="card-body-info">
                  <a href="<?php echo $path_prefix; ?>assets/files/BCS-bachelor.pdf" download>Download</a>
                  <p class="mb-0">حزمة واتفاقية البكالوريوس</p>
                </div>
              </div>
            </div>
          </div>

          <!-- كرت الماجستير (نشط) -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-active h-100">
              <h5 class="text-bg text-center text-bg-active">الماجستير</h5>
              <div class="card-body d-flex align-items-center gap-3">
                <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="ملف PDF" />
                <div class="card-body-info">
                  <a href="<?php echo $path_prefix; ?>assets/files/BCS-master.pdf" class="text-active" download>Download</a>
                  <p class="text-active mb-0">حزمة واتفاقية الماجستير</p>
                </div>
              </div>
            </div>
          </div>

          <!-- كرت الدكتوراه -->
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card h-100">
              <h5 class="text-bg text-center">الدكتوراه</h5>
              <div class="card-body d-flex align-items-center gap-3">
                <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="ملف PDF" />
                <div class="card-body-info">
                  <a href="<?php echo $path_prefix; ?>assets/files/BCS-phd.pdf" download>Download</a>
                  <p class="mb-0">حزمة واتفاقية الدكتوراه</p>
                </div>
              </div>
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
