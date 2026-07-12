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
        <li class="breadcrumb-item" aria-current="page">قائمة أسعار الخدمات</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container">
      <div class="coverLetter-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg15.png'); background-position: center center;">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">قائمة الأسعار العامة</h2>
        <p class="par-text">يسعى فريق عمل بيتهوفن سيتي جاهداً لتوفير خدمة عالية الجودة وبتكلفة معقولة وتنافسية للطلبة والمتدربين الأجانب الذين يبحثون عن فرص التعليم العالي والتدريب في ألمانيا. يوضح الجدول أدناه بعض الخدمات التي نسعى لتقديمها مع التكلفة التقديرية لكل خِدمة.</p>
      </div>
      
      <div class="row mt-5">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="download-card">
            <div class="download-row">
              <img src="<?php echo $path_prefix; ?>assets/img/education/Grouppdf.png" alt="ملف PDF" />
              <div class="dl-info">
                <div class="dl-title">قائمة الأسعار العامة</div>
              </div>
              <span class="leader d-lg-block d-md-none d-sm-none" aria-hidden="true">................................................................................................................</span>
              <a class="download-link" href="<?php echo $path_prefix; ?>assets/files/general_price_list.pdf" download>Download</a>
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
