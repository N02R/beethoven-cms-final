<?php 
// 1. تحديد بادئة المسار للعودة للمجلد الرئيسي
$path_prefix = '../'; 

// 2. مسار ملف الـ CSS الخاص بهذه الصفحة (مع إضافة البادئة)
$page_css = [
    $path_prefix . 'css/edu-services.css'
];

// 3. استدعاء الهيدر المشترك
include $path_prefix . 'includes/header.php'; 
?>

  <!-- ===== BREADCRUMB START ===== -->
  <div class="custom-container pt-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="<?php echo $path_prefix; ?>education.php">التعليم العالي</a></li>
        <li class="breadcrumb-item active" aria-current="page">الإستقبال في المطار، المواصلات، الإقامة والسكن</li>
      </ol>
    </nav>
  </div>
  <!-- ===== BREADCRUMB END ===== -->

  <!-- ===== CUSTOM SERVICES START ===== -->
  <section class="custom-services py-5">
    <div class="custom-container">
      <div class="arrival-hero custom-hero" style="background-image: url('<?php echo $path_prefix; ?>assets/img/education/servicesimg9.png');"></div>
    </div>
  </section>
  <!-- ===== CUSTOM SERVICES END ===== -->

  <!-- ===== CUSTOM SERVICES INFO START ===== -->
  <section class="custom-services-info py-5">
    <div class="custom-container">
      <div class="head-info">
        <h2 class="main-text">إستمتع برحلتك إلى ألمانيا، وابدأ حياتك الجديدة دون أية مشقة!</h2>
        <p class="par-text">
          كثيرٌ من الطلبة يَصِلون إلى ألمانيا دون ترتيب وحجز مكان السكن و الإقامة أو حجز وسيلة نقل مناسبة تسهل عليهم الوصول إلى مكان السكن الجديد. سيواجه هؤلاء الطلاب الكثير من الضغط النفسي والمشقه وسينفقون الكثير من المال للمبيت في الفنادق و التنقل بينها!
        </p>
      </div>

      <div class="advice-check py-5">
        <h5 class="advice-text">ما الذي يجب فعله قبل السفر؟</h5>
        <p>قبل السفر إلى ألمانيا، تأكد من التخطيط الجيد لتفادي التوتر والمصاريف غير المتوقعة. إليك أهم التوصيات:</p>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <p>✅ احجز الاستقبال ووسيلة النقل من المطار مسبقًا</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <p>✅ رتب السكن قبل الوصول بشهر لتفادي التكاليف المرتفعة</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <p>✅ تأكد من موقع السكن لتسهيل الإجراءات الرسمية</p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <p>✅ لا تستخدم المواصلات بدون تذكرة لتجنب الغرامات القانونية</p>
          </div>
        </div>
      </div>

      <div class="advice-stars my-5">
        <h5 class="mb-4 note-text">ملاحظات هامة !!</h5>
        <ul class="star-list list-unstyled">
          <li class="mb-3">
            <p><img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2">العثور على سكن خلال فترة قصيرة أمر صعب جدًا، لذا ننصح بحجز السكن مسبقًا.</p>
          </li>
          <li>
            <p>
              <img src="<?php echo $path_prefix; ?>assets/img/education/starList.svg" alt="" class="ms-2">وإذا كنت ترغب بحجز هذه الخدمات من خلال شركتنا، يرجى التواصل عبر <span style="color: #66aeee; font-weight: 500;">البريد الإلكتروني</span> الخاص بالشركة قبل أسبوعين على الأقل من موعد وصولك.
            </p>
          </li>
        </ul>
      </div>
    </div>
  </section>
  <!-- ===== CUSTOM SERVICES INFO END ===== -->

<?php 
// استدعاء الفوتر المشترك
include $path_prefix . 'includes/footer.php'; 
?>
