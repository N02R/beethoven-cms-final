<?php 
// 1. تحديد بادئة المسار للعودة للمجلد الرئيسي
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
        <li class="breadcrumb-item active" aria-current="page">تكلفة المعيشة في ألمانيا</li>
      </ol>
    </nav>
  </div>
  <!-- Breadcrumb end-->

  <!-- custom-services start -->
  <section class="custom-services py-5">
    <div class="custom-container ">
      <div class="living-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg8.png');">
      </div>
    </div>
  </section>
  <!-- custom-services end -->

  <!-- custom-services-info start -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">تكلفة المعيشة في ألمانيا للطلاب الأجانب</h2>
        <p class="par-text">تُعد تكلفة المعيشة في ألمانيا معتدلة مقارنة بدول أوروبية أخرى، لكنها أعلى من الدول النامية. يحتاج الطالب الأجنبي عادةً بين 700 و900 يورو شهريًا لتغطية الإيجار، الطعام، المواصلات، التأمين وغيرها، وتختلف التكاليف حسب المدينة ونمط الحياة. امتلاك بطاقة الطالب يساعد على تقليل المصاريف، كما يمكن للطلاب العمل جزئيًا لتغطية جزء كبير من نفقهم.</p>
      </div>

      <div class="advice-check py-5">
        <h5 class="advice-text mb-4">نصائح لتقليل النفقات</h5>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <p class="mb-0">✅ اختيار السكن الجامعي أو مشاركة شقة مع طلاب آخرين.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <p class="mb-0">✅ استخدام بطاقة الطالب لخصومات في المواصلات والمتاجر.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <p class="mb-0">✅ فرز النفايات المنزلية إلزامي لتجنب الغرامات والإخلاء.</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <p class="mb-0">✅ تجنّب المكتبات العقارية إلا عند الضرورة، لتوفير التكاليف.</p>
          </div>
        </div>
      </div>

      <div class="advice-stars my-5">
        <h5 class="mb-4 note-text">ملاحظات هامة !!</h5>
        <ul class="star-list list-unstyled p-0">
          <li class="d-flex align-items-start mb-3">
            <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2 mt-1" />
            <p class="mb-0">يتوجب دفع وديعة (Kaution) تعادل إيجار شهر أو شهرين عند توقيع العقد.</p>
          </li>
          <li class="d-flex align-items-start mb-3">
            <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2 mt-1" />
            <p class="mb-0">عند طلب سكن عن طريق مكتب عقاري، قد تُدفع عمولة تتراوح بين 600 إلى 1000 يورو.</p>
          </li>
          <li class="d-flex align-items-start mb-3">
            <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="نجمة" class="ms-2 mt-1" />
            <p class="mb-0">خيار مشاركة شقة (WG) مع طلاب آخرين يساعد في تقليل التكاليف.</p>
          </li>
        </ul>
      </div>
    </div>
  </section>
  <!-- custom-services-info end -->

<?php 
// 4. استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
