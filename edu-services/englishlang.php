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
        <li class="breadcrumb-item" aria-current="page">دورات اللغة الألمانية</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container">
      <div class="germanlang-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg5.png');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">برامج دراسية باللغة الإنجليزية في ألمانيا</h2>
        <p class="par-text">ألمانيا تعد وجهة مفضلة للكثير من الطلاب الدوليين بسبب وجود أكثر من 1450 برنامج دراسي يدرس بالكامل بالإنجليزية، مما يتيح لهم بدء الدراسة دون الحاجة لإتقان اللغة الألمانية مسبقاً - شرط استيفاء المتطلبات الأكاديمية.</p>
      </div>

      <div class="advice-check my-5">
        <h5 class="advice-text mb-4">من يمكنه الاستفادة من هذه البرامج</h5>
        <h6>كل من يستوفي الشروط التالية:</h6>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅️شهادة دراسية معترف بها تؤهلك للتسجيل في الجامعة الألمانية.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <p>✅️إثبات كفاءة في اللغة الإنجليزية (TOEFL, IELTS).</p>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12">
            <p>✅️كشف علامات أكاديمي موثّق.</p>
          </div>
          <div class="col-lg-8 col-md-8 col-sm-12">
            <p>✅️وثائق داعمة "سيرة ذاتية، خطاب دافع، إثبات تمويل، تأمين صحي، قبول جامعي".</p>
          </div>
        </div>
      </div>

      <div class="advice-stars">
        <h5 class="advice-text mb-4">متطلبات اللغة بشكل عام</h5>
        <ul class="star-list">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>بنتيجة 80 نقطة كحد أدنى TOEFL.</p>
              </li>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>بنتيجة لا تقل عن 6.0 IELTS.</p>
              </li>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <li>
                <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2"/>برامج الماجستير والدكتوراه تتطلب درجات أعلى مقارنة بالبرامج الجامعية.</p>
              </li>
            </div>
          </div>
        </ul>
      </div>
      
      <div class="note mt-5">
        <p><span style="color: #66aeee;">ملاحظة:</span> كل جامعة تحدد الحد الأدنى الخاص بها بشكل مستقل</p>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 4. استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
