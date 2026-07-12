<?php 
// 1. تحديد بادئة المسار للعودة خطوة للمجلد الرئيسي
$path_prefix = '../'; 

// 2. تمرير ملف الـ CSS الخاص بـ edu-services ديناميكياً ليتم تضمينه في الهيدر المشترك
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
        <li class="breadcrumb-item" aria-current="page">التخصصات الطبية</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container ">
      <div class="coverLetter-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/job/servicesimg1.png'); background-position: center center;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">قائمة أكثر التخصصات إنتشاراً</h2>
        <p class="par-text">يوجد في ألمانيا أكثر من 50 تخصص طبي في مجالات طبية مختلفة وجميعها متوفرة لكلٍ من الأطباء الألمان والأجانب. تختلف مدة التخصصات الطبية من فرع لآخر ولكن بشكل عام، تستغرق أكثر من 5 سنوات في معظم التخصصات الطبية. فيما يلي أكثر التخصصات الطبية في ألمانيا انتشاراً</p>
      </div>
      
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="ملف PDF" />
              <div class="dl-info">
                <div class="dl-title">قائمة أكثر التخصصات الطبية انتشارا</div>
                <div class="dl-sub">اختر تخصصك الطبي</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">...................................................................................................</span>
              <a class="download-link" href="<?php echo $path_prefix; ?>assets/files/medical_specialties_list.pdf" download>Download</a>
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
